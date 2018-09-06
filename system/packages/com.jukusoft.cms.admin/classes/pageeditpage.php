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

		$success_messages = array();
		$error_messages = array();

		//save page
		if (isset($_REQUEST['submit'])) {
			if ($_REQUEST['submit'] === "Save") {
				//save page
				$res = $this->save($page);

				if ($res === true) {
					$success_messages[] = "Saved page successfully!";
				} else {
					$error_messages[] = $res;
				}
			} else if ($_REQUEST['submit'] === "Publish") {
				//save page
				$res = $this->save($page);

				if ($res === true) {
					$success_messages[] = "Saved page successfully!";
				} else {
					$error_messages[] = $res;
				}

				//publish page
				$res = $this->publish($page);

				if ($res === true) {
					$success_messages[] = "Page published successfully!";
				} else {
					$error_messages[] = $res;
				}
			}
		}

		$template->assign("action_url", DomainUtils::generateURL($this->getPage()->getAlias(), array("edit" => $pageID)));

		$template->assign("page", array(
			'id' => $page->getPageID(),
			'alias' => "/" . $page->getAlias(),
			'title' => $page->getTitle(),
			'content' => $page->getContent(),
			'is_published' => $page->isPublished(),
			'can_publish' => (!$page->isPublished() && (PermissionChecker::current()->hasRight("can_publish_all_pages") || (PermissionChecker::current()->hasRight("can_publish_own_pages") && $page->getAuthorID() == User::current()->getID()))),
			'can_change_owner' => (PermissionChecker::current()->hasRight("can_change_page_owner") || $page->getAuthorID() == User::current()->getID())
		));

		//add support to show additional code from plugins
		$additional_code_header = "";
		$additional_code_footer = "";

		Events::throwEvent("page_edit_additional_code_header", array(
			'page' => &$page,
			'code' => &$additional_code_header
		));

		$template->assign("additional_code_header", $additional_code_footer);

		Events::throwEvent("page_edit_additional_code_footer", array(
			'page' => &$page,
			'code' => &$additional_code_footer
		));

		$template->assign("additional_code_footer", $additional_code_footer);

		$template->assign("errors", $error_messages);
		$template->assign("success_messages", $success_messages);

		return $template->getCode();
	}

	protected function save (Page &$page) {
		//first check permissions
		if (!PermissionChecker::current()->hasRight("can_edit_all_pages") && !(PermissionChecker::current()->hasRight("can_edit_own_pages") && $page->getAuthorID() == User::current()->getID())) {
			//user doesn't have permissions to edit this page
			return "You don't have permissions to edit this page!";
		}

		if (!isset($_POST['title']) || empty($_POST['title'])) {
			return "No title was set";
		}

		//validate title
		$title = htmlentities($_POST['title']);

		if (!isset($_POST['html_code']) || empty($_POST['html_code'])) {
			return "No content was set or content is empty!";
		}

		$content = $_POST['html_code'];

		//update page in database
		Database::getInstance()->execute("UPDATE `{praefix}pages` SET `title` = :title, `content` = :content WHERE `id` = :pageID; ", array(
			'title' => $title,
			'content' => $content,
			'pageID' => $page->getPageID()
		));

		//clear cache
		$page->clearCache();

		//reload page from database
		$page->loadByID($page->getPageID(), false);

		//TODO: remove this line later
		Cache::clear("pages");

		return true;
	}

	protected function publish (Page &$page) {
		//check permissions for publishing
		if (PermissionChecker::current()->hasRight("can_publish_all_pages") || (PermissionChecker::current()->hasRight("can_publish_own_pages") && $page->getAuthorID() == User::current()->getID())) {
			//update page in database
			Database::getInstance()->execute("UPDATE `{praefix}pages` SET `published` = '1' WHERE `id` = :pageID; ", array(
				'pageID' => $page->getPageID()
			));

			//clear cache
			$page->clearCache();

			//reload page from database
			$page->loadByID($page->getPageID(), false);

			//TODO: remove this line later
			Cache::clear("pages");

			return true;
		} else {
			return "You don't have the permissions to publish this page!";
		}
	}

	protected function showError (string $message) : string {
		//show error
		$template = new DwooTemplate("pages/error");
		$template->assign("message", "No pageID was set!");
		return $template->getCode();
	}

	public function getFooterScripts(): string {
		$style_name = Registry::singleton()->getSetting("current_style_name");
		$style_path = DomainUtils::getBaseURL() . "/styles/" . $style_name . "/";

		$thirdparty_url = Registry::singleton()->getSetting("thirdparty_url");

		/*return "<!-- CK Editor -->
			<script src=\"" . $style_path . "bower_components/ckeditor/ckeditor.js\"></script>
			
			<script>
				$(function () {
					// Replace the <textarea id=\"editor1\"> with a CKEditor
					// instance, using default configuration.
					CKEDITOR.replace('wysiwygEditor', {
						height: '500px',
						enterMode: CKEDITOR.ENTER_BR
					});
				});
			</script>";*/

		return "<script src=\"" . $thirdparty_url . "tinymce_4.8.2/js/tinymce/tinymce.min.js\"></script>
  				<script>tinymce.init({
					  selector: 'textarea',
					  height: 500,
					  theme: 'modern',
					  plugins: 'print preview fullpage searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
					  toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
					  image_advtab: true
				});</script>";
	}

	public function listRequiredPermissions(): array {
		return array("can_edit_all_pages", "can_edit_own_pages");
	}

}

?>
