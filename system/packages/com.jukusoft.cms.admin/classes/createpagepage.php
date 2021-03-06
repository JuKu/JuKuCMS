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

		$errors = array();

		if (isset($_REQUEST['action']) && $_REQUEST['action'] === "create" && PermissionChecker::current()->hasRight("can_create_pages")) {
			//try to create a new page

			//first, get all values
			if (!isset($_REQUEST['folder']) || empty($_REQUEST['folder'])) {
				$errors[] = "Folder is not set!";
			}

			if (!isset($_REQUEST['page_alias']) || empty($_REQUEST['page_alias'])) {
				$errors[] = "Page alias isn't set!";
			}

			if (!isset($_REQUEST['title']) || empty($_REQUEST['title'])) {
				$errors[] = "Title is't set!";
			}

			if (!isset($_REQUEST['pagetype']) || empty($_REQUEST['pagetype'])) {
				$errors[] = "Pagetype is't set!";
			}

			if (empty($errors)) {
				$folder = $_REQUEST['folder'];
				$alias = $_REQUEST['page_alias'];
				$title = $_REQUEST['title'];
				$pagetype = $_REQUEST['pagetype'];

				//check, if folder exists
				if (!Folder::exists($folder)) {
					$errors[] = "Folder '" . htmlentities($folder) . "' doesn't exists!";
				}

				//check, if page alias already exists
				$page_full_alias = $folder . $alias;

				if (PHPUtils::startsWith($page_full_alias, "/")) {
					//remove / at beginning
					$page_full_alias = substr($page_full_alias, 1);
				}

				if (Page::exists($page_full_alias)) {
					$errors[] = "Page alias '" . htmlentities($page_full_alias) . "' already exists!";
				}

				//remove html characters in title
				$title = htmlentities($title);

				//check, if pagetype exists
				if (!PageType::exists($pagetype)) {
					$errors[] = "Pagetype '" . htmlentities($pagetype) . "' doesn't exists!";
				}

				Events::throwEvent("before_create_page", array(
					'folder' => &$folder,
					'alias' => &$alias,
					'full_alias' => &$page_full_alias,
					'title' => &$title,
					'pagetype' => &$pagetype
				));

				if (empty($errors)) {
					$pageID = Page::createIfAbsent($page_full_alias, htmlentities($title), $pagetype, "", $folder, -1, -1, -1, true, false, true, true, User::current()->getUsername());

					Events::throwEvent("after_create_page", array(
						'pageID' => $pageID
					));

					//redirect header
					header("Location: " . DomainUtils::generateURL("admin/edit_page", array("edit" => $pageID)));

					exit;
				}
			}
		}

		//set form action url
		$template->assign("action_url", DomainUtils::generateURL($this->getPage()->getAlias(), array("action" => "create")));
		$template->assign("username", User::current()->getUsername());

		$template->assign("errors", $errors);

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

		$folders = array();

		foreach (Folder::listFolders(false) as $row) {
			$folders[] = array(
				'folder' => $row['folder'],
				'hidden' => $row['hidden'] == 1,
				'is_root_folder' => $row['folder'] === "/"
			);
		}

		$template->assign("folders", $folders);

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
