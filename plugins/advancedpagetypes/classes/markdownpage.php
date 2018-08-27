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
 * Date: 11.04.2018
 * Time: 00:40
 */

class Plugin_AdvancedPageTypes_MarkdownPage extends PageType {

	//https://commonmark.thephpleague.com/

	//https://caret.io/

	//http://parsedown.org/

	//https://github.com/erusev/parsedown-extra

	//https://stackoverflow.com/questions/32068537/generate-table-of-contents-from-markdown-in-php

	//https://github.com/erusev/parsedown/wiki/Tutorial:-Get-Started

	public function getContent(): string {
		$content = "";

		//check, if generated html is already cached
		if (Cache::contains("plugin_advancedpagetypes", "markdown_" . $this->getPage()->getAlias())) {
			$content = Cache::get("plugin_advancedpagetypes", "markdown_" . $this->getPage()->getAlias());
		} else {
			//require parsedown
			require_once(PLUGIN_PATH . "advancedpagetypes/parsedown-1.7.1/Parsedown.php");

			$parsedown = Parsedown::instance();

			//enables automatic line breaks
			$parsedown->setBreaksEnabled(true);

			//escape html
			$parsedown->setMarkupEscaped(true);

			//automatically link urls
			$parsedown->setUrlsLinked(true);

			//enable safe mode
			$parsedown->setSafeMode(true);

			$content = $parsedown->text($this->getPage()->getContent());

			//cache content
			Cache::put("plugin_advancedpagetypes", "markdown_" . $this->getPage()->getAlias(), $content);
		}

		Events::throwEvent("plugin_markdownpage_parse", array(
			'content' => &$content,
			'page' => &$this->getPage(),
			'page_type' => &$this
		));

		return $content;
	}

}

?>
