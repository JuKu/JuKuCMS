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
 * Date: 07.04.2018
 * Time: 12:18
 */

class Translator {

	protected static $backend = null;

	public static function translate (string $key, string $category = "") : string {
		self::getBackend()->translate($key, $category);
	}

	public static function &getBackend () : Translator_Backend {
		if (self::$backend == null) {
			//get translator backend
			$class_name = Settings::get("translator_class_name", "GetTextBackend");

			self::$backend = new $class_name();

			//initialize backend with current language
			self::$backend->init(Registry::singleton()->getSetting("lang_token"));
		}

		return self::$backend;
	}

}

?>
