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
 * Date: 20.03.2018
 * Time: 16:49
 */

class Validator_Color implements Validator_Base {

	//https://code.hyperspatial.com/all-code/php-code/verify-hex-color-string/

	public function isValide ($value): bool {
		if(preg_match('/^#[a-f0-9]{6}$/i', $value)) { //hex color is valid
			//Verified hex color
			return true;
		} else if(preg_match('/^[a-f0-9]{6}$/i', $value)) {
			//Check for a hex color string without hash 'c1c2b4'
			//hex color is valid

			//$fix_color = '#' . $value;

			return true;
		}

		return false;
	}

	public function validate ($value) {
		if(preg_match('/^#[a-f0-9]{6}$/i', $value)) { //hex color is valid
			//Verified hex color
			return $value;
		} else if(preg_match('/^[a-f0-9]{6}$/i', $value)) {
			//Check for a hex color string without hash 'c1c2b4'
			//hex color is valid

			$fix_color = '#' . $value;

			return $fix_color;
		}

		throw new Exception("method isnt implemented yet.");
	}

}

?>
