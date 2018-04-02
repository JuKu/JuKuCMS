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
 * Date: 02.04.2018
 * Time: 14:30
 */

class PageRights {

	protected $pageID = 0;
	protected $page = null;
	protected $group_rows = null;
	protected $user_rows = null;

	public function __construct(Page $page) {
		$this->pageID = $page->getPageID();
		$this->page = $page;
	}

	public function load () {
		if (Cache::contains("page_rights", "page_" . $this->pageID)) {
			$this->group_rows = Cache::get("page_rights", "page_" . $this->pageID);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}page_rights` WHERE `pageID` = :pageID; ", array(
				'pageID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $this->pageID
				)
			));

			$array = array();

			foreach ($rows as $row) {
				if (!isset($array[$row['groupID']])) {
					$array[$row['groupID']] = array();
				}

				$array[$row['groupID']][$row['token']] = $row['value'];
			}

			//cache results
			Cache::put("page_rights", "page_" . $this->pageID, $array);

			$this->group_rows = $array;
		}

		if (Cache::contains("page_rights", "page_user_" . $this->pageID)) {
			$this->user_rows = Cache::get("page_rights", "page_user_" . $this->pageID);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}page_user_rights` WHERE `pageID` = :pageID; ", array(
				'pageID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $this->pageID
				)
			));

			$array = array();

			foreach ($rows as $row) {
				if (!isset($array[$row['userID']])) {
					$array[$row['userID']] = array();
				}

				$array[$row['userID']][$row['token']] = $row['value'];
			}

			//cache results
			Cache::put("page_rights", "page_user_" . $this->pageID, $array);

			$this->user_rows = $array;
		}
	}

	/**
	 * check, if user has right for this page
	 */
	public function checkRights (int $userID, array $groupIDs, string $token) : bool {
		$value = 0;

		//per default published pages are visible, if not specified
		if ($token == "see") {
			$value = -1;
		}

		//iterate through user groups
		foreach ($groupIDs as $groupID) {
			//check, if permissions exists for groupID
			if (!isset($this->group_rows[$groupID])) {
				//no rights specified for this group
				continue;
			}

			if (!isset($this->group_rows[$groupID][$token])) {
				continue;
			}

			$row_value = $this->group_rows[$groupID][$token];

			if ($row_value > $value) {
				$value = $row_value;
			}
		}

		if (isset($this->user_rows[$userID]) && isset($this->user_rows[$userID][$token])) {
			$row_value = $this->user_rows[$userID][$token];

			if ($row_value > $value) {
				$value = $row_value;
			}
		}

		return $value == 1 || $value == -1;
	}

	protected function mergeRow (array $permissions, string $token, int $value) : array {
		if ($value < 0 || $value > 2) {
			throw new IllegalArgumentException("token ('" . $token . "') value '" . $value . "' is not allowed, value has to be >= 0 and <= 2.");
		}

		if (!isset($permissions[$token])) {
			$permissions[$token] = $value;
		} else {
			$current_value = $permissions[$token];

			if ($value > $current_value) {
				$permissions[$token] = $value;
			}
		}

		return $permissions;
	}

	public static function setDefaultAllowedGroups (int $pageID, array $groupIDs) {
		if (sizeof($groupIDs) == 0) {
			throw new IllegalArgumentException("no groupIDs was set.");
		}

		$lines = array();

		foreach ($groupIDs as $groupID) {
			//validate groupID
			$groupID = Validator_Int::get($groupID);

			$lines[] = "('" . $groupID . "', '" . $pageID . "', 'see', '1')";
		}

		$line_str = implode(",\n", $lines);

		Database::getInstance()->execute("INSERT INTO `{praefix}page_rights` (
			`groupID`, `pageID`, `token`, `value`
		) VALUES 
			" . $line_str . "
		ON DUPLICATE KEY UPDATE `value` = '1'; ");

		//clear cache
		Cache::clear("page_rights", "page_" . $pageID);
	}

	public static function setDefaultAllowedGroupsForAlias (string $alias, array $groupIDs) {
		$pageID = Page::getPageIDByAlias($alias);

		self::setDefaultAllowedGroups($pageID, $groupIDs);
	}

}

?>
