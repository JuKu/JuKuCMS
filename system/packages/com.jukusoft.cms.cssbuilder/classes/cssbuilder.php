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


/**
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 22.03.2018
 * Time: 13:23
 */

class CSSBuilder {

	public function __construct() {
		//
	}

	public function generateCSS (string $style_name, string $media = "ALL") : string {
		//validate values
		$style_name = Validator_Filename::get($style_name);
		$media = Validator_Filename::get($media);

		$md5_filename = md5($style_name);
		$css_cache_path = CACHE_PATH . "cssbuilder/" . $md5_filename . ".css";

		$css_files = array();

		//get css files from style.json
		if (file_exists(STYLE_PATH . $style_name . "/style.json")) {
			$json = json_decode(file_get_contents(STYLE_PATH . $style_name . "/style.json"), true);

			if (isset($json['css']) && is_array($json['css'])) {
				foreach ($json['css'] as $css_file) {
					$full_path = STYLE_PATH . $style_name . "/" . $css_file;
					$css_files[] = $full_path;
				}
			}
		}

		//load css files from database
		$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}css_files` WHERE (`style` = :style OR `style` = 'ALL') AND (`media` = :media OR `media` = 'ALL') AND `activated` = '1'; ", array(
			'style' => $style_name,
			'media' => $media
		));

		foreach ($rows as $row) {
			$css_files[] = $row['css_file'];
		}

		$buffer = "";

		foreach ($css_files as $css_file) {
			//first check, if file exists
			if (!file_exists($css_file)) {
				continue;
			}

			//add file content to buffer
			$buffer .= file_get_contents($css_file) . "\n";
		}

		//$code = preg_replace("/\s\s+/", " ", $code);

		//remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

		// remove space after colons
		$buffer = str_replace(': ', ':', $buffer);

		//remove whitespace
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

		//TODO: cache buffer

		return $buffer;
	}

	public function getCachePath (string $style, string $media = "ALL") : string {
		$md5_filename = md5($style . "_" . $media);
		$css_cache_path = CACHE_PATH . "cssbuilder/" . $md5_filename . ".css";

		return $css_cache_path;
	}

}

?>
