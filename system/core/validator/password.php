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
 * Date: 05.04.2018
 * Time: 22:01
 */

class Validator_Password implements Validator_Base {

	public function isValide($value): bool {
		$valide = true;

		//throw event, so plugins like pwned can interact
		Events::throwEvent("validate_password", array(
			'password' => &$value,
			'valide' => &$valide
		));

		if (!$valide) {
			return false;
		}

		if (strlen($value) < Settings::get("password_min_length", 8)) {
			return false;
		}

		if (strlen($value) > Settings::get("password_max_length", 64)) {
			//more than 64 characters arent supported
			return false;
		}

		//everything is allowed
		return true;
	}

	public function validate($value) {
		if ($this->isValide($value)) {
			return $value;
		} else {
			throw new SecurityException("username is not valide '" . htmlentities($value) . "'!");
		}
	}

}

?>
