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
 * Date: 03.04.2018
 * Time: 20:11
 */

define('DWOO_PATH', ROOT_PATH . "system/packages/com.jukusoft.cms.dwoo/dwoo/lib/");

class DwooAutoloader {

	public static function loadClass (string $class_name) {
		$class_name = str_replace("\\", "/", $class_name);

		if (file_exists(DWOO_PATH . $class_name . ".php")) {
			require(DWOO_PATH . $class_name . ".php");
		} else {
			echo "Cannot load Dwoo Template Engine class '" . $class_name . "'!";
		}
	}

}

?>
