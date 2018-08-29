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
 * Date: 29.08.2018
 * Time: 14:53
 */

class DataType_SelectBox extends DataType_Base {

	public function getFormCode(): string {
		$code = "<select name=\"" . $this->getInputName() . "\" class=\"form-control select2\" style=\"width: 100%;\">";

		foreach ($this->getDatatypeParams() as $option) {
			$code .= "<option" . ($option === $this->getValue() ? " selected=\"selected\"" : "") . ">" . $option . "</option>";
		}

		$code .= "</select>";
		return $code;
	}

	public function validate(): bool {
		// TODO: Implement validate() method.
	}

}

?>
