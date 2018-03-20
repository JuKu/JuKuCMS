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
 * Time: 15:49
 */

class Group {

	protected $groupID = -1;
	protected $row = null;

	public function __construct() {
		//
	}

	public function loadById (int $groupID) {
		if (Cache::contains("groups", "group-" . $groupID)) {
			$this->row = Cache::get("groups", "group-" . $groupID);
		} else {
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}groups` WHERE `groupID` = :groupID AND `acivated` = '1'; ", array(
				'groupID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $groupID
				)
			));

			if (!$row) {
				throw new IllegalStateException("Group with groupID " . $groupID . " doesnt exists.");
			}

			$this->row = $row;
			$this->groupID = $row['groupID'];

			//cache database row
			Cache::put("groups", "group-" . $groupID, $row);
		}
	}

	public function loadByRow (array $row) {
		$this->row = $row;
		$this->groupID = $row['groupID'];
	}

	public function update (string $name, string $description, string $color, bool $auto_assign_regist = false) {
		//throw event
		Events::throwEvent("before_update_group", array(
			'groupID' => $this->groupID,
			'old_row' => $this->row,
			'name' => &$name,
			'description' => &$description,
			'color' => &$color,
			'auto_assign_regist' => &$auto_assign_regist
		));

		Database::getInstance()->execute("UPDATE `{praefix}groups` SET `name` = :name, `description` = :description, `color` = :color, `auto_assign_regist` = :auto_assign_regist WHERE `groupID` = :groupID; ", array(
			'name' => $name,
			'description' => $description,
			'color' => $color,
			'auto_assign_regist' => ($auto_assign_regist ? 1 : 0),
			'groupID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $this->groupID
			)
		));

		//throw event
		Events::throwEvent("after_update_group", array(
			'groupID' => $this->groupID,
			'old_row' => $this->row,
		));

		//update row in-memory
		$this->row['name'] = $name;
		$this->row['description'] = $description;
		$this->row['color'] = $color;
		$this->row['auto_assign_regist'] = ($auto_assign_regist ? 1 : 0);

		//clear cache
		Cache::clear("groups", "group-" . $this->groupID);
	}

	public function putCache () {
		//cache database row
		Cache::put("groups", "group-" . $this->groupID, $this->row);
	}

	public function removeCache () {
		//clear cache data for this group
		Cache::clear("groups", "group-" . $this->groupID);
	}

	/**
	 * get id of group
	 *
	 * @return id of group
	 */
	public function getGroupID () : int {
		return $this->groupID;
	}

	/**
	 * get name of group
	 *
	 * @return name of group
	 */
	public function getName () : string {
		return $this->row['name'];
	}

	/**
	 * get group description
	 *
	 * @return group description
	 */
	public function getDescription () : string {
		return $this->row['description'];
	}

	/**
	 * get color of group (e.q. #FF0000)
	 *
	 * @return color of group in hex
	 */
	public function getColor () : string {
		return $this->row['color'];
	}

	/**
	 * check, if group is a system group, so group cannot be deleted and is required by system
	 *
	 * @return true, if group is a system group
	 */
	public function isSystemGroup () : bool {
		return $this->row['system_group'] === 1;
	}

	/**
	 * check for auto assign flag, this is means a group is automatically assigned to users on registration
	 *
	 * @return true, if group is a auto assign group on registration
	 */
	public function isAutoAssignGroup () : bool {
		return $this->row['auto_assign_regist'] === 1;
	}

	public function getRow () : array {
		return $this->row;
	}

	public function visible () : bool {
		return $this->row['show'] === 1;
	}

	public function hasRank () : bool {
		return $this->row['rank'] !== "none";
	}

	public function getRank () : string {
		return $this->row['rank'];
	}

	public function hasRankImage () : bool {
		return $this->row['rank_image'] !== "none";
	}

	public function getRankImage () : string {
		return $this->row['rank_image'];
	}

	public function isActivated () : bool {
		return $this->row['activated'] === 1;
	}

	public function delete () {
		if ($this->groupID <= 0) {
			throw new IllegalStateException("groupID cannot be <= 0, maybe group wasnt loaded with loadById() or loadByRow()?");
		}

		$delete_group = true;

		//throw event, so plugins can avoid deleting of groups
		Events::throwEvent("before_delete_group", array(
			'groupID' => $this->groupID,
			'row' => $this->row,
			'delete_group' => &$delete_group
		));

		if ($delete_group) {
			//delete group from database
			Database::getInstance()->execute("DELETE * FROM `{praefix}groups` WHERE `groupID` = :groupID; ", array(
				'groupID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $this->groupID
				)
			));

			//delete all members of group
			Database::getInstance()->execute("DELETE * FROM `{praefix}group_members` WHERE `groupID` = :groupID; ", array(
				'groupID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $this->groupID
				)
			));

			//clear cache
			Cache::clear("groups", "group-" . $this->groupID);

			//throw event, so plugins can cleanup
			Events::throwEvent("after_delete_group", array(
				'groupID' => $this->groupID,
				'row' => $this->row
			));
		}
	}

}

?>
