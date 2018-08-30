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
 * Time: 14:50
 */

class DataType_LangChooser extends DataType_Base {

	public function getFormCode(): string {
		$langs = Lang::listSupportedLangTokens();

		$code = "<select name=\"" . $this->getInputName() . "\">";

		foreach ($langs as $lang) {
			$code .= "<option value=\"" . $lang . "\"" . ($lang === $this->getValue() ? " selected=\"selected\"" : "") . ">" . $lang . "</option>";
		}

		$code .= "</select>";
		return $code;
	}

	public function validate(string $value): bool {
		//check, if lang is in list
		$langs = Lang::listSupportedLangTokens();

		foreach ($langs as $lang) {
			if ($value == $lang) {
				//language is suppored
				return true;
			}
		}

		return false;
	}

	protected function saveAsync($value) {
		Settings::setAsync($this->getKey(), (string) $value);
	}
}

?>
