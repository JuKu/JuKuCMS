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
 * Date: 07.03.2018
 * Time: 15:46
 */

class Folder {

	protected $folder = "";
	protected $row = array();

	protected static $load_count = 0;

	public function __construct($folder) {
		$this->folder = $folder;
	}

	public function load ($folder) {
		self::$load_count++;

		if (self::$load_count > 50) {
			throw new IllegalStateException("reached max allowed number of Folder::load() calls (to prevent recursion).");
		}

		echo "load folder: " . $folder . "<br />";

		//escape string
		//$folder = Database::getInstance()->escape($folder);

		if (empty($folder)) {
			$folder = "/";
		}

		if (Cache::contains("folder", "folder-" . $folder)) {
			$this->row = Cache::get("folder", "folder-" . $folder);
		} else {
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}folder` WHERE `folder` = :folder; ", array('folder' => $folder));

			if (!$row) {
				//load upper folder
				if (strcmp($folder, "/") !== 0) {
					//load upper dir
					$folder = self::getUpperDir($folder);

					$this->load($folder);
				} else {
					throw new IllegalStateException("no main folder / is defined. Please insert folder '/'.");
				}
			} else {
				$this->row = $row;
			}

			Cache::put("folder", "folder-" . $folder, $this->row);
		}
	}

	public function getFolder () : string {
		return $this->row['folder'];
	}

	public function listRequiredPermissions () : array {
		return explode("|", $this->row['permissions']);
	}

	public function checkPermissions (PermissionChecker $permission_checker) : bool {
		foreach ($this->listRequiredPermissions() as $permission) {
			if ($permission_checker->hasRight($permission)) {
				return true;
			} else if ($permission === "none") {
				return true;
			}
		}

		return false;
	}

	public function isHidden () : bool {
		return $this->row['hidden'] == 1;
	}

	public function hasCustomMainMenu () : bool {
		return $this->row['main_menu'] != -1;
	}

	public function getMainMenu () : int {
		return $this->row['main_menu'];
	}

	public function hasCustomLocalMenu () : bool {
		return $this->row['local_menu'] != -1;
	}

	public function getLocalMenu () : int {
		return $this->row['local_menu'];
	}

	public function isActivated () : bool {
		return $this->row['activated'] == 1;
	}

	public static function getFolderByPage (string $page) : string {
		$array = explode("/", $page);

		if (sizeof($array) <= 2 && empty($array[0])) {
			return "/";
		}

		//remove last element
		array_pop($array);

		return implode("/", $array) . "/";
	}

	public static function getUpperDir ($dir) {
		if (PHPUtils::endsWith($dir, "/")) {
			//remove last slash
			$dir = substr($dir, 0, -1);
		}

		$array = explode("/", $dir);

		if (sizeof($array) <= 2 && empty($array[0])) {
			return "/";
		}

		//remove last element
		array_pop($array);

		return implode("/", $array) . "/";
	}

	public static function createFolder (string $folder, bool $hidden = false, array $permissions = array(), int $main_menu = -1, int $local_menu = -1, string $force_template = "none") {
		//escape string
		//$folder = Database::getInstance()->escape($folder);

		$permissions_str = implode("|", $permissions);

		if (sizeof($permissions) == 0) {
			$permissions_str = "none";
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}folder` (
			`folder`, `force_template`, `main_menu`, `local_menu`, `permissions`, `hidden`, `activated`
		) VALUES (
			:folder, :templatename, :main_menu, :local_menu, :permissions, :hidden, '1'
		); ", array(
			'folder' => $folder,
			'templatename' => $force_template,
			'main_menu' => $main_menu,
			'local_menu' => $local_menu,
			'permissions' => $permissions_str,
			'hidden' => $hidden ? 1 : 0
		));

		//clear cache
		Cache::clear("folder");
	}

	public static function createFolderIfAbsent (string $folder, bool $hidden = false, array $permissions = array(), int $main_menu = -1, int $local_menu = -1, string $force_template = "none") {
		//escape string
		//$folder = Database::getInstance()->escape($folder);

		$permissions_str = implode("|", $permissions);

		if (sizeof($permissions) == 0) {
			$permissions_str = "none";
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}folder` (
			`folder`, `force_template`, `main_menu`, `local_menu`, `permissions`, `hidden`, `activated`
		) VALUES (
			:folder, :templatename, :main_menu, :local_menu, :permissions, :hidden, '1'
		) ON DUPLICATE KEY UPDATE `hidden` = :hidden, `permissions` = :permissions, `force_template` = :templatename; ", array(
			'folder' => $folder,
			'templatename' => $force_template,
			'main_menu' => $main_menu,
			'local_menu' => $local_menu,
			'permissions' => $permissions_str,
			'hidden' => $hidden ? 1 : 0
		));

		//clear cache
		Cache::clear("folder");
	}

	public static function deleteFolder ($folder) {
		//escape string
		//$folder = Database::getInstance()->escape($folder);

		Database::getInstance()->execute("DELETE FROM `{praefix}folder` WHERE `folder` = :folder; ", array('folder' => $folder));
	}

}

?>
