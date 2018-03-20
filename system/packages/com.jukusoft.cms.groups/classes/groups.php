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
 * Date: 20.03.2018
 * Time: 14:36
 */

class Groups {

	public static function createGroupIfIdAbsent (int $groupID, string $name, string $description, string $color = "#000000", bool $show = true, bool $system_group = false, bool $auto_assign_regist = false) {
		//check, if color is valide
		$validator = new Validator_Color();

		if (!$validator->isValide($color)) {
			throw new IllegalArgumentException("color '" . $color . "' isnt a valide hex color.");
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}groups` (
			`groupID`, `name`, `description`, `color`, `auto_assign_regist`, `system_group`, `show`, `activated`
		) VALUES (
			:groupID, :name, :description, :color, :auto_assign_regist, :system_group, :show, '1'
		) ON DUPLICATE KEY UPDATE `groupID` = :groupID; ", array(
			'groupID' => $groupID,
			'name' => Validator_String::get($name),
			'description' => Validator_String::get($description),
			'color' => $color,
			'auto_assign_regist' => ($auto_assign_regist ? 1 : 0),
			'system_group' => ($system_group ? 1 : 0),
			'show' => ($show ? 1 : 0)
		));

		//clear complete cache for all groups, so membership cache is also cleared
		Cache::clear("groups");
	}

	public static function deleteGroup (int $groupID) {
		$group = new Group();

		try {
			$group->loadById($groupID);
		} catch (IllegalStateException $e) {
			//group doesnt exists, we dont have to do anything
			return;
		}

		$group->delete();
	}

}

?>
