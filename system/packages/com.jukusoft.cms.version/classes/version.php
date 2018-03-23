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
 * Date: 23.03.2018
 * Time: 15:46
 */

class Version {

	protected static $instance = null;

	protected $version_data = array();

	public function __construct() {
		//
	}

	public function load ($version_path) {
		//because unserialize is 2 times faster than json_decode, we cache this value
		if (Cache::contains("version", "version_" . $version_path)) {
			$this->version_data = Cache::get("version", "version_" . $version_path);
		} else {
			if (!file_exists($version_path)) {
				echo "Version file doesnt exists: " . $version_path;
				exit;
			}

			$array = json_decode($version_path, true);

			//cache
			Cache::put("version", "version_" . $version_path, $array);

			$this->version_data = $array;
		}
	}

	public function getVersion () : string {
		if (!isset($this->version_data['version'])) {
			var_dump($this->version_data);

			echo "Version not found!";
			exit;
		}

		return $this->version_data['version'];
	}

	public function getBuildNumber () : string {
		return $this->version_data['build'];
	}

	public static function &current () : Version {
		if (self::$instance == null) {
			self::$instance = new Version();
			self::$instance->load(ROOT_PATH . "system/core/version.json");
		}

		return self::$instance;
	}

}

?>
