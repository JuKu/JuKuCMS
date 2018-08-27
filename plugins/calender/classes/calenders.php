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
 * Date: 26.04.2018
 * Time: 21:26
 */

namespace Plugin\Calender;

use Cache;
use Database;
use Groups;
use User;
use PDO;

class Calenders {

	public static function listMyCalenderIDs (int $userUD) : array {
		if (Cache::contains("plugin-calender", "calenderIDs-" . $userUD)) {
			return Cache::get("plugin-calender", "calenderIDs-" . $userUD);
		} else {
			$groups = new Groups();
			$groups->loadMyGroups(User::current()->getID());
			$groupIDs = $groups->listGroupIDs();

			//get calenders by groups
			$calender_ids = self::listCalenderRowsByGroups($groupIDs);

			//merge arrays
			foreach (self::listCalenderRowsByUser(User::current()->getID()) as $calenderID=>$row) {
				if (isset($calender_ids[$calender_ids])) {
					//check, which is higher permission
					if (self::valueToInt($calender_ids[$calenderID]['value']) < self::valueToInt($row['value'])) {
						$calender_ids[$calenderID] = $row;
					}
				} else {
					$calender_ids[$calenderID] = $row;
				}
			}

			//put results to cache
			Cache::put("plugin-calender", "calenderIDs-" . $userUD, $calender_ids);

			return $calender_ids;
		}
	}

	public static function listMyCalenders (int $userID) : array {
		if (Cache::contains("plugin-calender", "my-calenders-" . $userID)) {
			return Cache::get("plugin-calender", "my-calenders-" . $userID);
		} else {
			$array = array();

			$array1 = array();
			$user_rows = array();

			foreach (self::listMyCalenderIDs($userID) as $calenderID=>$row) {
				$array1[] = "`id` = '" . intval($calenderID) . "'";
				$user_rows[$calenderID] = $row;
			}

			$array_str = (!empty($array1) ? " OR " : "") . implode(" OR ", $array1);

			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}plugin_calender_calenders` WHERE `id` = '-1'" . $array_str . "; ");

			foreach ($rows as $row) {
				$calenderID = $row['id'];

				//create new calender
				$calender = new Calender($row, $user_rows[$calenderID]);

				$array[] = $calender;
			}

			//put array to cache
			Cache::put("plugin-calender", "my-calenders-" . $userID, $array);

			return $array;
		}
	}

	/**
	 * list calender ids in this form calenderID=>value
	 *
	 * Dont use this method directly, because its not cached!
	 *
	 * @param $groupIDs array with ids of groups, user belongs to
	 *
	 * @return array with calender IDs in form calenderID=>value
	 */
	protected static function listCalenderRowsByGroups (array $groupIDs) : array {
		$array = array();

		foreach ($groupIDs as $id) {
			$array[] = "`groupID` = '" . intval($id) . "'";
		}

		$array_str = (!empty($array) ? " OR " : "") . implode(" OR ", $array);

		$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}plugin_calender_group_rights` WHERE `groupID` = '-1'" . $array_str . "; ");

		$res_array = array();

		foreach ($rows as $row) {
			$calenderID = $row['calenderID'];
			$res_array[$calenderID] = $row;
		}

		return $res_array;
	}

	protected static function listCalenderRowsByUser (int $userID) : array {
		if (Cache::contains("plugin-calender", "calender-ids-by-user-" . $userID)) {
			return Cache::get("plugin-calender", "calender-ids-by-user-" . $userID);
		} else {
			$array = array();

			//get calenders from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}plugin_calender_user_rights` WHERE `userID` = :userID; ", array(
				'userID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $userID
				)
			));

			foreach ($rows as $row) {
				$calenderID = $row['calenderID'];

				$array[$calenderID] = $row;
			}

			//cache results
			Cache::put("plugin-calender", "calender-ids-by-user-" . $userID, $array);

			return $array;
		}
	}

	public static function valueToInt (string $value) : int {
		switch ($value) {
			case "read":
				return 1;
			case "write":
				return 2;
			case "owner":
				return 3;
			default:
				throw new \IllegalArgumentException("Unknown calender value '" . $value . "'!");
		}
	}

}

?>
