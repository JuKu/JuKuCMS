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
 * Date: 29.04.2018
 * Time: 17:01
 */

namespace Plugin\Workshops;

use ClassLoader;

if (!defined('FB_SDK_PATH')) {
	define('FB_SDK_PATH', PLUGIN_PATH . "facebookapi/facebook-sdk/");
}

class FacebookApi {

	public static function addFBClassloader () {
		//add classloader for facebook sdk
		ClassLoader::addLoader("Facebook", function (string $class_name) {
			$path = FB_SDK_PATH . str_replace("\\", "/", $class_name) . ".php";

			if (file_exists($path)) {
				require($path);
			} else {
				echo "Couldnt load facebook class: " . $class_name . " (expected path: " . $path . ")!";
				exit;
			}
		});
	}

}

?>
