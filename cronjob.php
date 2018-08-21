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

$start_time = microtime(true);

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

error_reporting(E_ALL);

require("system/core/init.php");

if (!Settings::get("cronjob_enabled", true)) {
	//dont execute cronjob
	exit;
}

$auth_key = Settings::get("cronjob_auth_key", "");

if (!empty($auth_key)) {
	//auth key is required
	if (!isset($_REQUEST['auth_key']) || $_REQUEST['auth_key'] !== $auth_key) {
		echo "No auth key set or auth key is wrong.";
		ob_end_flush();

		Logger::log(LogLevel::WARNING, "No auth key set or auth key is wrong.");

		exit;
	}
}

Logger::log(LogLevel::INFO, "call cronjon.php");

Events::throwEvent("init_cronjob");

//execute tasks (task schedular)
Tasks::schedule(Settings::get("max_tasks_on_cronjob", 10));

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

echo "<!-- cronjob executed in " . $exec_time . " seconds -->";

ob_end_flush();
flush();

//send logs to server
if (LOGGING_ENABLED) {
	Logger::send();
}

?>
