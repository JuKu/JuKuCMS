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
use LDAPClient;

class LDAPAuthentificator implements IAuthentificator {

	public function __construct() {
		//
	}

	/**
	 * check password of user and import user, if neccessary
	 *
	 * @param $username string name of user
	 * @param $password string password of user
	 *
	 * @return userID or -1, if credentials are wrong
	 */
	public function checkPasswordAndImport(string $username, string $password): int {
		//https://samjlevy.com/php-ldap-login/

		//Free test ldap server: https://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/

		//https://www.experts-exchange.com/questions/23969673/Using-PHP-with-LDAP-to-connect-to-Active-Directory-on-another-machine.html

		$ldap_client = new LDAPClient();

		//try to login user on ldap server
		$res = $ldap_client->bind($username, $password);

		if (!$res) {
			echo "credentials are wrong.";
			exit;

			//user doesnt exists or credentials are wrong
			//return -1;
		}

		echo "user exists";
		print_r($res);

		//unbind
		$ldap_client->unbind();

		return -1;

		exit;
	}
}

?>
