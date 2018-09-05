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
 * Date: 03.09.2018
 * Time: 14:36
 */

class PageEditPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/editpage");

		//check, if pageID is set
		if (!isset($_REQUEST['edit']) || empty($_REQUEST['edit'])) {
			//show error
			return $this->showError("No pageID was set!");
		}

		$pageID = (int) $_REQUEST['edit'];

		$page = new Page();
		$page->loadByID($pageID);

		//first check permissions
		if (!PermissionChecker::current()->hasRight("can_edit_all_pages") && !(PermissionChecker::current()->hasRight("can_edit_own_pages") && $page->getAuthorID() == User::current()->getID())) {
			//user doesn't have permissions to edit this page
			return $this->showError("You don't have permissions to edit this page!");
		}

		//TODO: add code here

		$template->assign("action_url", DomainUtils::generateURL($this->getPage()->getAlias(), array("edit" => $pageID)));

		$template->assign("page", array(
			'alias' => $page->getAlias(),
			'title' => (isset($_POST['title']) ? htmlentities($_POST['title']) : $page->getTitle()),
			'is_published' => $page->isPublished()
		));

		return $template->getCode();
	}

	protected function showError (string $message) : string {
		//show error
		$template = new DwooTemplate("pages/error");
		$template->assign("No pageID was set!");
		return $template->getCode();
	}

	public function getFooterScripts(): string {
		return "";
	}

	public function listRequiredPermissions(): array {
		return array("can_edit_all_pages", "can_edit_own_pages");
	}

}

?>
