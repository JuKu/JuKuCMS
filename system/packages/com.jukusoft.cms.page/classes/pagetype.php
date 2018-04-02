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
		return "text/html; charset=" . $this->getCharset();
	}

	public function getCharset () : string {
		return "utf-8";
	}

	public function setCustomHeader () {
		//
	}

	public function showFooter () : bool {
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

	public function checkPermissions () {
		if (!$this->getPage()->hasCustomPermissions()) {
			return true;
		}
	}

	public function exitAfterOutput () {
		return false;
	}

	public static function reloadCache () {
		Cache::clear("pagetypes");
	}

	public static function createPageType (string $class_name, string $title, bool $advanced = false) {
		$class_name = Validator_String::get($class_name);
		$title = Validator_String::get($title);

		Database::getInstance()->execute("INSERT INTO `{praefix}page_types` (
			`page_type`, `title`, `advanced`, `activated`
		) VALUES (
			:pagetype, :title, :advanced, '1'
		) ON DUPLICATE KEY UPDATE `title` = :title, `advanced` = :advanced, `activated` = '1'; ", array(
			'pagetype' => $class_name,
			'title' => $title,
			'advanced' => ($advanced ? 1 : 0)
		));

		//clear cache
		Cache::clear("pagetypes");
	}

	public static function removePageType (string $class_name) {
		//
	}

}

?>
