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
 * Date: 29.04.2018
 * Time: 22:30
 */

class MenuInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['menus'])) {
			foreach ($install_json['menus'] as $menu) {
				$menuID = $menu['menuID'];

				if (PHPUtils::containsStr($menuID, "settings:")) {
					$array = explode(":", $menuID);

					//load value from settings
					$menuID = intval(Settings::get($array[1], 1));
				}

				$title = $menu['title'];
				$url = $menu['url'];
				$parent = (isset($menu['parent']) ? intval($menu['parent']) : -1);
				$unique_name = (isset($menu['unique_name']) ? $menu['unique_name'] : "");
				$type = (isset($menu['type']) ? $menu['type'] : "page");
				$permissions = (isset($menu['permissions']) ? $menu['permissions'] : array("none"));
				$login_required = (isset($menu['login_required']) ? boolval($menu['login_required']) : false);
				$icon = (isset($menu['icon']) ? $menu['icon'] : "fa fa-circle");
				$order = (isset($menu['order']) ? intval($menu['order']) : 10);

				//create menu
				Menu::createMenu(null, $menuID, $title, $url, $parent, $unique_name, $type, $permissions, $login_required, $icon, $order, "plugin_" . $plugin->getName());
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		Menu::deleteMenusByOwner("plugin_" . $plugin->getName());

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//remove old menus
		$this->uninstall($plugin, $install_json);

		return $this->install($plugin, $install_json);
	}

}

?>
