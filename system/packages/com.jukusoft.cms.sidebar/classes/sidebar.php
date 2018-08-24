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

	public function __construct() {
		//
	}

	public function load (int $sidebar_id) {
		if (Cache::contains("sidebars", "sidebar_" . $sidebar_id)) {
			$this->row = Cache::get("sidebars", "sidebar_" . $sidebar_id);
		} else {
			//get sidebar from database
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}sidebars` WHERE `sidebar_id` = :sidebar_id; ", array(
				'sidebar_id' => $sidebar_id
			));

			if (!$row) {
				throw new IllegalStateException("sidebar with sidebar_id '" . $sidebar_id . "' doesnt exists.");
			}

			$this->row = $row;

			//cache row
			Cache::put("sidebars", "sidebar_" . $sidebar_id, $this->row);
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
