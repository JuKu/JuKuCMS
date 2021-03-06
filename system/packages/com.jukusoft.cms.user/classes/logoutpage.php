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
 * Date: 19.03.2018
 * Time: 13:52
 */

class LogoutPage extends HTMLPage {

	protected $error = false;

	public function setCustomHeader() {
		//check, if session was started
		PHPUtils::checkSessionStarted();

		if (!Security::checkCSRFToken()) {
			$this->error = true;

			//dont logout user, because csrf token isnt correct
			return;
		}

		//logout user
		User::current()->logout();

		Events::throwEvent("after_logout");

		//get domain
		$domain = Registry::singleton()->getObject("domain");

		//generate index url
		$index_url = DomainUtils::generateURL($domain->getHomePage());

		header("Location: " . $index_url);

		//flush gzip buffer
		ob_end_flush();

		exit;
	}

	public function getContent(): string {
		if ($this->error) {
			return "Wrong CSRF token!";
		}

		return "";
	}

}

?>
