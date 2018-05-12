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
 * Date: 24.04.2018
 * Time: 23:00
 */

class LDAPClient {

	//host and port
	protected $host = "";
	protected $port = 389;

	//flag, if uri is used instead of host
	protected $uri_used = false;
	protected $uri = "";

	protected $conn = null;
	protected $res = null;

	protected $ldap_config = array();

	//flag, if connection is readonly
	protected $readonly = false;

	public function __construct (string $host = "", int $port = 0, bool $ssl = false) {
		$ldap_config = array(
			'enabled' => true,
			'ssl' => $ssl
		);

		if (empty($host)) {
			//load local config
			if (!file_exists(CONFIG_PATH . "ldap.php")) {
				throw new IllegalStateException("No ldap configuration file config/ldap.php exists!");
			}

			//override $ldap_config
			require(CONFIG_PATH . "ldap.php");

			//check, if ldap is enabled
			if ($ldap_config['enabled'] == false) {
				throw new IllegalStateException("LDAP is disabled.");
			}

			$this->host = $ldap_config['host'];
			$this->port = intval($ldap_config['port']);

			$this->readonly = boolval($ldap_config['readonly']);
		} else {
			$this->host = $host;
			$this->port = $port;
		}

		//check, if SSL is enabled
		if ($ldap_config['ssl'] == true) {
			//use OpenLDAP 2.x.x URI instead of host
			$this->uri = "ldaps://" . $this->host . ":" . $this->port;

			//set flag, that uri is used
			$this->uri_used = true;
		}

		//check, if host / uri is valide (this statement doesnt connect to server!) - see also http://php.net/manual/de/function.ldap-connect.php
		if ($this->uri_used) {
			$this->conn = ldap_connect($this->uri);
		} else {
			$this->conn = ldap_connect($this->host, $this->port);
		}

		if ($this->conn === FALSE) {
			throw new IllegalStateException("LDAP connection parameters (host or port) are invalide.");
		}

		//set ldap params
		if (isset($ldap_config['params'])) {
			foreach ($ldap_config['params'] as $key=>$value) {
				// configure ldap params
				ldap_set_option($this->conn,$key, $value);
			}
		}

		$this->ldap_config = $ldap_config;
	}

	public function bind (string $username = null, string $password = null) : bool {
		if (is_null($username) && isset($this->ldap_config['user'])) {
			$username = $this->ldap_config['user'];
			$password = $this->ldap_config['password'];
		}

		if ($this->conn === FALSE) {
			throw new IllegalStateException("ldap connection check failed.");
		}

		//connect and bind to ldap server
		if (!is_null($username)) {
			//with authentification
			$this->res = ldap_bind($this->conn, $username, $password);
		} else {
			//anonymous binding
			$this->res = ldap_bind($this->conn);
		}

		return $this->res !== FALSE;
	}

	public function getConnection () {
		return $this->conn;
	}

	public function disconnect () {
		//disconnect from ldap server
		ldap_unbind($this->conn);
	}

}

?>
