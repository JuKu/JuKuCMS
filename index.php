<?php

$start_time = microtime(true);

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

error_reporting(E_ALL);

//start session
session_start();

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

//get user
$user = User::current();
$registry->storeObject("user", $user);

//get my groups
$groups = new Groups();
$groups->loadMyGroups($user->getID());
$registry->storeObject("groups", $groups);

$page = new Page();
$page->load();
$registry->storeObject("page", $page);

//set folder
$registry->setSetting("folder", $page->getFolder());

//create page type
$page_type = PageLoader::loadInstance($page->getPageType());
$page_type->setPage($page);
$registry->storeObject("page_type", $page_type);

//set content type
header("Content-Type: " . $page_type->getContentType());
$page_type->setCustomHeader();

//get current style
$registry->setSetting("current_style_name", StyleController::getCurrentStyle($registry, $page, $page_type));

//get (global) main menu
$menuID = (int) ($page->getGlobalMenuID() != -1) ? $page->getGlobalMenuID() : Settings::get("main_menuID");
$menu = new Menu($menuID, "menu");
$menu->loadMenu();
$registry->storeObject("main_menu", $menu);

//get (global) main menu
$localMenuID = (int) ($page->getLocalMenuID() != -1) ? $page->getLocalMenuID() : Settings::get("local_menuID");
$localMenu = new Menu($menuID, "local_menu");
$localMenu->loadMenu();
$registry->storeObject("local_menu", $localMenu);

$registry->setSetting("footer", "");

Events::throwEvent("Show page", array(
	'registry' => &$registry
));

//show page here
if ($page_type->showDesign()) {
	//show page with design
	StyleController::showPage($registry, $page, $page_type);
} else {
	//only show content
	return $page_type->getContent();
}

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

//benchmark code
if (ACTIVATE_BENCHMARK) {
	echo "<!-- page was generated in " . $exec_time . " seconds -->\n";
	echo "<!-- mobile detection executed in " . $mobile_detection_exec_time . " seconds, isMobile: " . ($registry->getSetting("isMobile") ? "true" : "false") . " -->\n";
}

if (DEBUG_MODE) {
	echo "<!-- userID: " . User::current()->getID() . ", username: " . User::current()->getUsername() . " -->\n";
	echo "<!-- " . Database::getInstance()->countQueries() . " sql queries executed -->";

	foreach (Database::getInstance()->listQueryHistory() as $query_array) {
		echo "<!-- query: " . $query_array['query'] . " -->\n";
	}
}

//flush gzip cache
ob_end_flush();

//update online list
User::current()->updateOnlineList();

?>