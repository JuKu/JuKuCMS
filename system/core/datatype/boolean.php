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
 * Time: 14:52
 */

class DataType_Boolean extends DataType_Base {

	public function getFormCode(): string {
		//return "<input type=\"checkbox\" name=\"" . $this->getInputName() . "\" value=\"" . $this->getTitle() . "\"" . ($this->getValue() == true ? " checked" : "") . " />";

		return "	<label>
						<input type=\"checkbox\" name=\"" . $this->getInputName() . "\" value=\"enabled\" class=\"flat-green\"" . ($this->getValue() == true ? " checked" : "") . " />
                	</label>";
	}

	public function val () : bool {
		//if key isn't set this means checkbox isn't checked
		return true;
	}

	public function validate(string $value): bool {
		return true;
	}

	protected function saveAsync($value) {
		Settings::setAsync($this->getKey(), (boolean) (isset($_REQUEST[$this->getInputName()]) ? true : false));
	}
}

?>
