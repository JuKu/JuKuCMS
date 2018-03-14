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
	protected static $menuID_array = array();

	protected $menus = array();

	public function __construct (int $menuID = -1, string $template = "menu") {
		$this->menuID = (int) $menuID;
		$this->template = $template;
	}

	public function loadMenu (int $menuID = -1) {
		if ($menuID == -1) {
			$menuID = $this->menuID;
		}

		if ($menuID == -1) {
			//we dont need to load anything, because no menu is selected
			$this->menus = array();
		}

		Events::throwEvent("before_load_menu", array(
			'menuID' => &$menuID,
			'instance' => &$this
		));

		//load menuID if absent
		self::loadMenuID($menuID);

		if (Cache::contains("menus", "menu_" . $menuID)) {
			$this->menus = Cache::get("menus", "menu_" . $menuID);
		} else {
			$menu_cache = self::$menuID_array[$menuID];

			//get menu by parent -y, this means root menu
			$this->menus = $this->getMenuByParent($menu_cache, -1);

			Events::throwEvent("after_load_menu", array(
				'menuID' => &$menuID,
				'instance' => &$this,
				'menus' => &$this->menus,
				'menu_cache' => $menu_cache
			));

			Cache::put("menus", "menu_" . $menuID, $this->menus);
		}

		$this->menuID = $menuID;
	}

	protected function getMenuByParent (array &$menu_array, int $parentID) : array {
		if (!isset($menu_array[$parentID])) {
			//menu doesnt have submenus
			return array();
		}

		$array = array();

		foreach ($menu_array[$parentID] as $row) {
			$entry = array();

			$href = "";
			$entry['append'] = "";
			$entry['title'] = $row['title'];
			$entry['text'] = $row['title'];
			$entry['icon'] = $row['icon'];

			if ($row['type'] == "page") {
				$href = Domain::getUrl($row['url']);
			} else if ($row['type'] == "link") {
				$href = Domain::getUrl($row['url']);
			} else if ($row['type'] == "external_link") {
				$href = $row['url'];
			} else if ($row['type'] == "js_link") {
				$href = "#";
				$entry['append'] = " onclick=\"" . $row['url'] . "\"";
			}

			$entry['href'] = $href;

			if (!empty($row['icon']) && $row['icon'] != "none") {
				$entry['text'] = "<img src=\"" . $row['icon'] . "\" alt=\"" . $row['title'] . "\" title=\"" . $row['title'] . "\" style=\"max-width:32px; max-height:32px; \" /> " . $row['title'];
			}

			//get submenus
			$entry['submenus'] = $this->getMenuByParent($menu_array, $row['id']);
			$entry['has_submenus'] = sizeof($entry['submenus']) > 0;

			$array[] = $entry;
		}

		return $array;
	}

	/**
	 * get HTML code of menu
	 */
	public function getCode () : string {
		//TODO: add code here
	}

	protected static function loadMenuID (int $menuID) {
		if (isset(self::$menuID_array[$menuID])) {
			return;
		}

		if (Cache::contains("menus", "menuID_" . $menuID)) {
			self::$menuID_array[$menuID] = Cache::get("menus", "menuID_" . $menuID);
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}menu` WHERE `menuID` = :menuID AND `activated` = '1' ORDER BY `order`; ", array('menuID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $menuID
			)));

			$array = array();

			foreach ($rows as $row) {
				$parentID = $row['parent'];

				if (!isset($array[$parentID])) {
					$array[$parentID] = array();
				}

				$array[$parentID][] = $row;
			}

			self::$menuID_array[$menuID] = $array;

			Cache::put("menus", "menuID_" . $menuID, $array);
		}
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
