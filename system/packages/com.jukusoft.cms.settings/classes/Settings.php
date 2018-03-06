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
 * Date: 06.03.2018
 * Time: 14:11
 */

class Settings {

	//in-memory cache of settings
	protected static $settings = array();

	//flag, if global settings was initialized
	protected static $initialized = false;

	/**
	 * initialize settings and get global settings
	 */
	protected static function loadAllSettings () {
		if (Cache::contains("global_settings", "all-settings")) {
			self::$settings = Cache::get("global_settings", "all-settings");
		} else {
			//load settings from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}global_settings` WHERE `activated` = '1'; ");

			self::$settings = array();

			foreach ($rows as $row) {
				self::$settings[$row['key']] = $row;
			}

			//cache rows
			Cache::put("global_settings", "all-settings", self::$settings);
		}

		self::$initialized = true;
	}

}

?>
