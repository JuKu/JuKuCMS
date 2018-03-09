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
 * Language Detection
 */

class Lang {

	protected static $supported_languages = array();
	protected static $initialized = false;

	//https://paulund.co.uk/auto-detect-browser-language-in-php

	public static function getPrefLangToken () : string {
		return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}

	public static function getLangToken (array $supported_lang_tokens) : string {
		//http://php.net/manual/fa/function.http-negotiate-language.php

		//https://stackoverflow.com/questions/6038236/using-the-php-http-accept-language-server-variable

		//https://stackoverflow.com/questions/3770513/detect-browser-language-in-php

		return self::prefered_language($supported_lang_tokens);//http_negotiate_language($supported_lang_tokens);
	}

	public static function loadSupportedLangs () {
		if (Cache::contains("supported-languages", "list")) {
			self::$supported_languages = Cache::get("supported-languages", "list");
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}supported_languages`; ");

			$array = array();

			foreach ($rows as $row) {
				$array[$row['lang_token']] = $row;
			}

			//cache values
			Cache::put("supported-languages", "list", $array);

			self::$supported_languages = $array;
		}

		self::$initialized = true;
	}

	protected static function loadIfAbsent () {
		if (!self::$initialized) {
			self::loadSupportedLangs();
		}
	}

	public static function listSupportedLangTokens () {
		//load tokens, if not initialized
		self::loadIfAbsent();

		$keys = array_keys(self::$supported_languages);

		//get default language
		$default_lang = Settings::get("default_lang");

		if (!in_array($default_lang, $keys)) {
			throw new IllegalStateException("default language (in global settings) isnt a supported language");
		}

		//add as first element
		array_unshift($keys, $default_lang);

		return $keys;
	}

	public static function addLang (string $token, string $title) {
		Database::getInstance()->execute("INSERT INTO `{praefix}supported_languages` (
			`lang_token`, `title`
		) VALUES (
			:token, :title
		); ", array(
			'token' => $token,
			'title' => $title
		));

		//clear local in-memory cache
		self::$initialized = false;

		//clear cache
		Cache::clear("supported-languages");
	}

	public static function addLangOrUpdate (string $token, string $title) {
		Database::getInstance()->execute("INSERT INTO `{praefix}supported_languages` (
			`lang_token`, `title`
		) VALUES (
			:token, :title
		) ON DUPLICATE KEY UPDATE `title` = :title; ", array(
			'token' => $token,
			'title' => $title
		));

		//clear local in-memory cache
		self::$initialized = false;

		//clear cache
		Cache::clear("supported-languages");
	}

	/**
	 * determine which language out of an available set the user prefers most
	 *
	 * @param $available_languages array with language-tag-strings (must be lowercase) that are available
	 * @param $http_accept_language a HTTP_ACCEPT_LANGUAGE string (read from $_SERVER['HTTP_ACCEPT_LANGUAGE'] if left out)
	 */

	/**
	 * determine which language out of an available set the user prefers most
	 *
	 * @param $available_languages array with language-tag-strings (must be lowercase) that are available
	 * @param $http_accept_language string HTTP_ACCEPT_LANGUAGE string (read from $_SERVER['HTTP_ACCEPT_LANGUAGE'] if left out)
	 *
	 * @return prefered language
	 */
	protected static function prefered_language (array $available_languages, string $http_accept_language = "auto") : string {
		// if $http_accept_language was left out, read it from the HTTP-Header
		if ($http_accept_language == "auto") $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

		// standard  for HTTP_ACCEPT_LANGUAGE is defined under
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
		// pattern to find is therefore something like this:
		//    1#( language-range [ ";" "q" "=" qvalue ] )
		// where:
		//    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
		//    qvalue         = ( "0" [ "." 0*3DIGIT ] )
		//            | ( "1" [ "." 0*3("0") ] )
		preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
			"(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
			$http_accept_language, $hits, PREG_SET_ORDER);

		// default language (in case of no hits) is the first in the array
		$bestlang = $available_languages[0];
		$bestqval = 0;

		foreach ($hits as $arr) {
			// read data from the array of this hit
			$langprefix = strtolower ($arr[1]);
			if (!empty($arr[3])) {
				$langrange = strtolower ($arr[3]);
				$language = $langprefix . "-" . $langrange;
			}
			else $language = $langprefix;
			$qvalue = 1.0;
			if (!empty($arr[5])) $qvalue = floatval($arr[5]);

			// find q-maximal language
			if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
				$bestlang = $language;
				$bestqval = $qvalue;
			}
			// if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
			else if (in_array($langprefix,$available_languages) && (($qvalue*0.9) > $bestqval)) {
				$bestlang = $langprefix;
				$bestqval = $qvalue*0.9;
			}
		}
		return $bestlang;
	}

}

?>
