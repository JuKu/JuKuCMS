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

//throw event
Events::throwEvent("start_session");

//use gzip compression
ob_start();

$api_method = new ApiMethod();
$api_method->loadApiMethods();

$method = "";

if (isset($_REQUEST['method']) && !empty($_REQUEST['method'])) {
	$method = htmlentities($_REQUEST['method']);
}

//execute api method, if available
if (!empty($method)) {
	$apimethods->loadMethod($method);
	$apimethods->executeApiMethod();
} else {
	//print error message
	header("Content-Type: application/json");
	echo "{\"error\": \"No api method in request, correct call: api.php?method=<API_METHOD>\"}";
}

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

//flush gzip cache
ob_end_flush();


?>
