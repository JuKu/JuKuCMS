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

use LogProvider;

class RollbarLoggingProvider implements LogProvider {

	/**
	 * initialize logging provider
	 */
	public function init () {
		// TODO: Implement init() method.
	}

	/**
	 * log message
	 */
	public function log (string $level, string $message, $args = array()) {
		// TODO: Implement log() method.
	}

	/**
	 * lazy logging - after generating page write logs to file or send them to server
	 */
	public function send () {
		// TODO: Implement send() method.
	}

}

?>
