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
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 30.03.2018
 * Time: 18:19
 */

class Validator_Mail implements Validator_Base {

	protected static $instance = null;

	public function isValide ($value): bool {
		return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

	public function validate ($value) : string {
		if (!$this->isValide($value)) {
			throw new SecurityException("given mail '" . htmlentities($value) . "' isnt a valide mail.");
		}

		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	public static function get (string $value) : string {
		if (self::$instance == null) {
			self::$instance = new Validator_Mail();
		}

		return self::$instance->validate($value);
	}

}

?>
