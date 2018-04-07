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
 * Time: 12:20
 */

class GetTextBackend implements Translator_Backend {

	//current language token
	protected $lang_token = "";

	//current domain
	protected $domain = "";

	/**
	 * initialize translator backend
	 *
	 * @param $lang_token $lang_token contains language & country, e.q. de_DE
	 */
	public function init(string $lang_token) {
		//check, if gettext is available
		if (!PHPUtils::isGettextAvailable()) {
			throw new IllegalStateException("PHP extension 'gettext' is not available.");
		}

		putenv("LANG=" . $lang_token);
		setlocale(LC_ALL, $lang_token);
	}

	public function translate(string $key, string $domain = "", array $params = array()): string {
		$text = "";

		if (empty($domain)) {
			$text = gettext($key);
		} else {
			$text = dgettext($domain, $key);
		}

		if (!empty($params)) {
			foreach ($params as $key=>$value) {
				//replace variables
				$text = str_replace("{" . $key . "}", $value, $text);
			}
		}

		return $text;
	}

	public function n_translate (string $key, string $plural_key, int $n, string $domain = "", array $params = array()) : string {
		$text = "";

		if (empty($domain)) {
			$text = ngettext($key, $plural_key, $n);
		} else {
			$text = dngettext($domain, $key, $plural_key, $n);
		}

		if (!empty($params)) {
			foreach ($params as $key=>$value) {
				//replace variables
				$text = str_replace("{" . $key . "}", $value, $text);
			}
		}

		return $text;
	}

	public function bindLangPack(string $domain, string $path) {
		bindtextdomain($domain, $path);
		bind_textdomain_codeset($domain, 'UTF-8');
	}

	public function setDefaultDomain(string $domain) {
		textdomain($domain);
	}
}

?>
