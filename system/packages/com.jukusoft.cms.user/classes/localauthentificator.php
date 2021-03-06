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
 * Date: 12.05.2018
 * Time: 13:33
 */

class LocalAuthentificator implements IAuthentificator {

	public function __construct() {
		//
	}

	/**
	 * check password of user and import user, if neccessary
	 *
	 * @param $username string name of user
	 * @param $password string password of user
	 *
	 * @return userID or -1, if credentials are wrong
	 */
	public function checkPasswordAndImport(string $username, string $password) : int {
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}user` WHERE `username` = :username AND `activated` = '1'; ", array(
			'username' => &$username
		));

		if (!$row) {
			//user doesnt exists
			return -1;
		}

		//get salt
		$salt = $row['salt'];

		//add salt to password
		$password .= $salt;

		//verify password
		if (password_verify($password, $row['password'])) {
			//correct password

			//check, if a newer password algorithmus is available --> rehash required
			if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
				//rehash password
				$new_hash = self::hashPassword($password, $salt);

				//update password in database
				Database::getInstance()->execute("UPDATE `{praefix}user` SET `password` = :password WHERE `userID` = :userID; ", array(
					'password' => $new_hash,
					'userID' => array(
						'type' => PDO::PARAM_INT,
						'value' => $row['userID']
					)
				));
			}

			return $row['userID'];
		} else {
			return -1;
		}
	}

}

?>
