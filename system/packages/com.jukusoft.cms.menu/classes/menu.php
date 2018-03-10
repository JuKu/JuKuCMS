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
 * Date: 10.03.2018
 * Time: 20:52
 */

class Menu {

	//menu ID (table menu_names)
	protected $menuID = -1;

	//name of template
	protected $template = "";

	//menu structure
	protected $menus = array();

	public function __construct (int $menuID = -1, string $template = "menu") {
		$this->menuID = (int) $menuID;
		$this->template = $template;
	}

	public function loadMenu ($menuID = -1) {
		if ($menuID == -1) {
			$menuID = $this->menuID;
		}

		if (Cache::contains("menus", "menuID_" . $menuID)) {
			$this->menus = Cache::get("menus", "menuID_" . $menuID);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}menus` WHERE `menuID` = :menuID AND `activated` = '1' ORDER BY `position`; ", array('menuID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $menuID
			)));

			$menuID_array = array();

			foreach ($rows as $row) {
				$parentID = $row['parent'];

				if (!isset($menuID_array[$parentID])) {
					$menuID_array[$parentID] = array();
				}

				$menuID_array[$parentID][] = $row;
			}

			$this->menus = $menuID_array;

			Cache::put("menus", "menuID_" . $menuID, $menuID_array);
		}

		$this->menuID = $menuID;
	}

	public static function createMenuName ($title) : int {
		Events::throwEvent("before_create_menu_name", array(
			'title' => &$title
		));

		Database::getInstance()->execute("INSERT INTO `{praefix}menu_names` (
			`menuID`, `title`
		) VALUES (
			NULL, :title
		); ", array(
			'title' => $title
		));

		Cache::clear("menus");

		//get menuID of inserted menu name
		$menuID = Database::getInstance()->lastInsertId();

		Events::throwEvent("after_created_menu_name", array(
			'menuID' => $menuID,
			'title' => &$title
		));

		return $menuID;
	}

	public static function deleteMenuName (int $menuID) {
		throw new Exception("method deleteMenuName() isnt implemented yet.");

		//TODO: add code here
	}

}

?>
