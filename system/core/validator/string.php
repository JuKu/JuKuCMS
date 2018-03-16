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
 * This class is responsible for validating strings
 *
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 16.03.2018
 * Time: 14:20
 */
class Validator_String implements Validator_Base {

	public function isValide($value) : bool {
		$validated_str = $this->validate($value);

		return strcmp($value, $validated_str);
	}

	public function validate($value) : string {
		//escape string
		$value = Database::getInstance()->escape($value);

		//remove html entities
		$value = htmlentities($value, null, "UTF-8");//htmlspecialchars($value, ENT_QUOTES, "UTF-8");

		return $value;
	}

	public static function get (string $value) : string {
		$obj = new Validator_String();
		return $obj->validate($value);
	}

}

?>
