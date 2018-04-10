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
 * Date: 10.04.2018
 * Time: 22:56
 */

class PageTypeInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['pagetypes'])) {
			foreach ($install_json['pagetypes'] as $pagetype) {
				$class_name = $pagetype['class_name'];
				$title = $pagetype['title'];
				$permissions = (isset($pagetype['create_permissions']) ? $pagetype['create_permissions'] : array("none"));
				$advanced = (isset($pagetype['advanced']) ? boolval($pagetype['advanced']) : false);
				$order = (isset($pagetype['order']) ? intval($pagetype['advanced']) : 100);

				//create page type
				PageType::createPageType($class_name, $title, $advanced, $order, $permissions, "plugin_" . $plugin->getName());
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//remove all page types which was created by this plugin
		PageType::removePageTypesByOwner("plugin_" . $plugin->getName());

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//TODO: remove page types, which arent longer in install json

		//install supports ON DUPLICATE KEY
		return $this->install($plugin, $install_json);
	}

}

?>
