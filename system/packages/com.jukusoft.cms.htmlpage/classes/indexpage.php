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
 * Date: 30.03.2018
 * Time: 13:59
 */

class IndexPage extends HTMLPage {

	public function getContent(): string {
		//first check, if specific template exists
		$current_style = Registry::singleton()->getSetting("current_style_name");
		if (file_exists(STYLE_PATH . $current_style . "/pages/home.tpl")) {
			$template = new Template($this->getPage()->hasCustomTemplate() ? $this->getPage()->getCustomTemplate() : "pages/home");

			$template->assign("TITLE", $this->getPage()->getTitle());
			$template->assign("CONTENT", $this->getHTML());

			$template->parse("main");
			return $template->getCode();
		} else {
			return $this->getHTML();
		}
	}

	public function showFooter(): bool {
		return false;
	}

	protected function getHTML () : string {
		$content = $this->getPage()->getContent();

		Events::throwEvent("get_content", array(
			'content' => &$content,
			'page' => &$this->page,
			'page_type' => &$this
		));

		return $content;
	}

}

?>
