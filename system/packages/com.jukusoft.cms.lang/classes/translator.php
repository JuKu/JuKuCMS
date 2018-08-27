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
 * Date: 07.04.2018
 * Time: 12:18
 */

class Translator {

	/**
	 *
	 */
	protected static $backend = null;

	/**
	 * translate a string
	 *
	 * @param string $key message to translate
	 * @param string $domain domain where to search key (optional)
	 * @param array $params array with params to replace in translated message
	 *
	 * @throws IllegalStateException if settings key "translator_class_name" is not set
	 * @see Translator_Backend::translate()
	 * @since 0.1.0
	 *
	 * @return string translated message
	 */
	public static function translate (string $key, string $domain = "", $params = null) : string {
		return self::getBackend()->translate($key, $domain, $params);
	}

	/**
	 * translate a string, plural version of translate()
	 *
	 * @param string $key message to translate
	 * @param string $domain domain where to search key (optional)
	 * @param array $params array with params to replace in translated message
	 *
	 * @throws IllegalStateException if settings key "translator_class_name" is not set
	 * @see Translator_Backend::n_translate()
	 * @since 0.1.0
	 *
	 * @return string translated message
	 */
	public static function n_translate (string $key, string $plural_key, int $n, string $domain = "", $params = null) : string {
		return self::getBackend()->n_translate($key, $plural_key, $n, $domain, $params);
	}
	
	public static function translateTitle (string $token) {
		//translate title
		if (strpos($token, "lang_") !== FALSE) {
			$array1 = explode("_", $token);

			if (count($array1) == 2) {
				//translate
				$token = Translator::translate($array1[1]);
			} else if (count($array1) == 3) {
				//translate with domain
				$token = Translator::translate($array1[2], $array1[1]);
			} else if (count($array1) > 3) {
				$token_parts = array();

				for ($i = 2; $i < count($array1); $i++) {
					$token_parts[] = $array1[$i];
				}

				//generate final token
				$token = implode("_", $token_parts);

				//translate with domain
				$token = Translator::translate($token, $array1[1]);
			}
		}

		return $token;
	}

	/**
	 * get current instance of translator backend
	 *
	 * @see GetTextBackend
	 *
	 * @throws IllegalStateException if settings key "translator_class_name" is not set
	 *
	 * @return Translator_Backend instance of translator backend
	 */
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
