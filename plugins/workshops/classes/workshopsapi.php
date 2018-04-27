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
 * Date: 27.04.2018
 * Time: 20:48
 */

class WorkshopsApi {

	public static function listAllWorkshops () : array {
		$res = array();

		//read workshops directly from database
		$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}plugin_workshops_workshops` ORDER BY `order`; ");

		$array = array();

		foreach ($rows as $row) {
			$array[] = array(
				'id' => $row['id'],
				'title' => utf8_encode($row['title']),
				'description' => utf8_encode($row['description']),
				'image' => $row['image'],
				'date' => $row['date'],
				'time' => $row['time'],
				'interval' => $row['interval'],
				'location' => $row['location'],
				'responsible' => $row['responsible'],
				'order' => $row['order']
			);
		}

		$res['workshops'] = $array;

		return $res;
	}

}

?>