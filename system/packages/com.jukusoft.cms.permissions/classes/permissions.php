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
 * Date: 23.03.2018
 * Time: 18:38
 */

class Permissions {

	public static function createOrUpdateCategory (string $category, string $title, int $order = 100, string $area = "global") {
		//validate values
		$category = Validator_AlphaNumeric::get($category);
		$title = Validator_AlphaNumeric::get($category);
		$area = Validator_AlphaNumeric::get($area);
		$order = intval($order);

		Database::getInstance()->execute("INSERT INTO `{praefix}permission_category` (
			`category`, `title`, `area`, `show`, `order`, `activated`
		) VALUES (
			:category, :title, :area, '1', :order, '1'
		) ON DUPLICATE KEY UPDATE `title` = :title, `area` = :area, `order` = :order, `activated` = '1'; ", array(
			'category' => $category,
			'title' => $title,
			'area' => $area,
			'order' => $order
		));

		//clear cache
		Cache::clear("permissions", "categories");
	}

	public static function deleteCategory (string $category) {
		//validate value
		$category = Validator_AlphaNumeric::get($category);

		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}permission_category` WHERE `category` = :category; ", array('category' => $category));

		//clear cache
		Cache::clear("permissions", "categories");
	}

	public static function createPermission (string $token, string $title, string $description, string $category = "general", string $owner = "system", int $order = 100) {
		//validate values
		$token = Validator_Token::get($token);
		$title = Validator_String::get($title);
		$description = Validator_String::get($description);
		$category = Validator_Filename::get($category);
		$owner = Validator_AlphaNumeric::get($owner);
		$order = intval($order);

		Database::getInstance()->execute("ÌNSERT INTO `{praefix}permissions` (
			`token`, `title`, `description`, `category`, `owner`, `show`, `order`, `activated`
		) VALUES (
			:token, :title, :description, :category, :owner, '1', :order, '1'
		) ON DUPLICATE KEY UPDATE `title` = :title, `description` = :description, `category` = :category, `owner` = :owner, `order` = :order, `activated` = '1'; ", array(
			'token' => $token,
			'title' => $title,
			'description' => $description,
			'category' => $category,
			'owner' => $owner,
			'order' => $order
		));

		//clear cache
		Cache::clear("permissions", "permission_list");
	}

	public static function deletePermission (string $token) {
		//validate value
		$token = Validator_Token::get($token);

		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}permissions` WHERE `token` = :token; ", array('token' => $token));

		//clear cache
		Cache::clear("permissions", "permission_list");
	}

	public static function listPermissions (string $category = "") : array {
		$suffix = "";

		if ($category != "") {
			$suffix = "_" . Validator_AlphaNumeric::get($category);
		}

		if (Cache::contains("permissions", "permission_list" . $suffix)) {
			return Cache::get("permissions", "permission_list" . $suffix);
		} else {
			if ($category == "") {
				$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}permissions` WHERE `activated` = '1' ORDER BY `order`; ");
			} else {
				$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}permissions` WHERE `category` = :category, AND `activated` = '1' ORDER BY `order`; ", array('category' => $category));
			}

			Cache::put("permissions", "permission_list" . $suffix, $rows);

			return $rows;
		}
	}

}

?>
