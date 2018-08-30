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
 * Time: 17:04
 */

class SettingsPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/settings");

		$template->assign("form_action", DomainUtils::generateURL("admin/settings", array("option" => "save")));
		$template->assign("content", "");

		$categories = array();

		$all_settings_by_category = Settings::listAllSettingsByCategory();

		$save = false;
		$save_success = true;

		if (isset($_REQUEST['option']) && $_REQUEST['option'] == "save") {
			$save = true;
		}

		//check permission
		if (!PermissionChecker::current()->hasRight("can_edit_global_settings")) {
			$save = false;
		}

		$template->assign("permission_to_edit_settings", /*PermissionChecker::current()->hasRight("can_edit_global_settings")*/false);

		foreach (SettingsCategory::listAllCategories() as $category) {
			$category = SettingsCategory::cast($category);

			$settings = array();

			if (isset($all_settings_by_category[$category->getCategory()])) {
				//list settings
				$rows = $all_settings_by_category[$category->getCategory()];

				foreach ($rows as $key=>$row) {
					$datatype = $row['datatype'];
					$datatype_params = unserialize($row['datatype_params']);

					$obj = new $datatype();

					if (!($obj instanceof DataType_Base)) {
						throw new IllegalArgumentException("obj of class name '" . $datatype . "' has to be an instance of DataType_Base.");
					}

					//load instance
					$obj->load($row, $datatype_params);

					if ($save) {
						//try to validate
						if (!$obj->val()) {
							$save_success = false;
						} else {
							//save object
							$obj->save();
						}
					}

					$settings[] = array(
						'title' => Translator::translateTitle($row['title']),
						'description' => Translator::translateTitle($row['description']),
						'code' => $obj->getFormCode()
					);
				}
			}

			$categories[] = array(
				'title' => $category->getTitle(),
				'settings' => $settings
			);
		}

		Settings::saveAsync();

		$template->assign("categories", $categories);

		return $template->getCode();
	}

	public function listRequiredPermissions(): array {
		return array("can_see_global_settings", "can_edit_global_settings");
	}

}

?>
