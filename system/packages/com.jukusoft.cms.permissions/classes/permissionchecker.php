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
 * Time: 00:17
 */

class PermissionChecker {

	protected $userID = -1;

	//list with all permissions - values: 0 (no), 1 (yes), 2 (never)
	protected $permissions = array();

	//singleton instance
	protected static $instance = null;

	public function __construct(int $userID) {
		$this->userID = $userID;

		if (Cache::contains("permissions", "permissions_user_" . $this->userID)) {
			$this->permissions = Cache::get("permissions", "permissions_user_" . $this->userID);
		} else {
			//get all groups, where user is member on
			$groups = new Groups();
			$groups->loadMyGroups($userID);
			$my_group_ids = $groups->listGroupIDs();

			//iterate through all groups user is member on
			foreach ($my_group_ids as $groupID) {
				$group_rights = new GroupRights($groupID);

				//affect group rights
				foreach ($group_rights->listRights() as $token=>$value) {
					$this->mergeRow($token, $value);
				}
			}

			//get user rights
			$user_rights = new UserRights($userID);

			foreach ($user_rights->listRights() as $token=>$value) {
				$this->mergeRow($token, $value);
			}

			//cache result
			Cache::put("permissions", "permissions_user_" . $this->userID, $this->permissions);
		}
	}

	protected function mergeRow (string $token, int $value) {
		if ($value < 0 || $value > 2) {
			throw new IllegalArgumentException("token ('" . $token . "') value '" . $value . "' is not allowed, value has to be >= 0 and <= 2.");
		}

		if (!isset($this->permissions[$token])) {
			$this->permissions[$token] = $value;
		} else {
			$current_value = $this->permissions[$token];

			if ($value > $current_value) {
				$this->permissions[$token] = $value;
			}
		}
	}

	public function hasRight (string $token) {
		//check, if user is super admin
		if (/*$this->userID == 1 || */(isset($this->permissions["super_admin"]) && $this->permissions["super_admin"] == 1) && $token !== "not_logged_in") {
			//super admin has all rights
			return true;
		}

		if ($token === "none" || $token === "all") {
			return true;
		}

		return isset($this->permissions[$token]) && $this->permissions[$token] == 1;
	}

	public static function &current () {
		if (self::$instance == null) {
			self::$instance = new PermissionChecker(User::current()->getID());
		}

		return self::$instance;
	}

}

?>
