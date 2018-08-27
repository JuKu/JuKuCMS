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
 * Date: 08.04.2018
 * Time: 17:50
 */

abstract class PluginInstaller_Plugin {

	public abstract function install (Plugin $plugin, array $install_json) : bool;

	public abstract function uninstall (Plugin $plugin, array $install_json) : bool;

	public abstract function upgrade (Plugin $plugin, array $install_json) : bool;

	public function getPriority () : int {
		return 10;
	}

	public static function cast (PluginInstaller_Plugin $plugin) : PluginInstaller_Plugin {
		return $plugin;
	}

}

?>
