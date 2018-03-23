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

		Database::getInstance()->execute("INSERT INTO `{praefix}permission_category` (
			`category`, `title`, `area`, `show`, `order`, `activated`
		) VALUES (
			:category, :title, :area, '1', '1'
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

}

?>