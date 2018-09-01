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
 * Date: 10.04.2018
 * Time: 23:55
 */

class PageInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['pages'])) {
			foreach ($install_json['pages'] as $page) {
				$alias = $page['alias'];
				$title = $page['title'];
				$pagetype = $page['pagetype'];
				$content = (isset($page['content']) ? $page['content'] : "");
				$folder = (isset($page['folder']) ? $page['folder'] : "/");
				$global_menu = (isset($page['global_menu']) ? intval($page['global_menu']) : -1);
				$local_menu = (isset($page['local_menu']) ? intval($page['local_menu']) : -1);
				$parentID = (isset($page['parentID']) ? intval($page['parentID']) : -1);
				$sitemap = (isset($page['sitemap']) ? boolval($page['sitemap']) : true);
				$published = (isset($page['published']) ? boolval($page['published']) : true);
				$editable = (isset($page['editable']) ? boolval($page['editable']) : true);
				$author = (isset($page['author']) ? $page['author'] : "System");

				Page::createIfAbsent($alias, $title, $pagetype, $content, $folder, $global_menu, $local_menu, $parentID, $sitemap, $published, $editable, false, $author);
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['pages'])) {
			foreach ($install_json['pages'] as $page) {
				$alias = $page['alias'];
				$autoremove_on_uninstall = boolval($page['autoremove_on_uninstall']);

				if ($autoremove_on_uninstall) {
					Page::delete($alias);
				}
			}
		}

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//TODO: remove old pages, which arent longer used

		//install supports ON DUPLICATE KEY
		$this->install($plugin, $install_json);
	}

}

?>
