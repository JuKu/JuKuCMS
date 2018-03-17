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
 * Date: 15.03.2018
 * Time: 15:46
 */

class User {

	//instance of current (logged-in / guest) user
	protected static $instance = null;

	//current userID
	protected $userID = -1;

	//current username
	protected $username = "Guest";

	//flag, if user is logged in
	protected $isLoggedIn = false;

	//current database row
	protected $row = null;

	public function __construct() {
		//
	}

	public function load (int $userID = -1) {
		//check, if user is logged in
		if ($userID === -1) {
			if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] === true) {
				if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
					throw new IllegalStateException("userID is not set in session.");
				}

				if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
					throw new IllegalStateException("username is not set in session.");
				}

				$this->userID = (int) $_SESSION['userID'];
				$this->username = $_SESSION['username'];
				$this->isLoggedIn = true;

				//TODO: update online state in database
			} else {
				$this->userID = (int) Settings::get("guest_userid", "-1");
				$this->username = Settings::get("guest_username", "Guest");
				$this->isLoggedIn = false;
			}
		} else {
			$this->userID = (int) $userID;
		}

		Events::throwEvent("before_load_user", array(
			'userID' => &$this->userID,
			'isLoggedIn' => &$this->isLoggedIn,
			'user' => &$this
		));

		//try to load from cache
		if (Cache::contains("user", "user-" . $this->userID)) {
			$this->row = Cache::get("user", "user-" . $this->userID);
		} else {
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID AND `activated` = '1'; ", array(
				'userID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $this->userID
				)
			));

			if (!$row) {
				$logout_user = true;

				//user not found, throw an event, so plugins can handle this (optional)
				Events::throwEvent("user_not_found", array(
					'userID' => &$this->userID,
					'username' => &$this->username,
					'isLoggedIn' => &$this->isLoggedIn,
					'row' => &$row,
					'logout_user' => &$logout_user,
					'user' => &$this
				));

				if ($logout_user) {
					//logout user
					$this->logout();
				}
			} else {
				//remove password hash from row
				unset($row['password']);

				Events::throwEvent("before_cache_user", array(
					'userID' => &$this->userID,
					'username' => &$this->username,
					'isLoggedIn' => &$this->isLoggedIn,
					'row' => &$row,
					'user' => &$this
				));

				//cache entry
				Cache::put("user", "user-" . $this->userID, $row);

				$this->row = $row;
			}
		}

		if ($this->row !== null) {
			$this->userID = (int) $this->row['userID'];
			$this->username = $this->row['username'];
		}

		Events::throwEvent("after_load_user", array(
			'userID' => &$this->userID,
			'username' => &$this->username,
			'isLoggedIn' => &$this->isLoggedIn,
			'row' => &$row,
			'user' => &$this
		));

		//TODO: update online state and IP
	}

	public function loginByUsername (string $username, string $password) : bool {
		//TODO: get salt

		//TODO: search username from database

		//TODO: verify password

		//set online state
		$this->setOnline();
	}

	public function logout () {
		unset($_SESSION['userID']);
		unset($_SESSION['username']);

		$_SESSION['logged-in'] = false;
	}

	protected function hashPassword ($password, $salt) {
		//http://php.net/manual/de/function.password-hash.php

		//add salt to password
		$password .= $salt;

		$options = array(
			'cost' => (int) Settings::get("password_hash_cost", "3")
		);
		$algo = PASSWORD_DEFAULT;

		Events::throwEvent("hashing_password", array(
			'options' => &$options,
			'algo' => &$algo
		));

		return password_hash($password, $algo, $options);
	}

	/**
	 * get user ID of user
	 *
	 * @return integer userID
	 */
	public function getID () : int {
		return $this->userID;
	}

	/**
	 * get username of user
	 *
	 * @return string username
	 */
	public function getUsername () : string {
		return $this->username;
	}

	public function getMail () : string {
		return $this->row['mail'];
	}

	public function isLoggedIn () : bool {
		return $this->isLoggedIn;
	}

	public function getRow () : array {
		return $this->row;
	}

	public function setOnline () {
		//get client ip
		$ip = PHPUtils::getClientIP();

		Database::getInstance()->execute("UPDATE `{praefix}user` SET `online` = '1', `last_online` = CURRENT_TIMESTAMP, `ip` WHERE `userid` = :userid; ", array(
			'userid' => array(
				'type' => PDO::PARAM_INT,
				'value' => (int) $this->userID
			),
			'ip' => $ip
		));
	}

	public function updateOnlineList () {
		$interval_minutes = (int) Settings::get("online_interval", "5");

		Database::getInstance()->execute("UPDATE `{praefix}user` SET `online` = '0' WHERE DATE_SUB(NOW(), INTERVAL " . $interval_minutes . " MINUTE) > `last_online`; ");
	}

	/**
	 * get instance of current (logged in / guest) user
	 */
	public static function &current () : User {
		if (self::$instance == null) {
			self::$instance = new User();
			self::$instance->load();
		}

		return self::$instance;
	}

}

?>
