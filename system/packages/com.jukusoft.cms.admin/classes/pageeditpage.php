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
				$res = $this->save();

				if ($res == true) {
					$success_messages[] = "Saved page successfully!";
				} else {
					$error_messages[] = $res;
				}
			} else if ($_REQUEST['submit'] === "Publish") {
				//save page
				$res = $this->save();

				if ($res == true) {
					$success_messages[] = "Saved page successfully!";
				} else {
					$error_messages[] = $res;
				}

				//publish page
				$res = $this->publish();

				if ($res == true) {
					$success_messages[] = "Page published successfully!";
				} else {
					$error_messages[] = $res;
				}
			}
		}

		$template->assign("action_url", DomainUtils::generateURL($this->getPage()->getAlias(), array("edit" => $pageID)));

		$template->assign("page", array(
			'alias' => "/" . $page->getAlias(),
			'title' => (isset($_POST['title']) ? htmlentities($_POST['title']) : $page->getTitle()),
			'content' => (isset($_POST['content']) ? $_POST['content'] : $page->getContent()),
			'is_published' => $page->isPublished(),
			'can_publish' => false//(!$page->isPublished() && (PermissionChecker::current()->hasRight("can_publish_all_pages") || (PermissionChecker::current()->hasRight("can_publish_own_pages") && $page->getAuthorID() == User::current()->getID())))
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

	protected function save () {
		return true;
	}

	protected function publish (Page $page) {
		//TODO: check permissions for publishing
		if (PermissionChecker::current()->hasRight("can_publish_all_pages") || (PermissionChecker::current()->hasRight("can_publish_own_pages") && $page->getAuthorID() == User::current()->getID())) {
			//
		} else {
			return "You don't have the permissions to publish this page!";
		}

		return true;
	}

	protected function showError (string $message) : string {
		//show error
		$template = new DwooTemplate("pages/error");
		$template->assign("No pageID was set!");
		return $template->getCode();
	}

	public function getFooterScripts(): string {
		$style_name = Registry::singleton()->getSetting("current_style_name");
		$style_path = DomainUtils::getBaseURL() . "/styles/" . $style_name . "/";

		return "<!-- CK Editor -->
			<script src=\"" . $style_path . "bower_components/ckeditor/ckeditor.js\"></script>
			
			<script>
				$(function () {
					// Replace the <textarea id=\"editor1\"> with a CKEditor
					// instance, using default configuration.
					CKEDITOR.replace('wysiwygEditor', {
						height: '500px',
						enterMode: CKEDITOR.ENTER_BR, 
						/*toolbar:    
						[   { name: 'document', groups: [ 'document', 'doctools' ], items: [ 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
							{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
							{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },        '/',
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
							{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] }, { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
							{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'Iframe', 'Syntaxhighlight' ] }, '/',
							{ name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
							{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
							{ name: 'others', groups: [ 'mode' ], items: [ 'Source', 'searchCode', 'autoFormat', 'CommentSelectedRange', 'UncommentSelectedRange', 'AutoComplete', '-', 'ShowBlocks' ] },
							{ name: 'tools', items: [ 'Maximize' ] },
						]*/
					});
				});
			</script>";
	}

	public function listRequiredPermissions(): array {
		return array("can_edit_all_pages", "can_edit_own_pages");
	}

}

?>
