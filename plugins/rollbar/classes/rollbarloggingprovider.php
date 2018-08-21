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
 * Date: 21.08.2018
 * Time: 20:25
 */

namespace Plugin\Rollbar;

use \Rollbar\Rollbar;
use \Rollbar\Payload\Level;
use LogProvider;
use Preferences;
use LogLevel;

if (!defined('ROLLBAR_SDK_DIR')) {
	define('ROLLBAR_SDK_DIR', dirname(__FILE__) . "/../rollbar-php-1.6.2/");
}

class RollbarLoggingProvider implements LogProvider {

	protected $logs = array();

	/**
	 * initialize logging provider
	 */
	public function init () {
		$preferences = new Preferences("plugin_rollbar");
		$access_token = $preferences->get("access_token", "none");
		$environment = $preferences->get("environment", "development");

		if ($access_token === "none") {
			//access token wasnt set yet
			return;
		}

		Rollbar::init(
			array(
				'access_token' => $access_token,
				'environment' => $environment,

				// optional - path to directory your code is in. used for linking stack traces.
				'root' => ROOT_PATH,
				'included_errno' => E_ALL//Note: If you wish to log E_NOTICE errors make sure to pass 'included_errno' => E_ALL to Rollbar::init
			),
			true,
			true,
			true
		);
	}

	/**
	 * log message
	 */
	public function log (string $level, string $message, $args = array()) {
		$this->logs[] = array(
			'level' => $level,
			'message' => $message,
			'args' => $args
		);

		if ($level === LogLevel::ERROR || $level === LogLevel::CRITICAL) {
			//send it directly to rollbar server
			$this->send();
		}
	}

	/**
	 * lazy logging - after generating page write logs to file or send them to server
	 */
	public function send () {
		if (empty($this->logs)) {
			//we dont have to send anything
			return;
		}

		foreach ($this->logs as $entry) {
			//send log to server
			$response = Rollbar::log(
				$entry['level'],
				$entry['message'],
				$entry['args'] // key-value additional data
			);

			if (!$response->wasSuccessful()) {
				throw new \IllegalStateException('logging with Rollbar failed');
			}
		}

		//clear logs array
		$this->logs = array();
	}

	public static function addRollbarClassloader (array $params) {
		//add classloader for facebook sdk
		ClassLoader::addLoader("Rollbar", function (string $class_name) {
			$path = ROLLBAR_SDK_DIR . str_replace("\\", "/", $class_name) . ".php";

			if (file_exists($path)) {
				require($path);
			} else {
				echo "Couldnt load rollbar class: " . $class_name . " (expected path: " . $path . ")!";
				exit;
			}
		});
	}

}

?>
