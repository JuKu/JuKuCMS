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
 * Holds variables which can be shown / used in templates
 *
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 07.03.2018
 * Time: 15:22
 */
class Registry {

	protected static $objects = array();
	protected static $settings = array();
	protected static $instance = null;

	public function __construct () {
		//
	}

	public static function &singleton () {

		if (self::$instance == null) {
			self::$instance = new Registry();
		}

		return self::$instance;

	}

	public function storeObject ($key, &$object) {
		if (is_string($object)) {
			$object = new $object();
		}

		self::$objects[$key] = $object;
	}

	public function getObject ($key) {
		if (!isset(self::$objects[$key])) {
			throw new IllegalStateException("registry key '" . $key . "' doesnt exists.");
		}

		if (is_object(self::$objects[$key])) {
			return self::$objects[$key];
		} else {
			throw new IllegalStateException("key '" . $key.  "' isnt an object.");
		}
	}

	public function setSetting ($key, $value) {
		self::$settings[$key] = $value;
	}

	public function getSetting ($key) {
		if (!isset(self::$settings[$key])) {
			throw new IllegalStateException("Registry-Settings key '" . htmlentities($key) . "' doesnt exists.");
		}

		return self::$settings[$key];
	}

	public function listSettings () {
		return self::$settings;
	}

}

?>
