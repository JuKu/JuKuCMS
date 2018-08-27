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
 * Date: 26.04.2018
 * Time: 22:45
 */

class ApiMethodInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['api_methods'])) {
			foreach ($install_json['api_methods'] as $array) {
				$api_method = $array['api_method'];
				$classname = $array['class'];
				$method = $array['method'];

				//add api method
				ApiMethod::addMethod($api_method, $classname, $method, "plugin_" . $plugin->getName());
			}
		}

		return true;
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		ApiMethod::deleteMethodsByOwner("plugin_" . $plugin->getName());

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//remove api methods first
		$this->uninstall($plugin, $install_json);

		//install api methods
		return $this->install($plugin, $install_json);
	}

}

?>
