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

if (PHP_MAJOR_VERSION < 7) {
	echo "CMS is required PHP 7.0.0 or greater! Please install PHP 7 (current version: " . PHP_VERSION . ").";
	ob_flush();
	exit;
}

register_shutdown_function(function () {
	//flush gzip cache
	ob_end_flush();
});

//define some constants
define('CACHE_PATH', ROOT_PATH . "cache/");
define('CONFIG_PATH', ROOT_PATH . "config/");
define('STORE_PATH', ROOT_PATH . "store/");
define('PACKAGE_PATH', ROOT_PATH . "system/packages/");
define('STYLE_PATH', ROOT_PATH . "styles/");

//include and load ClassLoader
require(ROOT_PATH . "system/core/classes/classloader.php");
ClassLoader::init();

//require config
require(ROOT_PATH . "config/config.php");

//require autoloader cache
require(ROOT_PATH . "system/core/classes/autoloadercache.php");

//initialize autoloader cache
AutoLoaderCache::init();

//load pre-loaded classes, if option is enabled
if (OPTION_PRELOAD_CLASSES) {
	AutoLoaderCache::load();
}

//initialize cache
Cache::init();

//initialize database
Database::getInstance();

//initialize events
Events::init();

//throw init event
Events::throwEvent("init");

//TODO: manage session

//check secure php options
Security::check();

Events::throwEvent("init_security");

?>
