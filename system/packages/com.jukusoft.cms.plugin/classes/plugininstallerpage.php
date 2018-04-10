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
 * Time: 16:32
 */

class PluginInstallerPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/plugininstaller");

		$error = false;

		$template->assign("error_message", "");

		if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
			//
		} else {
			//set error message
			$template->assign("error_message", "Invalide request, no action set!");

			$error = true;
		}

		if (!isset($_REQUEST['plugin']) || empty($_REQUEST['plugin'])) {
			//set error message
			$template->assign("error_message", "Invalide request, no plugin was set!");

			$error = true;
		}

		if (!$error) {
			$plugin = $_REQUEST['plugin'];
			$action = $_REQUEST['action'];

			//validate plugin
			if (!Validator_PluginName::getInstance()->isValide($plugin)) {
				//set error message
				$template->assign("error_message", "Invalide plugin name '" . htmlentities($plugin) . "'!");

				$error = true;
			} else {
				//create new instance
				$plugin = new Plugin($plugin);

				if (!$plugin->exists()) {
					//set error message
					$template->assign("error_message", "Plugin '" . htmlentities($plugin->getName()) . "' doesnt exists!");

					$error = true;
				} else {
					//load plugin.json
					$plugin->load();

					switch ($action) {
						case "install":
							//install plugin
							if ($this->installPlugin($plugin) === TRUE) {
								//send redirect header
								header("Location: " . DomainUtils::generateURL("admin/plugins"));

								$template->assign("success_message", "Plugin '" . $plugin->getName() . "' installed successfully!");
							} else {
								//set error message
								$template->assign("error_message", "Couldnt install plugin '" . htmlentities($plugin) . "'!");
							}

							break;
						case "uninstall":
							//load database row
							$plugin->loadRow();

							//uninstall plugin
							if ($this->uninstallPlugin($plugin) === TRUE) {
								//send redirect header
								header("Location: " . DomainUtils::generateURL("admin/plugins"));

								$template->assign("success_message", "Plugin '" . $plugin->getName() . "' uninstalled successfully!");
							} else {
								//set error message
								$template->assign("error_message", "Couldnt uninstall plugin '" . htmlentities($plugin) . "'!");
							}

							break;
						case "upgrade":
							//load database row
							$plugin->loadRow();

							//upgrade plugin
							if ($this->upgradePlugin($plugin) === TRUE) {
								//send redirect header
								header("Location: " . DomainUtils::generateURL("admin/plugins"));

								$template->assign("success_message", "Plugin '" . $plugin->getName() . "' upgraded successfully!");
							} else {
								//set error message
								$template->assign("error_message", "Couldnt upgrade plugin '" . htmlentities($plugin) . "'!");
							}

							break;
						default:
							//set error message
							$template->assign("error_message", "Unknown action type '" . htmlentities($action) . "'!");

							break;
					}
				}
			}
		}

		return $template->getCode();
	}

	protected function installPlugin (Plugin $plugin) {
		$installer = new PluginInstaller($plugin);

		$res = $installer->checkRequirements();

		//check requirements first
		if ($res !== TRUE) {
			return array(
				'error' => $res
			);
		}

		//check, if plugin is already installed
		if ($plugin->isInstalled()) {
			return false;
		}

		//try to install plugin
		return $installer->install();
	}

	protected function uninstallPlugin (Plugin $plugin) {
		//first check, if plugin is installed
		if (!PluginInstaller::isPluginInstalled($plugin->getName())) {
			return array(
				'error' => "plugin_not_installed"
			);
		}

		$installer = new PluginInstaller($plugin);

		//try to uninstall plugin
		return $installer->uninstall();
	}

	protected function upgradePlugin (Plugin $plugin) {
		//first check, if plugin is installed
		if (!PluginInstaller::isPluginInstalled($plugin->getName())) {
			return array(
				'error' => "plugin_not_installed"
			);
		}

		$installer = new PluginInstaller($plugin);

		//try to uninstall plugin
		return $installer->upgrade();
	}

	public function listRequiredPermissions(): array {
		return array("can_install_plugins");
	}

}

?>
