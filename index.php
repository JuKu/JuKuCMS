<?php

$start_time = microtime(true);

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

error_reporting(E_ALL);

require("system/core/init.php");

//reset OpCache in debug mode
if (CLEAR_OP_CACHE) {
	//http://php.net/manual/en/function.opcache-reset.php
	//http://php.net/manual/en/function.opcache-invalidate.php
	opcache_reset();
}

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

//TODO: remove this code in production
if (isset($_REQUEST['clear_cache'])) {
	//clear cache
	Cache::clear();

	echo "Clear cache!<br />";
}

//create new instance of registry
$registry = Registry::singleton();

//get domain
$domain = new Domain();
$domain->load();
$registry->storeObject("domain", $domain);
$registry->setSetting("domain_name", DomainUtils::getCurrentDomain());

$mobile_detection_start_time = microtime(true);

//mobile detection
$registry->setSetting("isMobile", Browser::isMobile());
$registry->setSetting("isDesktop", !Browser::isMobile());

$mobile_detection_end_time = microtime(true);
$mobile_detection_exec_time = $mobile_detection_end_time - $mobile_detection_start_time;

//get prefered language
$registry->setSetting("pref_lang", Lang::getPrefLangToken());
$registry->setSetting("lang_token", Lang::getLangToken(Lang::listSupportedLangTokens()));

var_dump($registry);

//TODO: show page here

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

//benchmark code
if (ACTIVATE_BENCHMARK) {
	echo "<!-- page was generated in " . $exec_time . " seconds -->\n";
	echo "<!-- mobile detection executed in " . $mobile_detection_exec_time . " seconds, isMobile: " . ($registry->getSetting("isMobile") ? "true" : "false") . " -->\n";
}

if (DEBUG_MODE) {
	echo "<!-- " . Database::getInstance()->countQueries() . " sql queries executed -->";
}

//flush gzip cache
ob_end_flush();

?>