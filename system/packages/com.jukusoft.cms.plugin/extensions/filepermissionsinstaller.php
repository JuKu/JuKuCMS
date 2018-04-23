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
 * Date: 22.04.2018
 * Time: 11:51
 */

class FilePermissionsInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['chmod'])) {
			$files = $install_json['chmod'];

			foreach ($files as $file=>$chmod_value) {
				if (strpos($file, "..") !== FALSE) {
					throw new IllegalArgumentException("Its not allowed that chmod file path in install.json of plugin '" . $plugin->getName() . "' contains '..' in path.");
				}

				$file_path = ROOT_PATH . $file;

				if (!file_exists($file_path)) {
					//create directory
					//throw new IllegalStateException("directory '" . htmlentities($file_path) . "' doesnt exists.");

					//TODO: remove this line later
					mkdir($file_path);
				}

				if (strlen($chmod_value) != 3) {
					throw new IllegalArgumentException("Exception in install.json of plugin '" . $plugin->getName() . "': chmod value has to be a length of 3 characters (like 755).");
				}

				$chmod_value = "0" . $chmod_value;

				if(!chmod($file_path, $chmod_value)) {
					throw new IllegalStateException("Cannot change file permissions of directory '". $file_path . "' (plugin: " . $plugin->getName() . ".");
				}
			}
		}
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//dont do anything
		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		return $this->install($plugin, $install_json);
	}

	public function getPriority () : int {
		return 5;
	}

}

?>
