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

	protected static $default_authentificator = null;

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
				$this->setGuest();
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
			$row = false;

			//check, if guest user, because guest user doesnt exists in database
			if ($this->userID !== -1) {
				$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID AND `activated` = '1'; ", array(
					'userID' => array(
						'type' => PDO::PARAM_INT,
						'value' => $this->userID
					)
				));
			}

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
		if ($userID === -1 && $this->isLoggedIn()) {
			$this->setOnline();
		}
	}

	public function loginByUsername (string $username, string $password) : array {
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `username` = :username AND `activated` = '1'; ", array(
			'username' => &$username
		));

		if (!$row) {
			//get default authentificator
			$authentificator = self::getDefaultAuthentificator();

			$userID = $authentificator->checkPasswordAndImport($username, $password);

			if ($userID == -1) {
				//user not found
			} else {
				//user was imported now, get user row
				$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID AND `activated` = '1'; ", array(
					'userID' => &$userID
				));
			}
		}

		return $this->loginRow($row, $password);
	}

	public function loginByMail (string $mail, string $password) : array {
		//check, if mail is valide
		$validator = new Validator_Mail();

		if (!$validator->isValide($mail)) {
			return array(
				'success' => false,
				'error' => "mail_not_valide"
			);
		}

		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `mail` = :mail AND `activated` = '1'; ", array(
			'mail' => &$mail
		));

		return $this->loginRow($row, $password);
	}

	public function loginByID (int $userID) : array {
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID AND `activated` = '1'; ", array(
			'userID' => &$userID
		));

		$res = array();

		if ($row !== false) {
			//set online state
			$this->setOnline();

			//set logged in
			$this->setLoggedIn($row['userID'], $row['username'], $row);

			//login successful
			$res['success'] = true;
			$res['error'] = "none";
			return $res;
		} else {
			//user doesnt exists
			$res['success'] = false;
			$res['error'] = "user_not_exists";
			return $res;
		}
	}

	/**
	 * check password of current user
	 *
	 * @param $password string password
	 *
	 * @throws IllegalStateException if user wasnt loaded before
	 *
	 * @return true, if password is correct
	 */
	public function checkPassword (string $password) : bool {
		if ($this->row == null || empty($this->row)) {
			throw new IllegalStateException("user wasnt loaded.");
		}

		//because password is not cached, we have to load it directly from database
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID AND `activated` = '1'; ", array(
			'userID' => $this->getID()
		));

		//get salt
		$salt = $row['salt'];

		//add salt to password
		$password .= $salt;

		return password_verify($password, $row['password']);
	}

	public function setPassword (string $password) {
		if ($this->row == null || empty($this->row)) {
			throw new IllegalStateException("user wasnt loaded.");
		}

		//validate password
		$password = Validator_Password::get($password);

		//create new salt
		$salt = md5(PHPUtils::randomString(50));

		//generate password hash
		$hashed_password = self::hashPassword($password, $salt);

		//update database
		Database::getInstance()->execute("UPDATE `{praefix}user` SET `password` = :password, `salt` = :salt WHERE `userID` = :userID; ", array(
			'password' => $hashed_password,
			'salt' => $salt,
			'userID' => $this->getID()
		));

		//clear cache
		Cache::clear("user", "user-" . $this->getID());
	}

	protected function loginRow ($row, string $password) : array {
		$res = array();

		if (!$row) {
			//user doesnt exists
			$res['success'] = false;
			$res['error'] = "user_not_exists";

			return $res;
		}

		//get authentificator
		$authentificator = self::getAuthentificatorByID($row['userID']);

		//check password
		if ($authentificator->checkPasswordAndImport($row['username'], $password) !== -1) {
			//password is correct

			//set online state
			$this->setOnline();

			//set logged in
			$this->setLoggedIn($row['userID'], $row['username'], $row);

			//login successful
			$res['success'] = true;
			$res['error'] = "none";
			return $res;
		} else {
			//wrong password

			//user doesnt exists
			$res['success'] = false;
			$res['error'] = "wrong_password";

			return $res;
		}
	}

	protected function setLoggedIn (int $userID, string $username, array $row) {
		$_SESSION['logged-in'] = true;
		$_SESSION['userID'] = (int) $userID;
		$_SESSION['username'] = $username;

		//remove password hash from row (so password isnt cached)
		unset($row['password']);

		$this->userID = $userID;
		$this->username = $username;
		$this->row = $row;
	}

	public function logout () {
		//check, if session was started
		PHPUtils::checkSessionStarted();

		unset($_SESSION['userID']);
		unset($_SESSION['username']);

		$_SESSION['logged-in'] = false;

		$this->setGuest();
	}

	protected function setGuest () {
		$this->userID = (int) Settings::get("guest_userid", "-1");
		$this->username = Settings::get("guest_username", "Guest");
		$this->isLoggedIn = false;
	}

	protected static function hashPassword ($password, $salt) {
		//http://php.net/manual/de/function.password-hash.php

		//add salt to password
		$password .= $salt;

		$options = array(
			'cost' => (int) Settings::get("password_hash_cost", "10")
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

	public function setOnline (bool $updateIP = true) {
		//get client ip
		$ip = PHPUtils::getClientIP();

		if ($updateIP) {
			Database::getInstance()->execute("UPDATE `{praefix}user` SET `online` = '1', `last_online` = CURRENT_TIMESTAMP, `ip` = :ip WHERE `userid` = :userid; ", array(
				'userid' => array(
					'type' => PDO::PARAM_INT,
					'value' => (int) $this->userID
				),
				'ip' => $ip
			));
		} else {
			Database::getInstance()->execute("UPDATE `{praefix}user` SET `online` = '1', `last_online` = CURRENT_TIMESTAMP, WHERE `userid` = :userid; ", array(
				'userid' => array(
					'type' => PDO::PARAM_INT,
					'value' => (int) $this->userID
				)
			));
		}
	}

	public function updateOnlineList () {
		$interval_minutes = (int) Settings::get("online_interval", "5");

		Database::getInstance()->execute("UPDATE `{praefix}user` SET `online` = '0' WHERE DATE_SUB(NOW(), INTERVAL " . $interval_minutes . " MINUTE) > `last_online`; ");
	}

	/**
	 * creates user if userID is absent
	 *
	 * Only use this method for installation & upgrade!
	 */
	public static function createIfIdAbsent (int $userID, string $username, string $password, string $mail, int $main_group = 2, string $specific_title = "none", int $activated = 1) {
		if (self::existsUserID($userID)) {
			//dont create user, if user already exists
			return;
		}

		//create salt
		$salt = md5(PHPUtils::randomString(50));

		//generate password hash
		$hashed_password = self::hashPassword($password, $salt);

		Database::getInstance()->execute("INSERT INTO `{praefix}user` (
			`userID`, `username`, `password`, `salt`, `mail`, `ip`, `main_group`, `specific_title`, `online`, `last_online`, `registered`, `activated`
		) VALUES (
			:userID, :username, :password, :salt, :mail, '0.0.0.0', :main_group, :title, '0', '0000-00-00 00:00:00', CURRENT_TIMESTAMP , :activated
		)", array(
			'userID' => $userID,
			'username' => $username,
			'password' => $hashed_password,
			'salt' => $salt,
			'mail' => $mail,
			'main_group' => $main_group,
			'title' => $specific_title,
			'activated' => $activated
		));
	}

	public static function create (string $username, string $password, string $mail, string $ip, int $main_group = 2, string $specific_title = "none", int $activated = 1) {
		if (self::existsUsername($username)) {
			//dont create user, if username already exists
			return false;
		}

		if (self::existsMail($mail)) {
			//dont create user, if mail already exists
			return false;
		}

		if (empty($specific_title)) {
			$specific_title = "none";
		}

		//create salt
		$salt = md5(PHPUtils::randomString(50));

		//generate password hash
		$hashed_password = self::hashPassword($password, $salt);

		//create user in database
		Database::getInstance()->execute("INSERT INTO `{praefix}user` (
			`userID`, `username`, `password`, `salt`, `mail`, `ip`, `main_group`, `specific_title`, `online`, `last_online`, `registered`, `activated`
		) VALUES (
			NULL, :username, :password, :salt, :mail, :ip, :main_group, :title, '0', '0000-00-00 00:00:00', CURRENT_TIMESTAMP , :activated
		)", array(
			'username' => $username,
			'password' => $hashed_password,
			'salt' => $salt,
			'mail' => $mail,
			'ip' => $ip,
			'main_group' => $main_group,
			'title' => $specific_title,
			'activated' => $activated
		));

		//get userID
		$userID = self::getIDByUsernameFromDB($username);

		if ($userID == Settings::get("guest_userid", -1)) {
			//something went wrong
			return false;
		}

		//add user to group "registered users"
		Groups::addGroupToUser(2, $userID, false);

		Events::throwEvent("add_user", array(
			'userID' => $userID,
			'username' => &$username,
			'mail' => $mail,
			'main_group' => $main_group
		));

		return array(
			'success' => true,
			'userID' => $userID,
			'username' => $username,
			'mail' => $mail
		);
	}

	public static function deleteUserID (int $userID) {
		Database::getInstance()->execute("DELETE FROM `{praefix}user` WHERE `userID` = :userID; ", array(
			'userID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $userID
			)
		));

		//remove user from cache
		Cache::clear("user", "user-" . $userID);
	}

	public static function existsUserID (int $userID) : bool {
		//search for userID in database
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID; ", array(
			'userID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $userID
			)
		));

		return $row !== false;
	}

	public static function existsUsername (string $username) : bool {
		//search for username in database, ignore case
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE UPPER(`username`) LIKE UPPER(:username); ", array('username' => $username));

		return $row !== false;
	}

	public static function existsMail (string $mail) : bool {
		//search for mail in database, ignore case
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE UPPER(`mail`) LIKE UPPER(:mail); ", array('mail' => $mail));

		return $row !== false;
	}

	public static function getIDByUsernameFromDB (string $username) : int {
		//search for username in database, ignore case
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE UPPER(`username`) LIKE UPPER(:username); ", array('username' => $username));

		if ($row === false) {
			//return guest userID
			return Settings::get("guest_userid", -1);
		}

		return $row['userID'];
	}

	public static function &getAuthentificatorByID (int $userID = -1) {
		if ($userID == -1) {
			//get default authentificator
			return self::getDefaultAuthentificator();
		} else {
			//get authentificator class

			//check, if user exists
			if (!self::existsUserID($userID)) {
				throw new IllegalStateException("user with userID '" . $userID . "' doesnt exists.");
			}

			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `userID` = :userID AND `activated` = '1'; ", array(
				'userID' => &$userID
			));

			$class_name = $row['authentificator'];
			return new $class_name();
		}
	}

	public static function &getAuthentificatorByUsername (string $username = "") {
		if ($username == null || empty($username)) {
			//get default authentificator
			return self::getDefaultAuthentificator();
		} else {
			//get authentificator class

			//check, if user exists
			if (!self::existsUsername($username)) {
				throw new IllegalStateException("user with username '" . $username . "' doesnt exists.");
			}

			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `username` = :username AND `activated` = '1'; ", array(
				'username' => &$username
			));

			$class_name = $row['authentificator'];
			return new $class_name();
		}
	}

	public static function &getDefaultAuthentificator () : IAuthentificator {
		if (self::$default_authentificator == null) {
			$class_name = Settings::get("default_authentificator", "LocalAuthentificator");
			$obj = new $class_name();

			self::$default_authentificator = $obj;
		}

		return self::$default_authentificator;
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
