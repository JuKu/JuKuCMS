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
 * Date: 17.04.2018
 * Time: 14:28
 */

class Plugin_HTTPAuth_HTTPAuth {

	public static function headerEvent () {
		//get preferences first
		$prefs = new Preferences("plugin_httpauth");

		$activated = $prefs->get("activated", false);

		if (!$activated) {
			return;
		}

		//check, if user is logged in
		if (User::current()->isLoggedIn()) {
			//http auth is not required, because user is already logged in
			return;
		}

		//check, if credentials was already send
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			self::sendHeader($prefs);
		} else {
			echo "<p>Hallo {$_SERVER['PHP_AUTH_USER']}.</p>";
			echo "<p>Sie gaben {$_SERVER['PHP_AUTH_PW']} als Passwort ein.</p>";
		}
	}

	protected static function sendHeader (Preferences $prefs) {
		header('WWW-Authenticate: Basic realm="My Realm"');
		header('HTTP/1.0 401 Unauthorized');

		//text which will be sended, if user clicks on abort
		echo $prefs->get("abort_text", "<h1>401 Authorization Required</h1>");

		ob_end_flush();
		exit;
	}

}

?>
