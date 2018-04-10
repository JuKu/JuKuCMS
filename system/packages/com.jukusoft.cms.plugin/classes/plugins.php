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
 * Time: 19:12
 */

class Plugins {

	public static function listAvailablePluginNames () : array {
		$names = array();

		//use directory iterator
		$dir = new DirectoryIterator(PLUGIN_PATH);

		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$names[] = $fileinfo->getFilename();
			}
		}

		return $names;
	}

	public static function listInstalledPluginNames () : array {
		if (Cache::contains("plugins", "installed_plugin_names")) {
			return Cache::get("plugins", "installed_plugin_names");
		} else {
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}plugins` WHERE `installed` = '1'; ");

			$array = array();

			foreach ($rows as $row) {
				$array[] = $row['name'];
			}

			//cache rows
			Cache::put("plugins", "installed_plugin_names", $array);

			return $rows;
		}
	}

	public static function listInstalledPlugins () : array {
		if (Cache::contains("plugins", "installed_plugins")) {
			return Cache::get("plugins", "installed_plugins");
		} else {
			//read installed plugins from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}plugins` WHERE `installed` = '1'; ");

			$plugins = array();

			foreach ($rows as $row) {
				//create new plugin instance
				$plugin = new Plugin($row['name'], $row);

				//load plugin
				$plugin->load();

				$plugins[$plugin->getName()] = $plugin;
			}

			//cache plugins
			Cache::put("plugins", "installed_plugins", $plugins);

			return $plugins;
		}
	}

	public static function listUninstalledPlugins () : array {
		$installed_plugin_names = self::listInstalledPluginNames();

		//create new empty list
		$list = array();

		$dir = new DirectoryIterator(PLUGIN_PATH);

		foreach ($dir as $fileInfo) {
			if ($fileInfo->isDot()) {
				//dont parse directory "."
				continue;
			}

			if (!$fileInfo->isDir()) {
				//we only search for directories
				continue;
			}

			//get directory name
			$name = $fileInfo->getFilename();

			//check, if plugin is already installed
			if (in_array($name, $installed_plugin_names)) {
				continue;
			}

			//create and load new plugin
			$plugin = new Plugin($name);
			$plugin->load();

			//add plugin to list
			$list[] = $plugin;
		}

		return $list;
	}

	public static function clearCache () {
		Cache::clear("plugins");
	}

}

?>
