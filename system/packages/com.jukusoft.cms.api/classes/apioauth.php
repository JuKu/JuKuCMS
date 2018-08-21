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

class ApiOAuth {

	protected $row = array();

	public function __construct() {
		//
	}

	/**
	 * load oauth token and check, if token exists
	 */
	public function load (string $token) : bool {
		if (Cache::contains("oauth", "token_" + $token)) {
			$this->row = Cache::get("oauth", "token_" + $token);

			if ($this->isExpired()) {
				//clear token in cache, because key has expired
				Cache::clear("oauth", "token_" + $token);
			}
		} else {
			//get token from database
			$this->row = Database::getInstance()->getRow("SELECT * FROM `{praefix}api_oauth` WHERE `secret_key` = :token AND `expires` > NOW(); ", array(
				'token' => $token
			));

			if (!$this->row) {
				//cache value
				Cache::put("oauth", "token_" + $token, $this->row);
			}
		}

		return $this->row !== FALSE && !$this->isExpired();
	}

	public function getKey () : string {
		return $this->row['secret_key'];
	}

	public function isExpired () : bool {
		return $this->row !== "0000-00-00 00:00:00" && strtotime($this->row['expires']) <= strtotime('now');
	}

	public function getUserID () : int {
		return (int) $this->row['userID'];
	}

	public function getCreatedTimestamp () : string {
		return $this->row['created'];
	}

	/**
	 * create an oauth token for a specific user
	 *
	 * @param $userID integer id of user
	 *
	 * @return oauth key / token
	 */
	public static function createToken (int $userID) : string {
		//get setting
		$key_length = (int) Settings::get("oauth_key_length", 255);
		$expires_seconds = (int) Settings::get("oauth_expire_seconds", 86400);//default value of 1 day

		//generate a random token
		$token = PHPUtils::randomString($key_length);

		//insert token into database
		Database::getInstance()->execute("INSERT INTO `{praefix}api_oauth` (
			`secret_key`, `userID`, `created`, `expires`
		) VALUES (
			:secret_key, :userID, CURRENT_TIMESTAMP, DATE_ADD(NOW(), INTERVAL :seconds SECOND)
		); ", array(
			'secret_key' => $token,
			'userID' => $userID,
			'seconds' => $expires_seconds
		));

		return $token;
	}

	/**
	 * remove an oauth token for a specific user
	 *
	 * @param $token string secret key token
	 */
	public static function removeToken (string $token) {
		Database::getInstance()->execute("DELETE FROM `{praefix}api_oauth` WHERE `token` = :token; ", array('token' => $token));

		//clear token in cache, if exists
		Cache::clear("oauth", "token_" + $token);
	}

	/**
	 * remove all expired tokens from database
	 */
	public static function removeAllOutdatedTokensToken () {
		Database::getInstance()->execute("DELETE FROM `{praefix}api_oauth` WHERE `expires` < NOW(); ");

		//clear token cache
		Cache::clear("oauth");
	}

	/**
	 * handles authentification with OAuth, called by ApiMethod::executeApiMethod()
	 *
	 * @since 0.1.0
	 */
	public static function apiOAuth () : array {
		$result = array();
		$result['status'] = 200;

		if (isset($_REQUEST['login']) && !empty($_REQUEST['login']) && isset($_POST['password']) && !empty($_POST['password'])) {
			$username = $_REQUEST['login'];
			$password = $_POST['password'];

			$res = User::current()->loginByUsername($username, $password);

			if ($res['success'] === true) {
				//login was successful --> create new access token
				$access_token = self::createToken(User::current()->getID());

				$result['access_token'] = $access_token;
			} else {
				$result['error'] = $res['error'];
			}
		}

		//check, if user is authentificated
		$result['authentificated'] = User::current()->isLoggedIn();
		$result['userID'] = User::current()->getID();
		$result['username'] = User::current()->getUsername();

		return $result;
	}

}

?>
