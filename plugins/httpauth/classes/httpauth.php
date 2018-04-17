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

	//http://php.net/manual/de/features.http-auth.php

	public static function headerEvent () {
		//get preferences first
		$prefs = new Preferences("plugin_httpauth");

		$activated = $prefs->get("activated", true);

		if (!$activated) {
			return;
		}

		//check, if user is logged in
		if (User::current()->isLoggedIn()) {
			//http auth is not required, because user is already logged in
			return;
		}

		//check, if credentials was already send
		if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
			self::sendHeader($prefs);
		} else {
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];

			//try to login
			$res = User::current()->loginByUsername($username, $password);

			if ($res['success'] !== true) {
				//send http header again
				self::sendHeader($prefs);
			} else {
				//login successful, show redirect
				if (isset($_REQUEST['redirect_url']) && !empty($_REQUEST['redirect_url'])) {
					//TODO: check for security issues, maybe we should check if redirect_url is a known domain

					header("Location: " . urldecode($_REQUEST['redirect_url']));

					//flush gzip buffer
					ob_end_flush();

					exit;
				} else {
					//redirect to index page

					//get domain
					$domain = Registry::singleton()->getObject("domain");

					//generate index url
					$index_url = DomainUtils::generateURL($domain->getHomePage());

					header("Location: " . $index_url);

					//flush gzip buffer
					ob_end_flush();

					exit;
				}
			}
		}
	}

	protected static function sendHeader (Preferences $prefs) {
		$realm_name = $prefs->get("realm_name", "Website");

		//send http header, so browser will show a login form
		header('WWW-Authenticate: Basic realm="' . $realm_name . '"');
		header('HTTP/1.0 401 Unauthorized');

		//text which will be sended, if user clicks on abort
		echo $prefs->get("abort_text", "<h1>401 Authorization Required</h1>");

		ob_end_flush();
		exit;
	}

	public static function logoutEvent () {
		//because browser safes http auth credentials by default, we need to do a little trick to clear browser auth cache
		header("Location: " . DomainUtils::getProtocol() . "foo:bar@" . DomainUtils::getBaseURL(true));

		//echo "Location: " . DomainUtils::getProtocol() . "foo:bar@" . DomainUtils::getBaseURL(true);

		exit;
	}

}

?>
