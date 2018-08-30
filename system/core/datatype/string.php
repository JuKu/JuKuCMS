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
 * Date: 03.04.2018
 * Time: 23:33
 */

class DataType_String extends DataType_Base {

	public function getFormCode(): string {
		return "<input type=\"text\" name=\"" . $this->getInputName() . "\" value=\"" . $this->getValue() . "\" class=\"form-control\" />";
	}

	public function validate(string $value): bool {
		$val = new Validator_String();
		return $val->isValide($value);
	}

	protected function saveAsync($value) {
		Settings::setAsync($this->getKey(), (string) $value);
	}

}

?>
