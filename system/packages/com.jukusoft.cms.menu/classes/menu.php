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

	public function loadMenu (int $menuID = -1, Folder $folder) {
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

		if (Cache::contains("menus", "menu_" . $menuID . "_" . User::current()->getID())) {
			$this->menus = Cache::get("menus", "menu_" . $menuID . "_" . User::current()->getID());
		} else {
			$menu_cache = self::$menuID_array[$menuID];

			//get menu by parent -y, this means root menu
			$this->menus = $this->getMenuByParent($menu_cache, -1, $folder);

			Events::throwEvent("after_load_menu", array(
				'menuID' => &$menuID,
				'instance' => &$this,
				'menus' => &$this->menus,
				'menu_cache' => $menu_cache
			));

			Cache::put("menus", "menu_" . $menuID . "_" . User::current()->getID(), $this->menus);
		}

		$this->menuID = $menuID;
	}

	protected function getMenuByParent (array &$menu_array, int $parentID, Folder $folder) : array {
		if (!isset($menu_array[$parentID])) {
			//menu doesnt have submenus
			return array();
		}

		$array = array();

		foreach ($menu_array[$parentID] as $row) {
			//check login_required
			if ($row['login_required'] == 1 && !User::current()->isLoggedIn()) {
				//dont show this menu
				continue;
			}

			//check permissions
			$permissions = explode("|", $row['permissions']);
			$has_permission = false;

			foreach ($permissions as $permission) {
				if (PermissionChecker::current()->hasRight($permission)) {
					$has_permission = true;
					break;
				}
			}

			if (!$has_permission) {
				//dont show this menu, because user doesnt have permission for this menu
				continue;
			}

			$entry = array();

			//translate title
			if ($folder->isTitleTranslationEnabled()) {
				$row['title'] = Translator::translateTitle($row['title']);
			}

			$href = "";
			$entry['append'] = "";
			$entry['title'] = $row['title'];
			$entry['text'] = $row['title'];
			$entry['icon'] = $row['icon'];
			$entry['icon_class'] = " " . $row['icon'];
			$entry['permissions'] = explode("|", $row['permissions']);
			$entry['append'] = "";
			$entry['extension_code'] = "";

			if (strpos($row['url'], "LOGIN_URL") !== FALSE) {
				$row['url'] = Registry::singleton()->getSetting("login_url");
			}

			if (strpos($row['url'], "LOGOUT_URL") !== FALSE) {
				$row['url'] = Registry::singleton()->getSetting("logout_url");
			}

			if ($row['type'] == "page") {
				$href = DomainUtils::generateURL($row['url']);
			} else if ($row['type'] == "link") {
				//TODO: add base url
				$href = $row['url'];
			} else if ($row['type'] == "external_link") {
				$href = $row['url'];
			} else if ($row['type'] == "js_link") {
				$href = "#";
				$entry['append'] = " onclick=\"" . $row['url'] . "\"";
			} else if ($row['type'] == "no_link") {
				$href = "#";
			} else if ($row['type'] == "dynamic_link") {
				$href = "#";

				if (PHPUtils::startsWith($row['url'], "settings:")) {
					$array1 = explode(":", $row['url']);
					$href = Settings::get($array1[1], "#");
				} else if (PHPUtils::startsWith($row['url'], "registry:")) {
					$array1 = explode(":", $row['url']);
					$href = Registry::singleton()->getSetting($array1[1], "#");
				}
			} else {
				throw new IllegalStateException("Unknown menu type: " . $row['type']);
			}

			$entry['href'] = $href;

			if (!empty($row['icon']) && $row['icon'] != "none") {
				$entry['text'] = "<img src=\"" . $row['icon'] . "\" alt=\"" . $row['title'] . "\" title=\"" . $row['title'] . "\" style=\"max-width:32px; max-height:32px; \" /> " . $row['title'];
			}

			//get submenus
			$entry['submenus'] = $this->getMenuByParent($menu_array, $row['id'], $folder);
			$entry['has_submenus'] = sizeof($entry['submenus']) > 0;

			$array[] = $entry;
		}

		return $array;
	}

	/**
	 * get HTML code of menu
	 */
	public function getCode () : string {
		$template = new DwooTemplate($this->template);

		$template->assign("menu_array", $this->menus);

		/*$template = new Template($this->template);

		$this->parseMenu($this->menus, $template);

		//parse main block
		$template->parse("main");*/

		$html = $template->getCode();

		Events::throwEvent("get_menu_code", array(
			'menuID' => $this->menuID,
			'menus' => &$this->menus,
			'html' => &$html
		));

		return $html;
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

		//var_dump(self::$menuID_array);
	}

	public static function createMenuName (string $title, string $unique_name = null) : int {
		Events::throwEvent("before_create_menu_name", array(
			'title' => &$title
		));

		if ($unique_name == null) {
			$unique_name = md5(PHPUtils::randomString(100));
		}

		$unique_name = Validator_String::get($unique_name);

		Database::getInstance()->execute("INSERT INTO `{praefix}menu_names` (
			`menuID`, `title`, `unique_name`, `activated`
		) VALUES (
			NULL, :title, :name, '1'
		) ON DUPLICATE KEY UPDATE `title` = :title, `activated` = '1'; ", array(
			'title' => $title,
			'name' => $unique_name
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
		Database::getInstance()->execute("DELETE FROM `{praefix}menu_names` WHERE `menuID` = :menuID; ", array(
			'menuID' => array(
				'type' => PDO::PARAM_INT,
				'value' => $menuID
			)
		));

		//clear cache
		Cache::clear("menus", "menuID_" . $menuID);
	}

	public static function listMenuNames () : array {
		if (Cache::contains("menus", "menu_names")) {
			return Cache::get("menus", "menu_names");
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}menu_names` WHERE `activated` = 1; ");

			Cache::put("menus", "menu_names", $rows);

			return $rows;
		}
	}

	public static function createMenu (int $id, int $menuID, string $title, string $url, int $parent = -1, string $unique_name = "", $type = "page", $permissions = array("all"), $login_required = false, string $icon = "none", int $order = 100, string $owner = "user") {
		if (!is_array($permissions)) {
			$permissions = array($permissions);
		}

		//validate values
		$id = Validator_Int::get($id);
		$menuID = Validator_Int::get($menuID);
		$title = Validator_String::get($title);
		$parent = Validator_Int::get($parent);
		$type = Validator_String::get($type);
		$login_required = (bool) $login_required;

		$permissions = implode("|", $permissions);

		if (is_null($unique_name) || empty($unique_name)) {
			$unique_name = md5(PHPUtils::randomString(100) . time());
		}

		if ($id != null && $id != -1) {
			$insertID = Database::getInstance()->execute("INSERT INTO `{praefix}menu` (
				`id`, `menuID`, `title`, `url`, `type`, `icon`, `permissions`, `login_required`, `parent`, `unique_name`, `extensions`, `order`, `owner`, `activated`
			) VALUES (
				:id, :menuID, :title, :url, :url_type, :icon, :permissions, :login_required, :parent, :unique_name, :extensions, :menu_order, :owner, '1'
			) ON DUPLICATE KEY UPDATE `menuID` = :menuID, `title` = :title, `url` = :url, `type` = :url_type, `permissions` = :permissions, `login_required` = :login_required, `parent` = :parent, `unique_name` = :unique_name, `extensions` = :extensions, `order` = :menu_order, `owner` = :owner, `icon` = :icon, `activated` = '1'; ", array(
				'id' => $id,
				'menuID' => $menuID,
				'title' => $title,
				'url' => $url,
				'url_type' => $type,
				'icon' => $icon,
				'permissions' => $permissions,
				'login_required' => ($login_required ? 1 : 0),
				'parent' => $parent,
				'unique_name' => $unique_name,
				'extensions' => "none",
				'menu_order' => $order,
				'owner' => $owner
			));
		} else {
			$insertID = Database::getInstance()->execute("INSERT INTO `{praefix}menu` (
				`id`, `menuID`, `title`, `url`, `type`, `icon`, `permissions`, `login_required`, `parent`, `unique_name`, `extensions`, `order`, `owner`, `activated`
			) VALUES (
				NULL, :menuID, :title, :url, :url_type, :icon, :permissions, :login_required, :parent, :unique_name, :extensions, :menu_order, :owner, '1'
			) ON DUPLICATE KEY UPDATE `menuID` = :menuID, `title` = :title, `url` = :url, `type` = :url_type, `permissions` = :permissions, `login_required` = :login_required, `parent` = :parent, `unique_name` = :unique_name, `extensions` = :extensions, `order` = :menu_order, `owner` = :owner, `icon` = :icon, `activated` = '1'; ", array(
				'menuID' => $menuID,
				'title' => $title,
				'url' => $url,
				'url_type' => $type,
				'icon' => $icon,
				'permissions' => $permissions,
				'login_required' => ($login_required ? 1 : 0),
				'parent' => $parent,
				'unique_name' => $unique_name,
				'extensions' => "none",
				'menu_order' => $order,
				'owner' => $owner
			));
		}

		//clear cache
		Cache::clear("menus", "menuID_" . $menuID);

		return $insertID;
	}

	public static function deleteMenusByOwner (string $owner) {
		//delete menus from database
		Database::getInstance()->execute("DELETE FROM `{praefix}menu` WHERE `owner` = :owner; ", array('owner' => $owner));

		//clear cache
		Cache::clear("menus");
	}

}

?>
