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
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 26.04.2018
 * Time: 21:23
 */

namespace Plugin\Calender;

use Cache;
use Database;
use PDO;
use IllegalStateException;

class Calender {

	protected $calenderID = -1;
	protected $row = null;
	protected $user_row = null;

	public function __construct (array $row = null, array $user_row = null) {
		if (!is_null($row) && !empty($row)) {
			$this->calenderID = $row['id'];
			$this->row = $row;
		}

		if (!is_null($user_row) && !empty($user_row)) {
			$this->user_row = $user_row;
		}
	}

	public function load (int $calenderID) {
		if ($calenderID <= 0) {
			throw new \IllegalArgumentException("calenderID has to be greater than 0.");
		}

		if (Cache::contains("plugin-calender", "calender-" . $calenderID)) {
			$this->row = Cache::get("plugin-calender", "calender-" . $calenderID);
		} else {
			//get calender from database
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}plugin_calender_calenders` WHERE `id` = :calenderID; ", array(
				'calenderID' => array(
					'type' => PDO::PARAM_INT,
					'value' => $calenderID
				)
			));

			if (!$row) {
				throw new IllegalStateException("calender with id '" . $calenderID . "' doesnt exists.");
			}

			$this->row = $row;

			//put row to cache
			Cache::put("plugin-calender", "calender-" . $calenderID, $row);
		}

		$this->calenderID = $row['id'];
	}

	public function getID () : int {
		return $this->calenderID;
	}

	public function getTitle () : string {
		return $this->row['title'];
	}

	public function getDescription () : string {
		return $this->row['description'];
	}

	public function getType () : string {
		return $this->row['type'];
	}

	public function getPermission () : string {
		if ($this->user_row == null || empty($this->user_row)) {
			throw new IllegalStateException("user row wasnt set in constructor.");
		}

		return $this->user_row['value'];
	}

	public function listAllEvents () : array {
		//
	}

	public static function castCalender (Calender $calender) : Calender {
		return $calender;
	}

}

?>
