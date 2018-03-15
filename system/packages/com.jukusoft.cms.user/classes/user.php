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
 * Date: 15.03.2018
 * Time: 15:46
 */

class User {

	protected static $instance = null;

	public function __construct() {
		//
	}

	public function &load (int $userID = -1) {
		//
	}

	/**
	 * get instance of current (logged in / guest) user
	 */
	public static function &current () : User {
		if (self::$instance == null) {
			self::$instance = new User();
			self::$instance->load();
		} else {
			return self::$instance;
		}
	}

}

?>
