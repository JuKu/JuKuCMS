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

		$pages = array();

		//get all pages from database
		$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}pages` LEFT JOIN `{praefix}user` ON (`{praefix}pages`.`author` = `{praefix}user`.`userID`) WHERE `{praefix}pages`.`editable` = '1'; ");

		foreach ($rows as $row) {
			$is_author_online = $row['online'] == 1;

			$pages[] = array(
				'id' => $row['id'],
				'alias' => $row['alias'],
				'title' => Translator::translateTitle($row['title']),
				'author' => $row['username'],
				'state' => "&nbsp;",
				'actions' => "&nbsp;",
				'online' => $is_author_online
			);
		}

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
