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
 * Time: 18:51
 */

class FolderInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['folders'])) {
			foreach ($install_json['folders'] as $folder) {
				$name = $folder['folder'];
				$hidden = (isset($folder['hidden']) ? boolval($folder['hidden']) : false);
				$permissions = (isset($folder['permissions']) ? $folder['permissions'] : array("none"));
				$main_menu = (isset($folder['main_menu']) ? intval($folder['main_menu']) : -1);
				$local_menu = (isset($folder['local_menu']) ? intval($folder['local_menu']) : -1);
				$force_template = (isset($folder['force_template']) ? $folder['force_template'] : "none");
				$title_translation = (isset($folder['title_translation']) ? boolval($folder['title_translation']) : true);

				//create folder
				Folder::createFolderIfAbsent($name, $hidden, $permissions, $main_menu, $local_menu, $force_template, $title_translation);
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//folders will not removed, because they can contain pages
		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//install supports ON DUPLICATE KEY
		return $this->install($plugin, $install_json);
	}

}

?>
