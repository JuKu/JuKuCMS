<?php

/**
 * Copyright (c) 2018 Justin Kuenzel (jukusoft.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Robots {

	//https://developers.google.com/search/reference/robots_txt?hl=de

	protected $robots = array();

	public function __construct () {
		//
	}

	public function loadFromDB () {
		$rows = DataBase::getInstance()->listRows("SELECT * FROM `{prefix}robots` WHERE `activated` = '1'; ");
		$this->robots = $rows;
	}

	public function sortByUserAgent () {
		$array = array();

		foreach ($this->robots as $row) {
			if (!isset($row['useragent'])) {
				$array[$row['useragent']] = array();
			}

			$array[$row['useragent']][] = $row;
		}

		return $array;
	}

	public function writeFile () {
		$array = $this->sortByUserAgent();

		if (!is_writable(ROOT_PATH . "robots.txt")) {
			throw new IllegalStateException(ROOT_PATH . "robots.txt is not writable, please correct file permissions!");
		}

		$handle = fopen(ROOT_PATH . "robots.txt", "w");

		foreach ($array as $useragent=>$value) {

			fwrite($handle, "User-agent: " . $useragent . "\r\n");

			foreach ($value as $line) {
				fwrite($handle, "" . $line['option'] . ": " . $line['value'] . "\r\n");
			}

			fwrite($handle, "\r\n");

		}

		fclose($handle);
	}

	public function getContent () {
		$array = $this->sortByUserAgent();

		$buffer = "";

		foreach ($array as $useragent=>$value) {
			$buffer .= "User-agent: " . $useragent . "\r\n";

			foreach ($value as $line) {
				$buffer .= "" . $line['option'] . ": " . $line['value'] . "\r\n";
			}

			$buffer .= "\r\n";
		}

		return $buffer;
	}

	public static function addRule (string $option, string $value, string $useragent = "*") {
		Database::getInstance()->execute("INSERT INTO `{praefix}robots` (
			`useragent`, `option`, `value`, `activated`
		) VALUES (
			:useragent, :option, :value '1'
		) ON DUPLICATE KEY UPDATE `option` = :option, ``activated` = '1'; ", array(
			'useragent' => $useragent,
			'option' => $option,
			'value' => $value
		));
	}

}

?>
