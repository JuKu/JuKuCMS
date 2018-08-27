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
 * Date: 21.08.2018
 * Time: 19:33
 */

class Logger {

	//instance of logging provider
	protected static $provider = null;

	/**
	 * initialize logging provider
	 */
	public static function init () {
		self::getProvider()->init();
	}

	/**
	 * log message
	 */
	public static function log (string $level, string $message, $args = array()) {
		self::getProvider()->log($level, $message, $args);
	}

	/**
	 * lazy logging - write logs into file or send it to server
	 */
	public static function send () {
		self::getProvider()->send();
	}

	public static function &getProvider () : LogProvider {
		if (self::$provider == null) {
			if (defined("LOGGING_ENABLED") && LOGGING_ENABLED == true) {
				//search for loggging provider
				$logging_provider = Settings::get("logging_provider", "EmptyLogProvider");

				//create new instance of class in string
				self::$provider = new $logging_provider();
			} else {
				//use dummy logging provider, which doesnt do anything
				self::$provider = new EmptyLogProvider();
			}
		}

		return self::$provider;
	}

}

?>
