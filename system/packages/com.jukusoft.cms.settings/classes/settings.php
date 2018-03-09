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
 * this class is responsible for global settings
 *
 * @copyright (c) 2018, jukusoft.com (Justin Kuenzel), Pascal Reintjens
 * @license Apache 2.0
 * @since 1.0.0
 */
class Settings {

	//in-memory cache of settings
	protected static $settings = array();

	//flag, if global settings was initialized
	protected static $initialized = false;

	/**
	 * get value of setting
	 *
	 * @package com.jukusoft.cms.settings
	 *
	 * @param key setting
	 *
	 * @return mixed or null, if key doesnt exists in database / default value, if set
	 */
	public static function get (string $key, $default_value = null) {
		//load settings if neccessary
		self::loadSettingsIfAbsent();

		if (!isset(self::$settings[$key])) {
			if ($default_value != null) {
				return $default_value;
			} else {
				print_r(self::$settings);

				throw new IllegalStateException("Settings key '" . $key . "' doesnt exists.");

				//return null;
			}
		} else {
			return unserialize(self::$settings[$key]);
		}
	}

	/**
	 * set setting
	 *
	 * @param $key setting
	 * @param $value mixed value to set
	 */
	public static function set (string $key, $value) {
		//escape key
		$key = Database::getInstance()->escape($key);

		//serialize data
		$value = serialize($value);

		//update database
		Database::getInstance()->execute("UPDATE `{praefix}global_settings` SET `value` = :value WHERE `key` = :key;", array(
			'value' => $value,
			'key' => $key
		));

		//update local in-memory cache
		self::$settings[$key] = $value;

		//clear cache (area "global_settings")
		Cache::clear("global_settings");
	}

	/**
	 * set setting if key is absent
	 *
	 * @param $key setting
	 * @param $value mixed value to set
	 */
	public static function setIfAbsent (string $key, $value) {
		self::loadSettingsIfAbsent();

		if (!isset(self::$settings[$key])) {
			self::set($key, $value);
		}
	}

	/**
	 * create setting (so it can be shown on settings page)
	 */
	public static function create (string $key, $value, string $title, string $description, string $owner, string $category = "general", $visible_permissions = "can_see_global_settings", $change_permissions = "can_change_global_settings", int $order = 10, string $icon_path = "none", string $last_update = "0000-00-00 00:00:00") {
		self::loadSettingsIfAbsent();

		if (strlen($key) > 255) {
			throw new IllegalArgumentException("max key length is 255, your key: " . $key);
		}

		//check, if setting already exists
		/*if (isset(self::$settings[$key])) {
			throw new IllegalArgumentException("global setting key '" . $key . "' already exists in database.");
		}*/

		//allow more than one possible permission as array
		if (is_array($visible_permissions)) {
			$visible_permissions = implode("|", $visible_permissions);
		}

		//allow more than one possible permission as array
		if (is_array($change_permissions)) {
			$change_permissions = implode("|", $change_permissions);
		}

		//serialize value
		$value = serialize($value);

		//insert setting into database
		Database::getInstance()->execute("INSERT INTO `{praefix}global_settings` (
			`key`, `value`, `title`, `description`, `visible_permission`, `change_permission`, `owner`, `order`, `icon_path`, `last_update`, `category`, `activated`
		) VALUES (
			:key, :value, :title, :description, :visible_permissions, :change_permissions, :owner, :order, :icon_path, '0000-00-00 00:00:00', :category, '1'
		) ON DUPLICATE KEY UPDATE `title` = :title, `description` = :description, `visible_permission` = :visible_permissions, `change_permission` = :change_permissions, `owner` = :owner, `order` = :order, `icon_path` = :icon_path, `last_update` = CURRENT_TIMESTAMP , `category` = :category; ", array(
			'key' => $key,
			'value' => $value,
			'title' => $title,
			'description' => $description,
			'visible_permissions' => $visible_permissions,
			'change_permissions' => $change_permissions,
			'owner' => $owner,
			'order' => (int) $order,
			'icon_path' => $icon_path,
			'category' => $category
		));

		//update value in local in-memory cache
		self::$settings[$key] = $value;

		//clear cache (area "global_settings")
		Cache::clear("global_settings");
	}

	/**
	 * delete setting
	 */
	public static function delete (string $key) {
		//remove from in-memory cache
		unset(self::$settings[$key]);

		//remove key in database
		Database::getInstance()->execute("DELETE FROM `{praefix}global_settings` WHERE `key` = :key; ", array('key' => $key));

		//clear cache (area "global_settings")
		Cache::clear("global_settings");
	}

	/**
	 * initialize settings and get global settings
	 */
	protected static function loadCategorySettings (string $category) {
		$category = Database::getInstance()->escape($category);

		$category_settings = array();

		if (Cache::contains("global_settings", "settings-category-" + $category)) {
			$category_settings = Cache::get("global_settings", "settings-category-" + $category);
		} else {
			//load settings from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}global_settings` WHERE `category` = :category AND `activated` = '1' ORDER BY `order`; ", array(
				'category' => array(
					'type' => PDO::PARAM_STR,
					'value' => $category
				)
			));

			foreach ($rows as $row) {
				$category_settings[$row['key']] = $row['value'];
			}

			//cache rows
			Cache::put("global_settings", "settings-category-" + $category, $category_settings);
		}

		//merge arrays
		self::$settings = array_merge(self::$settings, $category_settings);

		self::$initialized = true;
	}

	/**
	 * initialize settings and get global settings
	 */
	protected static function loadAllSettings () {
		echo "loadAllSettings<br />";

		if (Cache::contains("global_settings", "all-settings")) {
			self::$settings = Cache::get("global_settings", "all-settings");
		} else {
			//load settings from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}global_settings` WHERE `activated` = '1' ORDER BY `order`; ");

			echo "rows: ";
			print_r($rows);

			echo "<br />\n";
			
			self::$settings = array();

			foreach ($rows as $row) {
				self::$settings[$row['key']] = $row['value'];
			}

			echo "settings:<br />";
			print_r(self::$settings);

			//cache rows
			Cache::put("global_settings", "all-settings", self::$settings);
		}

		self::$initialized = true;
	}

	/**
	 * load settings, if class was not initialized yet
	 */
	protected static function loadSettingsIfAbsent () {
		if (!self::$initialized) {
			self::loadAllSettings();
		}
	}

}

?>
