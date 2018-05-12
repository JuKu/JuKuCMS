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
 * Date: 12.05.2018
 * Time: 16:49
 */

class SettingsInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		if (isset($install_json['set-settings'])) {
			$prefs = new Preferences("plugin_" . $plugin->getName() . "_uninstall");

			foreach ($install_json['set-settings'] as $key=>$value) {
				if (Settings::contains($key)) {
					//backup old value
					$old_value = Settings::get($key);
					$prefs->put($key, $old_value);
				}

				//set new value
				Settings::set($key, $value);
			}

			$prefs->save();
		}
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//restore old values

		$prefs = new Preferences("plugin_" . $plugin->getName() . "_uninstall");

		foreach ($prefs->listAll() as $key=>$value) {
			Settings::set($key, $value);
		}

		//clear preferences
		$prefs->clearAll();
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		return $this->install($plugin, $install_json);
	}

}

?>
