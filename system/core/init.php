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
	flush();
	@ob_end_flush();

	//throw event, for example to write logs to file
	@Events::throwEvent("shutdown_function");
});

//define some constants
define('CACHE_PATH', ROOT_PATH . "cache/");
define('CONFIG_PATH', ROOT_PATH . "config/");
define('STORE_PATH', ROOT_PATH . "store/");
define('PACKAGE_PATH', ROOT_PATH . "system/packages/");
define('STYLE_PATH', ROOT_PATH . "styles/");
define('PLUGIN_PATH', ROOT_PATH . "plugins/");

//check, if cache directory is writable
if (!file_exists(CACHE_PATH)) {
	echo "Error! cache directory doesnt exists!";
	exit;
}

if (!is_writable(CACHE_PATH)) {
	echo "Error! directory isnt writable!";
	exit;
}

//set default charset to UTF-8
@ini_set('default_charset', 'utf-8');
@mb_internal_encoding("UTF-8");

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

//configure session
$session_ttl = (int) Settings::get("session_ttl", 3600);

// server should keep session data for AT LEAST 1 hour
@ini_set('session.gc_maxlifetime', $session_ttl);

//each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params($session_ttl, "/", DomainUtils::getCurrentDomain(), (DomainUtils::isHTTPS() ? TRUE : FALSE), true);

//start session
session_start();

//check secure php options
Security::check();

//check, if allow_url_fopen is enabled
if (!PHPUtils::isUrlfopenEnabled() && !PHPUtils::isCurlAvailable()) {
	echo "CMS requires PHP option 'allow_url_fopen' enabled OR extension 'curl', change your php.ini or hosting settings to enable this option!";
	ob_flush();
	exit;
}

Events::throwEvent("init_security");

//check for maintenance mode
if (Settings::get("maintenance_mode_enabled", false) == true) {
	$html = Settings::get("maintenance_text", "Maintenance mode enabled!");

	//throw event
	Events::throwEvent("maintenance_html", array(
		'html' => &$html
	));

	if (file_exists(ROOT_PATH . "maintenance.html")) {
		echo file_get_contents(ROOT_PATH . "maintenance.html");
	} else if (file_exists(ROOT_PATH . "setup/maintenance.html")) {
		echo file_get_contents(ROOT_PATH . "setup/maintenance.html");
	} else {
		echo $html;
	}
}

//initialize logging
Logger::init();

/*set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}

	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

function warning_handler($errno, $errstr) {
// do something
}

set_error_handler("warning_handler", E_WARNING);*/

?>
