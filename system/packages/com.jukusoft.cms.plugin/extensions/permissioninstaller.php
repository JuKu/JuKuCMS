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
 * Time: 18:34
 */

class PermissionInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['permissions'])) {
			$permissions = $install_json['permissions'];

			foreach ($permissions as $permission) {
				$token = $permission['token'];
				$title = $permission['title'];
				$description = $permission['description'];
				$category = (isset($permission['category']) ? $permission['category'] : "plugins");
				$order = (isset($permission['order']) ? intval($permission['order']) : 100);

				Permissions::createPermission($token, $title, $description, $category, "plugin_" . $plugin->getName(), $order);
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//delete permissions with plugin owner
		if (isset($install_json['permissions'])) {
			Permissions::deletePermissionsByOwner("plugin_" . $plugin->getName());
		}

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//TODO: remove permissions, which arent longer in install json

		//install queries supports ON DUPLICATE KEY
		return $this->install($plugin, $install_json);
	}

}

?>
