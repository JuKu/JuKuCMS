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

namespace Plugin\FacebookApi;

use ClassLoader;
use Preferences;
use Facebook\Facebook;

if (!defined('FB_SDK_PATH')) {
	define('FB_SDK_PATH', PLUGIN_PATH . "facebookapi/facebook-sdk/");
}

class FacebookApi {

	protected $appID = "";
	protected $secret = "";

	//facebook sdk instance
	protected $fb = null;

	public function __construct() {
		//load preferences
		$prefs = new Preferences("plugin_facebookapi");

		$this->appID = $prefs->get("appID", "");
		$this->secret = $prefs->get("secret", "");

		$config = array();
		$config['app_id'] = $this->appID;
		$config['app_secret'] = $this->secret;
		$config['default_graph_version'] = 'v2.2';

		$this->fb = new Facebook($config);
	}

	public static function addFBClassloader (array $params) {
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

	public function getSDK () : Facebook {
		return $this->fb;
	}

	public function getPage (string $name) : Page {
		$page = new Page($this->fb);
		$page->loadPage($name);

		return $page;
	}

	public function getAccessToken () : string {
		return $this->appID . "|" . $this->secret;
	}

}

?>
