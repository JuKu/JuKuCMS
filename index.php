<?php

$start_time = microtime(true);

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

error_reporting(E_ALL);

require("system/core/init.php");

//throw event
Events::throwEvent("start_session");

//start session
session_start();

//use gzip compression
ob_start();

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

//benchmark code
if (ACTIVATE_BENCHMARK) {
	echo "<!-- page was generated in " . $exec_time . " seconds -->";
}

//flush gzip cache
ob_end_flush();

?>