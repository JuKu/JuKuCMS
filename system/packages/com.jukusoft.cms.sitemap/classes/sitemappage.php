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
 * Date: 04.04.2018
 * Time: 00:08
 */

class SitemapPage extends PageType {

	public function getContentType(): string {
		return "text/xml; charset=" . $this->getCharset();
	}

	public function getContent(): string {
		$template = new DwooTemplate(PACKAGE_PATH . "com.jukusoft.cms.sitemap/template/sitemap.tpl");

		$urls = array();

		$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}pages` WHERE `sitemap` = '1' AND `published` = '1' AND `activated` = '1'; ");

		foreach ($rows as $row) {
			$entry = array();

			//generate url to page
			$entry['loc'] = DomainUtils::generateURL($row['alias']);

			//get last modified timestamp
			$last_update = ($row['last_update'] === "0000-00-00 00:00:00" ? $row['created'] : $row['last_update']);

			//timezone berlin
			$timezone = "+01:00";

			//convert last modification timestamp to w3c timestamp: https://www.w3.org/TR/NOTE-datetime
			$entry['lastmod'] = date('Y-m-d\TH:i:s', strtotime($last_update)) . $timezone;

			$entry['changefreq'] = strtolower($row['sitemap_changefreq']);
			$entry['priority'] = $row['sitemap_priority'];

			$urls[] = $entry;
		}

		$template->assign("urls", $urls);

		return $template->getCode();
	}

	public function showDesign() {
		return false;
	}

	public function showHTMLComments(): bool {
		return false;
	}

}

?>
