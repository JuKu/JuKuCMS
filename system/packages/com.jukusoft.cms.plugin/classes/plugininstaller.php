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
	public function checkRequirements (bool $dontCheckPlugins = false) {
		//get require
		$require_array = $this->plugin->getRequiredPlugins();

		//get package list
		require(STORE_PATH . "package_list.php");

		$missing_plugins = array();

		//iterate through all requirements
		foreach ($require_array as $requirement=>$version) {
			if ($requirement === "php") {
				//check php version
				if (!$this->checkVersion($version, phpversion())) {
					$missing_plugins[] = $requirement;

					continue;
				}
			} else if (PHPUtils::startsWith($requirement, "ext-")) {
				//check php extension

				$extension = str_replace("ext-", "", $requirement);

				//check, if php extension is loaded
				if (!extension_loaded($extension)) {
					$missing_plugins[] = $requirement;

					continue;
				}

				//get extension version
				$current_version = phpversion($extension);

				//check version
				if (!$this->checkVersion($version, $current_version)) {
					$missing_plugins[] = $requirement;

					continue;
				}
			} else if (PHPUtils::startsWith($requirement, "package-")) {
				//TODO: check if package is installed
				$package = str_replace("package-", "", $requirement);

				//packages doesnt supports specific version

				if (!isset($package_list[$package])) {
					$missing_plugins[] = $requirement;

					continue;
				}
			} else if ($requirement === "core") {
				//TODO: check core version

				if ($version === "*") {
					//we dont have to check version
				} else {
					//get current version
					$array = explode(" ", Version::current()->getVersion());
					$current_core_version = $array[0];

					//check version
					if (!$this->checkVersion($version, $current_core_version)) {
						$missing_plugins[] = "core";
					}
				}
			} else {
				if (!$dontCheckPlugins) {
					continue;
				}

				//TODO: check installed plugins
			}
		}

		if (empty($missing_plugins)) {
			return true;
		} else {
			return $missing_plugins;
		}
	}

	protected function checkVersion (string $expected_version, $current_version) : bool {
		//remove alpha and beta labels
		$expected_version = str_replace("-alpha", "", $expected_version);
		$expected_version = str_replace("-beta", "", $expected_version);
		$current_version = str_replace("-alpha", "", $current_version);
		$current_version = str_replace("-beta", "", $current_version);

		//check version
		if (is_numeric($expected_version)) {
			//a specific version is required
			if ($current_version !== $expected_version) {
				return false;
			} else {
				return true;
			}
		} else if ($expected_version === "*") {
			//every version is allowed
			return true;
		} else {
			//parse version string

			$operator_length = 0;

			for ($i = 0; $i < strlen($expected_version); $i++) {
				if (!is_numeric($expected_version[$i])) {
					$operator_length++;
				} else {
					break;
				}
			}

			//get operator and version
			$operator = substr($expected_version, 0, $operator_length);
			$version = substr($expected_version, $operator_length);

			if (!empty($operator_length)) {
				return version_compare($current_version, $expected_version, $operator) === TRUE;
			} else {
				return version_compare($current_version, $expected_version) === 0;
			}
		}
	}

}

?>
