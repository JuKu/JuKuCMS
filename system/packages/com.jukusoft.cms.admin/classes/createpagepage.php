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
 * Date: 02.09.2018
 * Time: 18:45
 */

class CreatePagePage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/createpage");

		//set form action url
		$template->assign("action_url", DomainUtils::generateURL($this->getPage()->getAlias()));

		//TODO: list folders

		//list page types
		$page_types_rows = PageType::listPageTypes();
		$page_types = array();

		foreach ($page_types_rows as $row) {
			$page_types[] = array(
				'title' => htmlentities(Translator::translateTitle($row['title'])),
				'class_name' => $row['page_type']
			);
		}

		$template->assign("pagetypes", $page_types);

		$sidebars = array();

		foreach (Sidebar::listSidebars() as $sidebar) {
			$sidebar = Sidebar::cast($sidebar);

			$sidebars[] = array(
				'id' => $sidebar->getSidebarId(),
				'title' => $sidebar->getTitle()
			);
		}

		$template->assign("sidebars", $sidebars);

		//set menus
		$menu_names = array();

		foreach (Menu::listMenuNames() as $row) {
			$menu_names[] = array(
				'id' => $row['menuID'],
				'title' => $row['title']
			);
		}

		$template->assign("menus", $menu_names);

		return $template->getCode();
	}

	public function getFooterScripts(): string {
		return "";
	}

	public function listRequiredPermissions(): array {
		return array("can_create_pages");
	}

}

?>
