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
 * Date: 09.04.2018
 * Time: 10:50
 */

class EventInstaller extends PluginInstaller_Plugin {

	public function install(Plugin $plugin, array $install_json): bool {
		//add events if absent
		return $this->addEvents($plugin, $install_json);
	}

	public function uninstall(Plugin $plugin, array $install_json): bool {
		//check, if events are specified
		if (isset($install_json['events']) && is_array($install_json['events'])) {
			//remove all events from this plugin
			Events::removePluginEvents($plugin->getName());
		}

		return true;
	}

	public function upgrade(Plugin $plugin, array $install_json): bool {
		//TODO: remove events, which arent longer in install json

		//add events if absent
		return $this->addEvents($plugin, $install_json);
	}

	protected function addEvents (Plugin $plugin, array $install_json) : bool {
		//check, if events are specified
		if (isset($install_json['events']) && is_array($install_json['events'])) {
			//create events
			foreach ($install_json['events'] as $event) {
				if (!is_array($event)) {
					throw new IllegalStateException("Invalide install.json, key 'events' has to contains arrays!");
				}

				$event_name = $event['event'];

				$type = "class_static_method";

				//get type
				if (isset($event['type'])) {
					$type = strtolower($event['type']);
				}

				switch ($type) {
					case "file":
						throw new Exception("UnuspportedOperationException: event type 'file' is not supported yet.");
						break;
					case "function":
						throw new Exception("UnuspportedOperationException: event type 'function' is not supported yet.");
						break;
					case "class_static_method":
						Events::addEventClass($event_name, $event['class'], $event['method'], $plugin->getName());
						break;
					default:
						throw new IllegalArgumentException("Unknown event type: " . $type . " (event: " . $event_name . ") in install.json of plugin '" . $plugin->getName() . "'!");
						break;
				}
			}
		}

		return true;
	}

}

?>
