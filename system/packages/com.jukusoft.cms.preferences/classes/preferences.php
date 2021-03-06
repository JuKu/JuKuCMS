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
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 16.04.2018
 * Time: 21:19
 */

class Preferences {

	protected $area = "";
	protected $prefs = array();
	protected $changed_prefs = array();

	public function __construct(string $area) {
		$area = strtolower($area);

		if (!PHPUtils::startsWith($area, "plugin_") && !PHPUtils::startsWith($area, "style_")) {
			throw new IllegalArgumentException("preferences area name should start with 'plugin_' or 'style_'´.");
		}

		$this->area = $area;

		if (Cache::contains("preferences", "preferences-" . $area)) {
			$this->prefs = Cache::get("preferences", "preferences-" . $area);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}preferences` WHERE `area` = :area; ", array('area' => $area));

			foreach ($rows as $row) {
				$this->prefs[$row['key']] = unserialize($row['value']);
			}

			//cache preferences
			Cache::put("preferences", "preferences-" . $area, $this->prefs);
		}
	}

	public function contains (string $key) : bool {
		return isset($this->prefs[$key]);
	}

	public function get (string $key, $default = null) {
		if (!isset($this->prefs[$key])) {
			return $default;
		}

		return $this->prefs[$key];
	}

	public function put (string $key, $value, bool $auto_save = false) {
		$contains_key = $this->contains($key);

		$this->prefs[$key] = $value;

		if ($auto_save) {
			//update database
			Database::getInstance()->execute("INSERT INTO `{praefix}preferences` (
				`key`, `area`, `value`
			) VALUES (
				:key, :area, :value
			) ON DUPLICATE KEY UPDATE `value` = :value; ", array(
				'key' => $key,
				'area' => $this->area,
				'value' => serialize($value)
			));
		} else {
			$this->changed_prefs[$key] = $contains_key;
		}
	}

	public function listAll () : array {
		return $this->prefs;
	}

	public function clearAll () {
		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}preferences` WHERE `area` = :area; ", array('area' => $this->area));

		$this->prefs = array();
		$this->changed_prefs = array();

		//clear cache
		Cache::clear("preferences", "preferences-" . $this->area);
	}

	/**
	 * write all values to database
	 */
	public function save () {
		$lines = array();
		$values = array();

		$values['area'] = $this->area;

		$i = 1;

		foreach ($this->changed_prefs as $key=>$contains_key) {
			$lines[] = "(:key" . $i . ", :area, :value" . $i . ")";
			$values['key' . $i] = $key;
			$values['value' . $i] = serialize($this->prefs[$key]);

			$i++;
		}

		$lines_str = implode(",\r\n", $lines);

		Database::getInstance()->execute("INSERT INTO `{praefix}preferences` (
			`key`, `area`, `value`
		) VALUES " . $lines_str . " ON DUPLICATE KEY UPDATE `value` = VALUES(value); ", $values);
	}

}

?>
