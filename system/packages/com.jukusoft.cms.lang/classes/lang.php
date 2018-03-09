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

	public static function getPrefLangToken () : string {
		return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}

	public static function getLangToken (array $supported_lang_tokens) : string {
		//http://php.net/manual/fa/function.http-negotiate-language.php

		//https://stackoverflow.com/questions/6038236/using-the-php-http-accept-language-server-variable

		return http_negotiate_language($supported_lang_tokens);
	}

}

?>
