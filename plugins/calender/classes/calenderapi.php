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
 * Time: 22:51
 */

namespace Plugin\Calender;

use User;
use IllegalStateException;

class CalenderApi {

	public static function listMyCalenderIDs () : array {
		$res = array();

		$array = array();

		$rows = Calenders::listMyCalenderIDs(User::current()->getID());

		foreach ($rows as $calenderID=>$row) {
			$array[$calenderID] = $row['value'];
		}

		$res['calender_ids'] = $array;

		return $res;
	}

	public static function listMyCalenders () : array {
		$res = array();

		$calenders = array();

		foreach (Calenders::listMyCalenders(User::current()->getID()) as $calender) {
			$calender = Calender::castCalender($calender);

			$calenders[] = array(
				'id' => $calender->getID(),
				'title' => $calender->getTitle(),
				'description' => $calender->getDescription(),
				'type' => $calender->getType(),
				'permission' => $calender->getPermission()
			);
		}

		$res['calenders'] = $calenders;

		return $res;
	}

	public static function listAllEvents () : array {
		$res = array();

		if (!isset($_REQUEST['calenderID']) || empty($_REQUEST['calenderID'])) {
			$res['status'] = 400;
			$res['error'] = "No parameter 'calenderID' is set. Right usage: api.php?method=list-all-calender-events&calenderID=<CalenderID>";

			return $res;
		}

		$calenderID = intval($_REQUEST['calenderID']);

		$only_current_events = false;

		if (isset($_REQUEST['only_current'])) {
			$only_current_events = true;
		}

		//create and load calender
		$calender = new Calender();

		try {
			$calender->load($calenderID);
		} catch (IllegalStateException $e) {
			$res['status'] = 404;
			$res['error'] = "Couldnt found calender with id '" . $calenderID . "'!";

			return $res;
		}

		$events = array();

		foreach ($calender->listAllEvents($only_current_events) as $event) {
			//cast event
			$event = Event::castEvent($event);

			$events[] = array(
				'id' => $event->getID(),
				'calenderID' => $event->getCalenderID(),
				'title' => $event->getTitle(),
				'description' => $event->getDescription(),
				'image' => $event->getImage(),
				'all_day' => $event->isAllDay(),
				'from' => $event->getFromTimestamp(),
				'to' => $event->getToTimestamp(),
				'location' => $event->getLocation(),
				'color' => $event->getColor()
			);
		}

		$res['events'] = $events;

		return $res;
	}

}

?>
