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
 * Date: 21.08.2018
 * Time: 13:16
 */

class ApiAuth {

	protected static $instance = null;

	protected $oauth = null;
	protected $is_authentificated = false;//flag, if user is authentificated

	/**
	 * ApiAuth constructor.
	 */
	public function __construct() {
		//
	}

	public function init () {
		//check for secret token
		if (isset($_REQUEST['access_token'])) {
			$access_token = Validator_String::get($_REQUEST['access_token']);

			//try to verify access token
			$oauth = new ApiOAuth();
			if (!$oauth->load($access_token)) {
				//api token isn't valide
				return;
			}

			//user is authentificated
			$this->is_authentificated = true;
			$this->oauth = $oauth;

			//set userID and load user
			User::current()->load($oauth->getUserID());
		}
	}

	/**
	 * check, if user is authentificated
	 *
	 * @return bool
	 */
	public function isAuthentificated () : bool {
		return $this->is_authentificated;
	}

	public static function &getInstance () : ApiAuth {
		if (self::$instance == null) {
			self::$instance = new ApiAuth();
		}

		return self::$instance;
	}

}

?>