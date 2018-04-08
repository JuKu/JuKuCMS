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
 * Date: 08.04.2018
 * Time: 14:45
 */

class PluginInstaller {

	//plugin to install / deinstall
	protected $plugin = null;

	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * check php version, php extensions and so on
	 *
	 * @return mixed true, if all required plugins are available, or an array with missing
	 */
	public function checkRequirements () {
		//get require
		$require_array = $this->plugin->getRequiredPlugins();

		//get package list
		require(STORE_PATH . "package_list.php");

		$missing_plugins = array();

		//iterate through all requirements
		foreach ($require_array as $requirement=>$version) {
			if ($requirement === "php") {
				//TODO: check php version
			} else if (PHPUtils::startsWith($requirement, "ext-")) {
				//check php extension
			} else if (PHPUtils::startsWith($requirement, "package-")) {
				//TODO: check if package is installed
				$package = str_replace("package-" . $requirement);

				//packages doesnt supports version

				if (!isset($package_list[$package])) {
					$missing_plugins[] = $requirement;
				}
			} else if ($requirement === "core") {
				//TODO: check core version
			} else {
				//TODO: check installed plugins
			}
		}

		if (empty($missing_plugins)) {
			return true;
		} else {
			return $missing_plugins;
		}
	}

}

?>
