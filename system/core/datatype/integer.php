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
 * Time: 14:49
 */

class DataType_Integer extends DataType_Base {

	public function getFormCode(): string {
		$min = null;
		$max = null;
		$unit = null;

		if (is_array($this->getDatatypeParams())) {
			$array = $this->getDatatypeParams();

			if (isset($array['min'])) {
				$min = (int) $array['min'];
			}

			if (isset($array['max'])) {
				$max = (int) $array['max'];
			}

			if (isset($array['unit'])) {
				$unit = $array['unit'];
			}
		}

		return "<input type=\"number\" name=\"" . $this->getInputName() . "\" value=\"" . $this->getValue() . "\" step=\"1\"" . ($min != null ? " min=\"" . $min . "\"" : "") . "" . ($max != null ? " max=\"" . $max . "\"" : "") . " />" . ($unit != null ? " " . $unit : "");
	}

	public function validate(string $value): bool {
		// TODO: Implement validate() method.
	}

}

?>
