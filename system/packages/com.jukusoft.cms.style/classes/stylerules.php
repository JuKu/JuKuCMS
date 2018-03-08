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

class StyleRules {

	protected static $rules = array();
	protected static $initialized = false;

	/**
	 * default constructor
	 */
	public function __construct() {
		//
	}

	public static function loadAllRules () {
		if (Cache::contains("style", "style-rules")) {
			self::$rules = Cache::get("style", "style-rules");
		} else {
			//get all style rules from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}style_rules` WHERE `activated` = '1' ORDER BY `order`; ");

			$rules = array();

			foreach ($rows as $row) {
				$parentID = $row['parent'];

				if (!isset($rules[$parentID])) {
					$rules[$parentID] = array();
				}

				$rules[$parentID][] = $row;
			}

			self::$rules = $rules;

			//cache array
			Cache::put("style", "style-rules", self::$rules);
		}

		//set initialized flag
		self::$initialized = true;
	}

	protected static function initIfAbsent () {
		if (!self::$initialized) {
			self::loadAllRules();
		}
	}

}

?>
