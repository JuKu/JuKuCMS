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
 * Date: 16.04.2018
 * Time: 20:29
 */

class Task {

	protected $row = null;

	public function __construct(array $row = array()) {
		if (!empty($row)) {
			$this->row = $row;
		}
	}

	public function load (int $id) {
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}tasks` WHERE `id` = :id; ", array(
			'id' => array(
				'type' => PDO::PARAM_INT,
				'value' => $id
			)
		));

		if (!$row || empty($row)) {
			throw new IllegalArgumentException("Task with id '" . $id . "' doesnt exists in database!");
		}

		$this->row = $row;
	}

	public function getID () : int {
		return $this->row['id'];
	}

	public function getTitle () : string {
		return $this->row['title'];
	}

	public function getType () : string {
		return $this->row['type'];
	}

	public function getTypeParams () : array {
		return $this->row['type_params'];
	}

	public function getFile () : string {
		if (!PHPUtils::strEqs($this->getType(), "FILE")) {
			throw new IllegalStateException("Task with id '" . $this->getID() . "' is not of type 'FILE', so cannot return file path in method Task::getFile().");
		}

		return $this->getTypeParams();
	}

	public function getParams () : array {
		return unserialize($this->row['params']);
	}

	/**
	 * try to lock task
	 */
	public function lock () : bool {
		if (Cache::contains("task-locks", "task-" . $this->getID)) {
			return false;
		}

		Cache::put("task-locks", "task-" . $this->getID, time());

		return true;
	}

	public function unlock () {
		Cache::clear("task-locks", "task-" . $this->getID);
	}

	public function execute () : bool {
		//first try to lock task, so other scripts cannot execute same task at same time
		if (!$this->lock()) {
			return false;
		}

		try {
			$array = explode(":", $this->getTypeParams());
			$params = $this->getParams();

			switch (strtolower($this->getType())) {
				case "file":
					$file = $this->getFile();

					//check, if file exists
					if (file_exists(ROOT_PATH . $file)) {
						require(ROOT_PATH . $file);
					} else {
						throw new IllegalStateException("required file for task with id '" . $this->getID() . "' not found: " . $file);
					}

					break;
				case "function":
					$class_method = $array[0];

					call_user_func($class_method, $params);
					break;
				case "class_static_method":
					$class_name = $array[0];
					$class_method = $array[1];

					call_user_func(array($class_name, $class_method), $params);
					break;
				default:
					throw new IllegalStateException("unknown task type '" . $this->getType() . "' for task id '" . $this->getID() . "'!");
					break;
			}
		} catch (Exception $e) {
			echo $e->getTraceAsString();
		} finally {
			//unlock task
			$this->unlock();
		}

		return true;
	}

	/**
	 * set last execution timestamp to now
	 */
	public function setLastExecution () {
		//update database, set last execution timestamp to now
		Database::getInstance()->execute("UPDATE `{praefix}tasks` SET `last_execution` = NOW() WHERE `id` = :id; ", array(
			'id' => array(
				'type' => PDO::PARAM_INT,
				'value' => $this->getID()
			)
		));
	}

	public function deleteAfterExecution () : bool {
		return $this->row['delete_after_execution'] == 1;
	}

	public function delete () {
		//delete from database
		Database::getInstance()->execute("DELETE FROM `{praefix}tasks` WHERE `id` = :id; ", array(
			'id' => array(
				'type' => PDO::PARAM_INT,
				'value' => $this->getID()
			)
		));
	}

	public static function cast (Task $task) : Task {
		return $task;
	}

}

?>
