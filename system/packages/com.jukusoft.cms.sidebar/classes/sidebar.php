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
 * Date: 24.08.2018
 * Time: 13:44
 */

class Sidebar {

	protected $sidebar_id = -1;
	protected $row = array();

	protected static $is_initialized = false;
	protected static $all_sidebars = array();

	public function __construct() {
		//load in-memory cache, if neccessary
		if (!self::isInitialized()) {
			self::initialize();
		}
	}

	public function load (int $sidebar_id) {
		if (isset(self::$all_sidebars[$sidebar_id])) {
			$this->row = self::$all_sidebars[$sidebar_id];
		} else {
			throw new IllegalStateException("sidebar with id '" . $sidebar_id . "' doesnt exists.");
		}

		$this->sidebar_id = $sidebar_id;
	}

	/**
	 * @return int sidebar id
	 */
	public function getSidebarId(): int {
		return $this->sidebar_id;
	}

	public function getUniqueName () : string {
		return $this->row['unique_name'];
	}

	public function getTitle () : string {
		return $this->row['title'];
	}

	public function isDeletable () : bool {
		return $this->row['deletable'] == 1;
	}

	public function getRow () : array {
		return $this->row;
	}

	/**
	 * @return bool
	 */
	protected static function isInitialized(): bool {
		return self::$is_initialized;
	}

	protected static function initialize () {
		if (Cache::contains("sidebars", "all_sidebars")) {
			self::$all_sidebars = Cache::get("sidebars", "all_sidebars");
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}sidebars`; ");

			//clear in-memory cache
			self::$all_sidebars = array();

			foreach ($rows as $row) {
				self::$all_sidebars[$row['sidebar_id']] = $row;
			}

			//cache result
			Cache::put("sidebars", "all_sidebars");
		}

		self::$is_initialized = true;
	}

	public static function create (string $title, string $unique_name, bool $deletable = true) : int {
		if (empty($unique_name)) {
			throw new IllegalArgumentException("unique_name cannot be null.");
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}sidebars` (
			`sidebar_id`, `unique_name`, `title`, `deletable`
		) VALUES (
			NULL, :unique_name, :title, :deletable
		) ON DUPLICATE KEY UPDATE `unique_name` = :unique_name, `title` = :title, `deletable` = :deletable; ", array(
			'unique_name' => $unique_name,
			'title' => $title,
			'deletable' => ($deletable ? 1 : 0)
		));

		//clear cache
		Cache::clear("sidebars");

		return Database::getInstance()->lastInsertId();
	}

	public static function removeById (int $id) {
		Database::getInstance()->execute("DELETE FROM `{praefix}sidebars` WHERE `sidebar_id` = :sidebar_id; ", array(
			'sidebar_id' => $id
		));

		//clear cache
		Cache::clear("sidebars");
	}

	public static function removeByUniqueName (string $unique_name) {
		if (empty($unique_name)) {
			throw new IllegalArgumentException("unique_name cannot be null.");
		}

		Database::getInstance()->execute("DELETE FROM `{praefix}sidebars` WHERE `unique_name` = :unique_name; ", array(
			'unique_name' => $unique_name
		));

		//clear cache
		Cache::clear("sidebars");
	}

}

?>
