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
 * Date: 22.03.2018
 * Time: 00:49
 */

class Validator_Int implements Validator_Base {

	public function isValide ($value): bool {
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	public function validate ($value) : int {
		return intval($value);
	}

	public static function get (string $value) : string {
		$obj = new Validator_Int();
		return $obj->validate($value);
	}

}

?>
