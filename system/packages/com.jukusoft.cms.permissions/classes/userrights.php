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
 * Date: 24.03.2018
 * Time: 00:11
 */

class UserRights {

	protected $userID = -1;

	//array with permissions - value: 0 (no), 1 (yes), 2 (never)
	protected $permissions = array();

	public function __construct(int $userID) {
		$this->userID = $userID;

		//load permissions
		$this->load($userID);
	}

	protected function load (int $userID) {
		if (Cache::contains("user_permissions", "user_" . $userID)) {
			$this->permissions = Cache::get("user_permissions", "user_" . $userID);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}user_rights` WHERE `userID` = :userID; ", array(
				'userID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $userID
				)
			));

			$this->permissions = array();

			foreach ($rows as $row) {
				$this->permissions[$row['token']] = $row['value'];
			}

			//cache result
			Cache::put("user_permissions", "user_user_" . $userID, $this->permissions);
		}
	}

	public function setRight (string $token, int $value = 1) {
		//validate token
		$token = Validator_Token::get($token);

		if ($value < 0 || $value > 2) {
			throw new IllegalArgumentException("token value '" . $value . "' is not allowed, value has to be >= 0 and <= 2.");
		}

		//update database
		Database::getInstance()->execute("INSERT INTO `{praefix}user_rights` (
			`userID`, `token`, `value`
		) VALUES (
			:userID, :token, :value
		) ON DUPLICATE KEY UPDATE `value` = :value; ", array(
			'userID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $this->userID
			),
			'token' => $token,
			'value' => array(
				'type' => PDO::PARAM_INT,
				'value' => $value
			)
		));

		$this->permissions[$token] = $value;

		//clear cache
		Cache::clear("user_permissions", "user_" . $this->userID);
		Cache::clear("permissions", "permissions_user_" . $this->userID);
	}

	public function removeRight (string $token) {
		//validate token
		$token = Validator_Token::get($token);

		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}user_rights` WHERE `userID` = :userID AND `token` = :token; ", array(
			'userID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $this->userID
			),
			'token' => $token
		));

		//delete from in-memory cache
		if (isset($this->permissions[$token])) {
			unset($this->permissions[$token]);
		}

		//clear cache
		Cache::clear("user_permissions", "user_" . $this->userID);
		Cache::clear("permissions", "permissions_user_" . $this->userID);
	}

	public function listRights () : array {
		return $this->permissions;
	}

	public function hasRight (string $token) {
		return isset($this->permissions[$token]) && $this->permissions[$token] == 1;
	}

}

?>
