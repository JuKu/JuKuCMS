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
 * Time: 12:19
 */

interface Translator_Backend {

	/**
	 * initialize translator backend
	 *
	 * @param $lang_token $lang_token contains language & country, e.q. de_DE
	 */
	public function init (string $lang_token);

	/**
	 * translate a string
	 *
	 * @param string $key message to translate
	 * @param string $domain domain where to search key (optional)
	 * @param array $params array with params to replace in translated message
	 *
	 * @return string translated message
	 */
	public function translate (string $key, string $domain = "", $params = null) : string;

	/**
	 * translate a string, plural version of translate()
	 *
	 * @see Translator_Backend::translate()
	 * @since 0.1.0
	 *
	 * @param string $key message to translate
	 * @param string $domain domain where to search key (optional)
	 * @param array $params array with params to replace in translated message
	 *
	 * @return string translated message
	 */
	public function n_translate (string $key, string $plural_key, int $n, string $domain = "", $params = null) : string;

	public function bindLangPack (string $domain, string $path);

	public function setDefaultDomain (string $domain);

}

?>
