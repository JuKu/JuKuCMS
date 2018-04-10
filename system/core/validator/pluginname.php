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
 * Date: 10.04.2018
 * Time: 16:50
 */

class Validator_PluginName implements Validator_Base {

	protected static $instance = null;

	public function isValide($value): bool {
		return $value === $this->validate($value);
	}

	public function validate($value) {
		//remove all characters except except a-z, A-Z and 0-9
		return preg_replace("/[^a-zA-Z_\-0-9]+/", "", $value);
	}

	public static function getInstance () : Validator_PluginName {
		if (self::$instance == null) {
			self::$instance = new Validator_PluginName();
		}

		return self::$instance;
	}

}

?>
