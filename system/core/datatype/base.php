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
 * Time: 23:34
 */

abstract class DataType_Base {

	protected $row = array();
	protected $datatype_params = null;

	public function load (array $row, $datatype_params) {
		$this->row = $row;
		$this->datatype_params = $datatype_params;
	}

	/**
	 * @return array
	 */
	public function getRow (): array {
		return $this->row;
	}

	public function getDatatypeParams () {
		return $this->datatype_params;
	}

	public function getInputName () {
		return "setting_" . $this->row['key'];
	}

	public function getTitle () : string {
		if (is_array($this->datatype_params) && isset($this->datatype_params['checkbox_title'])) {
			return $this->datatype_params['checkbox_title'];
		}

		return $this->row['title'];
	}

	public function getDescription () : string {
		return $this->row['description'];
	}

	public function getValue () {
		return unserialize($this->row['value']);
	}

	public abstract function getFormCode () : string;

	public abstract function validate () : bool;

}

?>
