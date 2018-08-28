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
 * Date: 28.08.2018
 * Time: 18:52
 */

class SettingsCategory {

	protected $row = array();

	public function __construct() {
		//
	}

	public function load ($row) {
		$this->row = $row;
	}

	public function getCategory () : string {
		return $this->row['category'];
	}

	public function getTitle () : string {
		return $this->row['title'];
	}

	public function getOwner () : string {
		return $this->row['owner'];
	}

	public function getOrder () : int {
		return $this->row['order'];
	}

	public static function createIfAbsent (string $category, string $title, int $order = 10, string $owner = "system") {
		Database::getInstance()->execute("INSERT INTO `{praefix}global_settings_category` (
			`category`, `title`, `owner`, `order`
		) VALUES (
			  :category, :title, :owner, :order
		) ON DUPLICATE KEY UPDATE `title` = :title, `owner` = :owner, `order` = :order; ", array(
			'category' => $category,
			'title' => $title,
			'owner' => $owner,
			'order' => $order
		));

		//clear cache
		Cache::clear("setting_categories");
	}

	public static function remove (string $category) {
		Database::getInstance()->execute("DELETE FROM `{praefix}global_settings_category` WHERE `category` = :category; ", array(
			'category' => $category
		));

		//clear cache
		Cache::clear("setting_categories");
	}

	public static function removeByOwner (string $owner) {
		Database::getInstance()->execute("DELETE FROM `{praefix}global_settings_category` WHERE `owner` = :owner; ", array(
			'owner' => $owner
		));

		//clear cache
		Cache::clear("setting_categories");
	}

	public static function listAllCategories () : array {
		$rows = array();
		$list = array();

		if (Cache::contains("setting_categories", "list")) {
			$rows = Cache::get("setting_categories", "list");
		} else {
			//get categories from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}global_settings_category`");

			//store values in cache
			Cache::put("setting_categories", "list", $rows);
		}

		foreach ($rows as $row) {
			//create new instance of category and load row
			$category = new SettingsCategory();
			$category->load($row);

			//add category to list
			$list[] = $category;
		}

		return $list;
	}

	public static function cast (SettingsCategory $category) : SettingsCategory {
		return $category;
	}

}

?>
