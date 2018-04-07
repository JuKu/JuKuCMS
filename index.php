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

//check, if gzip compression is enabled
if (Settings::get("gzip_compression", false)) {
	//use gzip compression
	ob_start();
}

//TODO: remove this code in production
if (isset($_REQUEST['clear_cache'])) {
	//clear cache
	Cache::clear();

	//clear gettext cache
	PHPUtils::clearGetTextCache();

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

//set default language domain
Translator::getBackend()->setDefaultDomain("core");

//get user
$user = User::current();
$registry->storeObject("user", $user);

//get my groups
$groups = new Groups();
$groups->loadMyGroups($user->getID());
$registry->storeObject("groups", $groups);

//get permission checker
$registry->storeObject("permission_checker", PermissionChecker::current());

$page = new Page();
$page->load();

//load folder
$folder = new Folder($page->getFolder());
$folder->load($page->getFolder());

//check, if user has folder permissions
if (!$folder->checkPermissions(PermissionChecker::current())) {
	//user dont has permissions to access folder
	$page->load("error403");
}

//set folder
$registry->setSetting("folder", $page->getFolder());

//create page type
$page_type = PageLoader::loadInstance($page->getPageType());
$page_type->setPage($page);

//check, if user has page permissions
if (!$page_type->checkPermissions(PermissionChecker::current())) {
	//user dont has custom permissions to access page
	$page->load("error403");

	//create page type
	$page_type = PageLoader::loadInstance($page->getPageType());
	$page_type->setPage($page);
}

//check page rights
$page_rights = new PageRights($page);
$page_rights->load();

//permission to see a published page
$page_permission = "see";

//check, if page is not published
if (!$page->isPublished()) {
	//another permission is required, because page is not published yet
	$page_permission = "see_draft";
}

if (!$page_rights->checkRights($user->getID(), $groups->listGroupIDs(), $page_permission)) {
	//user dont has custom permissions to access page
	$page->load("error403");

	//create page type
	$page_type = PageLoader::loadInstance($page->getPageType());
	$page_type->setPage($page);
}

$registry->storeObject("page", $page);
$registry->storeObject("folder", $folder);
$registry->storeObject("page_type", $page_type);

//set content type
header("Content-Type: " . $page_type->getContentType());
$page_type->setCustomHeader();

//get current style
$registry->setSetting("current_style_name", StyleController::getCurrentStyle($registry, $page, $page_type));


//set login & logout url
$redirect_url = urlencode(DomainUtils::getURL());

if (isset($_REQUEST['redirect_url']) && !empty($_REQUEST['redirect_url'])) {
	$redirect_url = $_REQUEST['redirect_url'];
}

$registry->setSetting("login_url", DomainUtils::getBaseURL() . "/" . Settings::get("login_page", "login") . "?action=login&redirect_url=" . $redirect_url);
$registry->setSetting("logout_url", DomainUtils::getBaseURL() . "/" . Settings::get("logout_page", "logout") . "?csrf_token=" . urlencode(Security::getCSRFToken()));

//get (global) main menu
$menuID = (int) ($page->getGlobalMenuID() != -1) ? $page->getGlobalMenuID() : ($folder->hasCustomMainMenu() ? $folder->getMainMenu() : Settings::get("main_menuID"));
$menu = new Menu($menuID, "menu");
$menu->loadMenu($menuID, $folder);
$registry->storeObject("main_menu", $menu);

//get (global) main menu
$localMenuID = (int) ($page->getLocalMenuID() != -1) ? $page->getLocalMenuID() : ($folder->hasCustomLocalMenu() ? $folder->getLocalMenu() : Settings::get("local_menuID"));
$localMenu = new Menu($menuID, "localmenu");
$localMenu->loadMenu($localMenuID, $folder);
$registry->storeObject("local_menu", $localMenu);

$registry->setSetting("header", "");
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
	echo $page_type->getContent();

	if ($page_type->exitAfterOutput()) {
		//flush gzip cache
		ob_end_flush();

		exit;
	}
}

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

if ($page_type->showHTMLComments()) {
	//benchmark code
	if (ACTIVATE_BENCHMARK) {
		echo "<!-- page was generated in " . $exec_time . " seconds -->\n";
		echo "<!-- mobile detection executed in " . $mobile_detection_exec_time . " seconds, isMobile: " . ($registry->getSetting("isMobile") ? "true" : "false") . " -->\n";

		//benchmark dwoo template engine
		foreach (DwooTemplate::listFileBenchmark() as $file=>$exec_time) {
			echo "<!-- Dwoo benchmark file '" . $file . "': " . $exec_time . " seconds -->\n";
		}

		foreach (CSSBuilder::listBenchmarks() as $key=>$exec_time) {
			echo "<!-- css generation of file '" . $key . "': " . $exec_time . " seconds -->\n";
		}

		foreach (JSBuilder::listBenchmarks() as $key=>$exec_time) {
			echo "<!-- js generation of file '" . $key . "': " . $exec_time . " seconds -->\n";
		}
	}

	if (DEBUG_MODE) {
		echo "<!-- userID: " . User::current()->getID() . ", username: " . User::current()->getUsername() . " -->\n";
		echo "<!-- " . Database::getInstance()->countQueries() . " sql queries executed -->";

		if (DEBUG_SQL_QUERIES) {
			foreach (Database::getInstance()->listQueryHistory() as $query_array) {
				echo "<!-- query: " . $query_array['query'] . " -->\n";
			}
		}
	}
}

//flush gzip cache
ob_end_flush();

//update online list
User::current()->updateOnlineList();

?>