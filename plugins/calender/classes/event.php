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
 * Date: 27.04.2018
 * Time: 01:11
 */

namespace Plugin\Calender;

class Event {

	protected $row = null;

	public function __construct(array $row) {
		$this->row = $row;
	}

	public function getID () : int {
		return $this->row['id'];
	}

	public function getCalenderID () : int {
		return $this->row['calenderID'];
	}

	public function getTitle () : string {
		return utf8_encode($this->row['title']);
	}

	public function getDescription () : string {
		return utf8_encode($this->row['description']);
	}

	public function getPriceInfo () : string {
		return (isset($this->row['price_info']) ? $this->row['price_info'] : "");
	}

	public function hasImage () : bool {
		return $this->row['image'] !== "none";
	}

	public function getImage () : string {
		return $this->row['image'];
	}

	public function isAllDay () : bool {
		return $this->row['all_day'] == 1;
	}

	public function getFromTimestamp () : string {
		return $this->row['from_date'];
	}

	public function getToTimestamp () : string {
		return $this->row['to_date'];
	}

	public function getLocation () : string {
		return utf8_encode($this->row['location']);
	}

	public function hasColor () : bool {
		return $this->row['color'] !== "none";
	}

	public function getColor () : string {
		return $this->row['color'];
	}

	public function isActivated () : bool {
		return $this->row['activated'] == 1;
	}

	public static function castEvent (Event $event) : Event {
		return $event;
	}

}

?>
