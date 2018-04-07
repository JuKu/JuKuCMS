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

class PageType {

	protected $page = null;

	public function __construct() {
		//
	}

	public function setPage (Page &$page) {
		$this->page = $page;
	}

	protected function getPage () : Page {
		return $this->page;
	}

	public function showDesign () {
		return true;
	}

	public function getContentType () : string {
		return "" . $this->page->getContentType() . "; charset=" . $this->getCharset();
	}

	public function getCharset () : string {
		return "utf-8";
	}

	public function setCustomHeader () {
		//
	}

	public function getAdditionalHeaderCode () : string {
		return "";
	}

	public function showFooter () : bool {
		return true;
	}

	public function showHTMLComments () : bool {
		return true;
	}

	public function getContent () : string {
		$content = $this->getPage()->getContent();

		//check, if page has custom template
		if ($this->getPage()->hasCustomTemplate()) {
			//get custom template
			$template = Validator_String::get($this->getPage()->getCustomTemplate());

			$current_style = Registry::singleton()->getSetting("current_style_name");

			//check, if custom template exists
			if (file_exists(STYLE_PATH . $current_style . "/" . $template)) {
				$template = new Template($template);

				$template->assign("TITLE", $this->getPage()->getTitle());
				$template->assign("CONTENT", $content);

				$template->parse("main");
				$content = $template->getCode();
			} else {
				throw new IllegalStateException("Custom template '" . $template . "' doesnt exists.");
			}
		}

		Events::throwEvent("get_content", array(
			'content' => &$content,
			'page' => &$this->page,
			'page_type' => &$this
		));

		return $content;
	}

	public function generateNormalPage (string $content, $vars = array()) : string {
		$current_style = Registry::singleton()->getSetting("current_style_name");

		if (file_exists(STYLE_PATH . $current_style . "/normal.tpl")) {
			$template = new DwooTemplate(STYLE_PATH . $current_style . "/normal.tpl");

			$template->assign("TITLE", $this->getPage()->getTitle());
			$template->assign("CONTENT", $content);

			Events::throwEvent("generate_normal_page", array(
				'template' => &$template,
				'current_style' => $current_style,
				'content' => &$content,
				'page_type' => &$this,
				'page' => $this->getPage()
			));

			foreach ($vars as $key=>$value) {
				$template->assign($key, $value);
			}

			return $template->getCode();
		} else {
			throw new IllegalStateException("no normal template (pages/normal.tpl) found!");
			//return $content;
		}
	}

	public function checkPermissions (PermissionChecker $permission_checker) {
		//first, check required permissions
		if (count($this->listRequiredPermissions()) > 0) {
			$bool = false;

			foreach ($this->listRequiredPermissions() as $permission) {
				if ($permission_checker->hasRight($permission)) {
					$bool = true;
					break;
				}
			}

			if (!$bool) {
				return false;
			}
		}

		if (!$this->getPage()->hasCustomPermissions()) {
			return true;
		} else {
			$permissions = $this->getPage()->listCustomPermissions();

			foreach ($permissions as $permission) {
				if ($permission_checker->hasRight($permission)) {
					return true;
				}
			}

			return false;
		}
	}

	protected function listRequiredPermissions () : array {
		return array();
	}

	public function exitAfterOutput () {
		return false;
	}

	public static function reloadCache () {
		Cache::clear("pagetypes");
	}

	public static function listPageTypes (bool $advanced = false) : array {
		$rows = array();

		$advanced_str = $advanced ? "advanced" : "normal";

		if (Cache::contains("pagetypes", "list-" . $advanced_str)) {
			$rows = Cache::get("pagetypes", "list-" . $advanced_str);
		} else {
			if ($advanced) {
				//show all page types
				$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}page_types` WHERE `activated` = '1' ORDER BY `order`; ");
			} else {
				//show only not-expert page types
				$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}page_types` WHERE `advanced` = '0' AND `activated` = '1' ORDER BY `order`; ");
			}

			//put into cache
			Cache::put("pagetypes", "list-" . $advanced_str, $rows);
		}

		return $rows;
	}

	public static function createPageType (string $class_name, string $title, bool $advanced = false,int $order = 10, array $permissions = array("none")) {
		//validate values
		$class_name = Validator_String::get($class_name);
		$title = Validator_String::get($title);
		$order = Validator_Int::get($order);

		Events::throwEvent("before_add_pagetype", array(
			'class_name' => &$class_name,
			'title' => &$title,
			'create_permissions' => &$permissions,
			'advanced' => &$advanced,
			'order' => &$order
		));

		//validate and convert array to string
		$permissions = implode("|", $permissions);
		$permissions = Validator_String::get($permissions);

		Database::getInstance()->execute("INSERT INTO `{praefix}page_types` (
			`page_type`, `title`, `create_permissions`, `advanced`, `order`, `activated`
		) VALUES (
			:pagetype, :title, :permissions, :advanced, :order, '1'
		) ON DUPLICATE KEY UPDATE `title` = :title, `advanced` = :advanced, `activated` = '1'; ", array(
			'pagetype' => $class_name,
			'title' => $title,
			'permissions' => $permissions,
			'advanced' => ($advanced ? 1 : 0),
			'order' => $order
		));

		Events::throwEvent("after_add_pagetype", array(
			'class_name' => $class_name,
			'title' => $title,
			'create_permissions' => $permissions,
			'advanced' => $advanced,
			'order' => $order
		));

		//clear cache
		Cache::clear("pagetypes");
	}

	public static function removePageType (string $class_name) {
		//validate value
		$class_name = Validator_String::get($class_name);

		$delete = true;

		//throw event, so plugins can interact
		Events::throwEvent("before_remove_pagetype", array(
			'class_name' => &$class_name,
			'delete' => &$delete
		));

		if ($delete) {
			Database::getInstance()->execute("DELETE FROM `{praefix}page_types` WHERE `page_type` = :pagetype; ", array('pagetype' => $class_name));

			//throw event, so plugins can interact
			Events::throwEvent("after_remove_pagetype", array(
				'class_name' => $class_name
			));

			//clear cache
			Cache::clear("pagetypes");
		}
	}

}

?>
