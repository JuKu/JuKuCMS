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
			$rows = (Array) DataBase::getInstance()->listRows("SELECT * FROM `{praefix}api_methods` WHERE `activated` = '1'; ");

			foreach ($rows as $row) {
				$row = (Array) $row;
				$this->apimethods[$row['api_method']] = $row;
			}

			Cache::put("apimethods", "apimethods", $this->apimethods);
		}

	}

	public function loadMethod ($method) : bool {
		if (isset($this->apimethods[$method])) {
			$this->method = $this->apimethods[$method];

			return true;
		}

		return false;
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

		if (is_array($result)) {
			if (!isset($result['status'])) {
				$result['status'] = 200;
			}

			print_r(json_encode($result));
			echo "test";
			exit;

			echo json_encode($result);
		} else {
			echo $result;
		}
	}

	public static function addMethod (string $api_method, string $class_name, string $method, string $owner = "system", string $response_type = "") {
		//add method to database
		Database::getInstance()->execute("INSERT INTO `{praefix}api_methods` (
			`api_method`, `classname`, `method`, `response_type`, `owner`, `activated`
		) VALUES (
			:api_method, :class_name, :method, :response_type, :owner, '1'
		) ON DUPLICATE KEY UPDATE `classname` = :class_name, `method` = :method, `response_type` = :response_type, `owner` = :owner, `activated` = '1'; ", array(
			'api_method' => $api_method,
			'class_name' => $class_name,
			'method' => $method,
			'response_type' => $response_type,
			'owner' => $owner
		));

		//clear cache
		Cache::clear("apimethods");
	}

	public static function deleteMethod (string $api_method) {
		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}api_methods` WHERE `api_method` = :api_method; ", array(
			'api_method' => $api_method
		));

		//clear cache
		Cache::clear("apimethods");
	}

	public static function deleteMethodsByOwner (string $owner) {
		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}api_methods` WHERE `owner` = :owner; ", array(
			'owner' => $owner
		));

		//clear cache
		Cache::clear("apimethods");
	}

}

?>
