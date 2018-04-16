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

class Tasks {

	public static function schedule (int $limit = 3) {
		//execute overdued tasks
		foreach (self::getOverduedTasks($limit) as $task) {
			//cast task
			$task = Task::cast($task);

			//execute task
			$task->execute();

			//update last execution timestamp
			$task->setLastExecution();
		}
	}

	public static function getOverduedTasks (int $limit = 10) : array {
		$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}tasks` WHERE (DATE_ADD(`last_execution`, INTERVAL `interval` MINUTE) < NOW() OR `last_execution` = '0000-00-00 00:00:00') AND `activated` = '1' LIMIT 0, :limit; ", array(
			'limit' => array(
				'type' => PDO::PARAM_INT,
				'value' => $limit
			)
		));

		$tasks = array();

		foreach ($rows as $row) {
			$tasks[] = new Task($row);
		}

		return $tasks;
	}

}

?>
