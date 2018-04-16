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
 * Date: 16.04.2018
 * Time: 23:00
 */

class StoreInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['store'])) {
			$store_dirs = $install_json['store'];

			foreach ($store_dirs as $dir) {
				if (is_array($dir)) {
					$dir_path = STORE_PATH . $dir['dir'];
					$permissions = "0" . $dir['chmod'];

					//create directory, if not exists
					if (!file_exists($dir_path)) {
						//create directory
						mkdir($dir_path);
					}

					chmod($dir_path, $permissions);
				} else {
					$dir = str_replace("..", "", $dir);

					//get directory path
					$dir_path = STORE_PATH . $dir;

					//create directory, if not exists
					if (!file_exists($dir_path)) {
						//create directory
						mkdir($dir_path);

						if(!chmod($dir_path, 0777)) {
							chmod($dir_path, 0755);

							throw new IllegalStateException("Cannot change file permissions of directory '". $dir_path . "'");
						}
					}
				}
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//dont do anything, because directories should not be deleted

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		return $this->install($plugin, $install_json);
	}

}

?>
