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
 * Date: 20.03.2018
 * Time: 14:36
 */

class Groups {

	protected $my_groups = array();

	public function __construct() {
		//
	}

	public function loadMyGroups (int $userID) {
		if (Cache::contains("groups", "own-groups-" . $userID)) {
			$this->my_groups = Cache::get("groups", "own-groups-" . $userID);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}group_members` LEFT JOIN `{praefix}groups` ON `{praefix}group_members`.`groupID` = `{praefix}groups`.`groupID` WHERE `{praefix}group_members`.`userID` = :userID AND `{praefix}group_members`.`activated` = '1'; ", array(
				'userID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $userID
				)
			));

			$this->my_groups = $rows;

			//cache rows
			Cache::put("groups", "own-groups-" . $userID, $this->my_groups);
		}
	}

	public function listGroupIDs () : array {
		$array = array();

		foreach ($this->my_groups as $group_row) {
			$array[] = $group_row['groupID'];
		}

		return $array;
	}

	public function listMyGroups () : array {
		$array = array();

		foreach ($this->my_groups as $row) {
			$group = new Group();
			$group->loadByRow($row);

			$array[] = $group;
		}

		return $array;
	}

	public static function createGroupIfIdAbsent (int $groupID, string $name, string $description, string $color = "#000000", bool $show = true, bool $system_group = false, bool $auto_assign_regist = false) {
		//check, if color is valide
		$validator = new Validator_Color();

		if (!$validator->isValide($color)) {
			throw new IllegalArgumentException("color '" . $color . "' isnt a valide hex color.");
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}groups` (
			`groupID`, `name`, `description`, `color`, `auto_assign_regist`, `system_group`, `show`, `activated`
		) VALUES (
			:groupID, :name, :description, :color, :auto_assign_regist, :system_group, :show, '1'
		) ON DUPLICATE KEY UPDATE `groupID` = :groupID; ", array(
			'groupID' => $groupID,
			'name' => Validator_String::get($name),
			'description' => Validator_String::get($description),
			'color' => $color,
			'auto_assign_regist' => ($auto_assign_regist ? 1 : 0),
			'system_group' => ($system_group ? 1 : 0),
			'show' => ($show ? 1 : 0)
		));

		//clear complete cache for all groups, so membership cache is also cleared
		Cache::clear("groups");
	}

	public static function deleteGroup (int $groupID) {
		$group = new Group();

		try {
			$group->loadById($groupID);
		} catch (IllegalStateException $e) {
			//group doesnt exists, we dont have to do anything
			return;
		}

		$group->delete();
	}

	public static function addGroupToUser (int $groupID, int $userID, bool $group_leader = false) {
		Database::getInstance()->execute("INSERT INTO `{praefix}group_members` (
			`groupID`, `userID`, `group_leader`, `activated`
		) VALUES (
			:groupID, :userID, :group_leader, '1'
		) ON DUPLICATE KEY UPDATE `group_leader` = :group_leader; ", array(
			'groupID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $groupID
			),
			'userID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $userID
			),
			'group_leader' => ($group_leader ? 1 : 0)
		));

		//clear cache
		Cache::clear("groups", "own-groups-" . $userID);
	}

	public static function removeGroupFromUser (int $groupID, int $userID) {
		Database::getInstance()->execute("DELETE FROM `{praefix}group_members` WHERE `groupID` = :groupID AND `userID` = :userID; ", array(
			'groupID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $groupID
			),
			'userID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $userID
			)
		));

		//clear cache
		Cache::clear("groups", "own-groups-" . $userID);
	}

}

?>
