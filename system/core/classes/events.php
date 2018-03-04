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
 * Date: 04.03.2018
 * Time: 19:05
 */

class Events {

	protected static $events = array();

	protected static $isInitialized = false;

	public static function init () {
		if (Cache::get2ndLvlCache()->contains("events", "events")) {
			self::$events = Cache::get2ndLvlCache()->get("events", "events");
		} else {
			//load events from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{PRAEFIX}events` WHERE `activated` = '1'; ");

			//iterate through rows
			foreach ($rows as $row) {
				//get name of event
				$name = $row['name'];

				//check, if name exists in array
				if (!isset(self::$events[$name])) {
					self::$events[$name] = array();
				}

				//add row to array
				self::$events[$name][] = $row;
			}

			//put events into cache
			Cache::get2ndLvlCache()->put("events", "events", self::$events);
		}

		//set initialized flag to true
		self::$isInitialized = true;
	}

	public static function throwEvent ($name, $params = array()) {
		if (!is_array($params)) {
			throw new IllegalArgumentException("second parameter params has to be an array.");
		}

		//check, if events was initialized first
		if (!self::$isInitialized) {
			//initialize events
			self::init();
		}

		if (isset(self::$events[$name])) {
			foreach (self::$events as $event) {
				self::executeEvent($event, $params);
			}
		}
	}

	protected static function executeEvent ($row, $params) {
		//TODO: execute event here
	}

}

?>
