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
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 07.04.2018
 * Time: 21:17
 */

class PluginsPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/plugins");

		//get list with installed plugins
		$installed_plugins = Plugins::listInstalledPlugins();

		$array = array();

		$lang_token = substr(Registry::singleton()->getSetting("lang_token"), 0, 2);

		foreach ($installed_plugins as $plugin) {
			$plugin = PLugin::castPlugin($plugin);

			$array[] = array(
				'name' => $plugin->getName(),
				'title' => $plugin->getTitle(),
				'description' => $plugin->getDescription($lang_token),
				'version' => $plugin->getVersion(),
				'installed_version' => $plugin->getInstalledVersion(),
				'homepage' => $plugin->getHomepage(),
				'authors' => $plugin->listAuthors(),
				'license' => $plugin->getLicense(),
				'keywords' => $plugin->listKeywords(),
				'categories' => $plugin->listCategories(),
				'text' => "",
				'issues' => $plugin->getIssuesLink(),
				'source' => $plugin->getSourceLink(),
				'support_mail' => $plugin->getSupportMail(),
				'support_links' => $plugin->listSupportLinks(),
				'installed' => $plugin->isInstalled(),
				'activated' => $plugin->isActivated()
			);
		}

		//assign list with installed plugins
		$template->assign("installed_plugins", $array);

		//get list with all uninstalled plugins
		$plugins = Plugins::listUninstalledPlugins();

		$plugin_list = array();

		foreach ($plugins as $plugin) {
			$plugin = PLugin::castPlugin($plugin);

			//create new instance of PluginInstaller to check plugin compatibility
			$installer = new PluginInstaller($plugin);

			$plugin_list[] = array(
				'name' => $plugin->getName(),
				'title' => $plugin->getTitle(),
				'description' => $plugin->getDescription($lang_token),
				'version' => $plugin->getVersion(),
				'installed_version' => $plugin->getInstalledVersion(),
				'homepage' => $plugin->getHomepage(),
				'authors' => $plugin->listAuthors(),
				'license' => $plugin->getLicense(),
				'keywords' => $plugin->listKeywords(),
				'categories' => $plugin->listCategories(),
				'text' => "",
				'issues' => $plugin->getIssuesLink(),
				'source' => $plugin->getSourceLink(),
				'support_mail' => $plugin->getSupportMail(),
				'support_links' => $plugin->listSupportLinks(),
				'compatible' => $installer->checkRequirements(true),
				'uptodate' => true,//TODO: check, if plugin is newest version
				'alpha' => $plugin->isAlpha(),
				'beta' => $plugin->isBeta(),
				'installed' => $plugin->isInstalled(),
				'activated' => $plugin->isActivated()
			);
		}

		$template->assign("plugins", $plugin_list);

		return $template->getCode();
	}

	public function getFooterScripts(): string {
		return "<!-- page script -->
			<script>
				$(function () {
					/*$('#example1').DataTable();*/
					$('#example2').DataTable({
						'paging'      : true,
						'lengthChange': false,
					 	'searching'   : false,
					 	'ordering'    : true,
					 	'info'        : true,
					 	'autoWidth'   : false
					});
					$('#plugintable').DataTable({
						'paging'      : true,
						'lengthChange': false,
					 	'searching'   : false,
					 	'ordering'    : true,
					 	'info'        : true,
					 	'autoWidth'   : false
					});
				});
			</script>";
	}

}

?>
