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
 * Time: 21:49
 */

class Validator_Username implements Validator_Base {

	public function isValide($value): bool {
		//https://stackoverflow.com/questions/4383878/php-username-validation

		if(preg_match("/^[" . Settings::get("username_regex", "a-zA-Z0-9\.\-") . "]{" . Settings::get("username_min_length", 4) . "," . Settings::get("username_max_length", 20) . "}$/", $value)) { // for english chars + numbers only
			return true;
		}

		return false;
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
