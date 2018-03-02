<?php

$start_time = microtime(true);

//start session
session_start();

//use gzip compression
ob_start();

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

require("system/core/init.php");

//include and load ClassLoader
require("system/core/classes/classloader.php");
ClassLoader::init();

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

//benchmark code
if (ACTIVATE_BENCHMARK) {
	echo "<!-- page was generated in " + $exec_time + " seconds -->";
}

//flush gzip cache
ob_end_flush();

?>