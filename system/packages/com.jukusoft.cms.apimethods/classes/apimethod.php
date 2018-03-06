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

class ApiMethod {

	protected $apimethods = array();
	protected $method = array();

	public function __construct () {
		//
	}

	public function loadApiMethods () {

		if (Cache::contains("apimethods", "apimethods")) {
			$this->apimethods = Cache::get("apimethods", "apimethods");
		} else {
			$rows = (Array) DataBase::getInstance()->listRows("SELECT * FROM `{prefix}api_methods` WHERE `activated` = '1'; ");

			foreach ($rows as $row) {
				$row = (Array) $row;
				$this->apimethods[$row['api_method']] = $row;
			}

			Cache::put("apimethods", "apimethods", $this->apimethods);
		}

	}

	public function loadMethod ($method) {
		if (isset($this->apimethods[$method])) {
			$this->method = $this->apimethods[$method];
		}
	}

	public function executeApiMethod () {

		if (!$this->method) {
			exit;
		}

		if ($this->method['response_type'] != "specific") {
			header("Content-Type: " . $this->method['response_type']);
		}

		$classname = $this->method['classname'];
		$method = $this->method['method'];

		$args = array();
		Events::throwEvent("apimethods", array('method' => $this->method, 'args' => &$args));

		$result = call_user_func(array($classname, $method), $args);

		echo $result;
	}

}

?>
