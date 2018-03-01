<?php

//start session
session_start();

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

require("system/core/init.php");

//include and load ClassLoader
require("system/core/classes/classloader.php");
ClassLoader::init();

?>