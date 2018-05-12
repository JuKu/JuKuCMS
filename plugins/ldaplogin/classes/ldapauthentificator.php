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
 * Date: 12.05.2018
 * Time: 15:29
 */

namespace Plugin\LDAPLogin;

use IAuthentificator;

class LDAPAuthentificator implements IAuthentificator {

	public function __construct() {
		//
	}

	/**
	 * check password of user
	 *
	 * @param $username string name of user
	 * @param $password string password of user
	 *
	 * @return true, if password is correct
	 */
	public function checkPassword(string $username, string $password): bool {
		// TODO: Implement checkPassword() method.
	}

	/**
	 * check, if username exists
	 *
	 * @param $username string name of user
	 *
	 * @return true, if username exists
	 */
	public function exists(string $username): bool {
		// TODO: Implement exists() method.
	}

}

?>
