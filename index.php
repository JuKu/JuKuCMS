<?php

$start_time = microtime(true);

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

error_reporting(E_ALL);

require("system/core/init.php");

//throw event
Events::throwEvent("start_session");

//get domain
$domain = Domain::getCurrent();

//check, if redirect is enabled
if ($domain->isRedirectUrl()) {
	if ($domain->getRedirectCode() == 301) {
		header("HTTP/1.1 301 Moved Permanently");
	} else if ($domain->getRedirectCode() == 302) {
		header("HTTP/1.1 302 Found");
	}

	header("Location: " + $domain->getRedirectUrl());
	header("Connection: close");

	exit;
}

//start session
session_start();

//use gzip compression
ob_start();

//create new instance of registry
$registry = Registry::singleton();

echo "current domain: " . DomainUtils::getCurrentDomain();

//get domain
$domain = new Domain();
$domain->load();
$registry->storeObject("domain", $domain);
$registry->setSetting("domain_name", DomainUtils::getCurrentDomain());

//mobile detection
$registry->setSetting("isMobile", Browser::isMobile());

//TODO: show page here

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

//benchmark code
if (ACTIVATE_BENCHMARK) {
	echo "<!-- page was generated in " . $exec_time . " seconds -->\n";
}

if (DEBUG_MODE) {
	echo "<!-- " . Database::getInstance()->countQueries() . " sql queries executed -->";
}

//flush gzip cache
ob_end_flush();

?>