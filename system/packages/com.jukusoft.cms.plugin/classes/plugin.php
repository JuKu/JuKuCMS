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
 * Time: 12:31
 */

class Plugin {

	//directory name of plugin
	protected $name = "";

	//database row
	protected $row = array();

	protected $json_data = null;

	/**
	 * default constructor
	 *
	 * @param string $name directory name of plugin
	 * @param array $row optional database row from plugin
	 */
	public function __construct(string $name, array $row = array()) {
		$this->name = $name;
		$this->row = $row;
	}

	/**
	 * load plugin.json file
	 */
	public function load () {
		$file_path = PLUGIN_PATH . $this->name . "/plugin.json";

		//check, if file exists
		if (!file_exists($file_path)) {
			throw new IllegalStateException("plugin.json for plugin '" . $this->name . "' does not exists (expected path: '" . $file_path . "')!");
		}

		$this->json_data = json_decode(file_get_contents($file_path), true);
	}

	/**
	 * get directory name of plugin
	 *
	 * @return string directory name of plugin
	 */
	public function getName () : string {
		return $this->name;
	}

}

?>
