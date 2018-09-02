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
 * Date: 31.08.2018
 * Time: 22:40
 */

class PageListPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/pagelist");

		$template->assign("no_edit_permissions", (boolean) (!PermissionChecker::current()->hasRight("an_edit_all_pages") && !PermissionChecker::current()->hasRight("can_edit_own_pages")));

		//set table columns
		$template->assign("columns", array(
			Translator::translate("ID"),
			Translator::translate("Alias"),
			Translator::translate("Title"),
			Translator::translate("Author"),
			Translator::translate("State"),
			Translator::translate("Actions")
		));

		//get permissions
		$current_userID = User::current()->getID();
		$permission_can_edit_all_pages = PermissionChecker::current()->hasRight("can_edit_all_pages");
		$permission_can_edit_own_pages = PermissionChecker::current()->hasRight("can_edit_own_pages");
		$permission_can_unlock_all_pages = PermissionChecker::current()->hasRight("can_unlock_all_pages");
		$permission_can_delete_own_pages = PermissionChecker::current()->hasRight("can_delete_own_pages");
		$permission_can_delete_all_pages = PermissionChecker::current()->hasRight("can_delete_all_pages");
		$permission_can_see_trash_pages = PermissionChecker::current()->hasRight("can_see_trash_pages");
		$permission_can_restore_trash_pages = PermissionChecker::current()->hasRight("can_restore_trash_pages");
		$permission_can_delete_all_pages_permanently = PermissionChecker::current()->hasRight("can_delete_all_pages_permanently");

		$success_messages = array();

		//unlock pages
		if (isset($_REQUEST['unlock']) && $permission_can_unlock_all_pages) {
			$pageID = (int) $_REQUEST['unlock'];
			Page::unlockPage($pageID);

			$success_messages[] = "Unlocked page successfully!";
		}

		//move pages to trash
		if (isset($_REQUEST['trash']) && is_numeric($_REQUEST['trash']) && ($permission_can_delete_own_pages || $permission_can_delete_all_pages)) {
			//move page to trash
			$pageID = (int) $_REQUEST['trash'];

			//load page
			$page = new Page();
			$page->loadByID($pageID);

			//check permisssion
			if ($permission_can_delete_all_pages || ($permission_can_delete_own_pages && $page->getAuthorID() == User::current()->getID())) {
				//check, if page is deletable
				if ($page->isDeletable()) {
					//move page to trash
					$page->moveToTrash();

					$success_messages[] = "Moved page '" . $page->getAlias() . "' to trash.";
				}
			}
		}

		//restore pages from trash
		if (isset($_REQUEST['restore']) && is_numeric($_REQUEST['restore']) && $permission_can_restore_trash_pages) {
			//restore page
			$pageID = (int) $_REQUEST['restore'];

			//load page
			$page = new Page();
			$page->loadByID($pageID);

			if ($page->isTrash()) {
				$page->restore();

				$success_messages[] = "Restored page '" . $page->getAlias() . "' successfully!";
			}
		}

		//delete pages from trash
		if (isset($_REQUEST['delete_permanently']) && is_numeric($_REQUEST['delete_permanently']) && $permission_can_delete_all_pages_permanently) {
			$pageID = (int) $_REQUEST['delete_permanently'];

			//load page
			$page = new Page();
			$page->loadByID($pageID);

			//check, if page is in trash
			if ($page->isTrash()) {
				Page::deleteByID($page->getPageID());

				$success_messages[] = "Deleted page '" . $page->getAlias() . "' permanently successful!";
			}
		}

		$show_trash = false;

		//show pages in trash
		if (isset($_REQUEST['show_trash']) && $permission_can_see_trash_pages) {
			$show_trash = true;
		}

		$template->assign("show_trash", $show_trash);
		$template->assign("page_url", DomainUtils::generateURL($this->getPage()->getAlias()));

		$pages = array();

		if (!$show_trash) {
			//count pages in trash
			$row = Database::getInstance()->getRow("SELECT COUNT(*) FROM `{praefix}pages` WHERE `activated` = 2; ");

			$number_of_pages_in_trash = (int) $row['COUNT(*)'];
			$template->assign("pages_in_trash", $number_of_pages_in_trash);
		}

		//get all pages from database
		$rows = Database::getInstance()->listRows("SELECT *, `{praefix}pages`.`activated` as `activated` FROM `{praefix}pages` LEFT JOIN `{praefix}user` ON (`{praefix}pages`.`author` = `{praefix}user`.`userID`) WHERE `{praefix}pages`.`editable` = '1' AND `{praefix}pages`.`activated` = :activated; ", array(
			'activated' => (!$show_trash ? 1 : 2)
		));

		foreach ($rows as $row) {
			$is_author_online = $row['online'] == 1;
			$is_own_page = $row['author'] == $current_userID;
			$editable = $permission_can_edit_all_pages || ($permission_can_edit_own_pages && $is_own_page);
			$is_trash = $row['activated'] == 2;

			$pages[] = array(
				'id' => $row['id'],
				'alias' => $row['alias'],
				'title' => Translator::translateTitle($row['title']),
				'author' => $row['username'],
				'state' => ($row['published'] == 1 ? "Published" : "Draft"),
				'actions' => "&nbsp;",
				'user_online' => (boolean) $is_author_online,
				'url' => DomainUtils::generateURL($row['alias']),
				'own_page' => (boolean) $is_own_page,
				'editable' => (boolean) $editable,
				'published' => $row['published'] == 1,
				'locked' => $row['locked_by'] != -1,
				'locked_user' => $row['locked_by'],
				'locked_timestamp' => $row['locked_timestamp'],
				'unlock_url' => DomainUtils::generateURL($this->getPage()->getAlias(), array("unlock" => $row['id'])),
				'can_edit' => ($permission_can_edit_all_pages || ($permission_can_edit_own_pages && $is_own_page)) && $row['editable'] == 1,
				'edit_url' => DomainUtils::generateURL("admin/edit_page", array("edit" => $row['id'])),
				'can_delete' => ($permission_can_delete_all_pages || ($permission_can_delete_own_pages && $is_own_page)) && $row['deletable'] == 1,
				'delete_url' => DomainUtils::generateURL($this->getPage()->getAlias(), array("trash" => $row['id'])),
				'is_in_trash' => (boolean) $is_trash,
				'restore_url' => DomainUtils::generateURL($this->getPage()->getAlias(), array("restore" => $row['id'])),
				'delete_permanently_url' => DomainUtils::generateURL($this->getPage()->getAlias(), array("delete_permanently" => $row['id']))
			);
		}

		$template->assign("permission_can_unlock_all_pages", $permission_can_unlock_all_pages);
		$template->assign("permission_can_restore_trash_pages", $permission_can_restore_trash_pages);
		$template->assign("permission_can_delete_all_pages_permanently", $permission_can_delete_all_pages_permanently);

		$template->assign("success_messages", $success_messages);

		$template->assign("pagelist", $pages);

		return $template->getCode();
	}

	public function getFooterScripts(): string {
		return "<script>
		  $(function () {
			$('#pagetable').DataTable({
			  'paging'      : true,
			  'lengthChange': false,
			  'searching'   : true,
			  'ordering'    : true,
			  'info'        : true,
			  'autoWidth'   : false
			});
		  });
		</script>";
	}

	public function listRequiredPermissions(): array {
		return array("can_see_all_pages", "can_edit_all_pages");
	}

}

?>
