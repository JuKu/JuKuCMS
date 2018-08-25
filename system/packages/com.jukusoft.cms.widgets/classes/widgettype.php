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
 * Date: 23.08.2018
 * Time: 23:08
 */

class WidgetType {

	public static function register (string $class_name, string $title, string $description, bool $editable = true, string $owner = "system") {
		if ($owner !== "system") {
			if (!PHPUtils::startsWith($owner, "plugin_") && !PHPUtils::startsWith($owner, "style_")) {
				throw new IllegalArgumentException("owner has to start with 'plugin_' or 'style_'!");
			}
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}widget_types` (
			`id`, `name`, `description`, `class_name`, `editable`, `owner`
		) VALUES (
			NULL, :name, :description, :class_name, :editable, :owner
		) ON DUPLICATE KEY UPDATE `name` = :name, `description` = :description, `class_name` = :class_name", array(
			'name' => $title,
			'description' => $description,
			'class_name' => $class_name,
			'editable' => ($editable ? 1 : 0),
			'owner' => $owner
		));

		//clear cache
		Cache::clear("widgets");
	}

	public static function unregister (string $class_name) {
		Database::getInstance()->execute("DELETE FROM `{praefix}widget_types` WHERE `class_name` = :class_name; ", array(
			'class_name' => $class_name
		));

		//clear cache
		Cache::clear("widgets");
	}

	public static function unregisterByOwner (string $owner) {
		Database::getInstance()->execute("DELETE FROM `{praefix}widget_types` WHERE `owner` = :owner; ", array(
			'owner' => $owner
		));

		//clear cache
		Cache::clear("widgets");
	}

}

?>
