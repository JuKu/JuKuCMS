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

		Events::throwEvent("get_content", array(
			'content' => &$content,
			'page' => &$this->page,
			'page_type' => &$this
		));

		return $content;
	}

	public function exitAfterOutput () {
		return false;
	}

	public static function reloadCache () {
		Cache::clear("pagetypes");
	}

}

?>
