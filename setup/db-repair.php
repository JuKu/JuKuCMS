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


/**
 * This file creates / upgrades database structure
 */

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/../");

error_reporting(E_ALL);

if (PHP_MAJOR_VERSION < 7) {
	echo "CMS is required PHP 7.0.0 or greater! Please install PHP 7 (current version: " . PHP_VERSION . ").";
	exit;
}

//define some constants
define('CACHE_PATH', ROOT_PATH . "cache/");
define('CONFIG_PATH', ROOT_PATH . "config/");
define('STORE_PATH', ROOT_PATH . "store/");

//include and load ClassLoader
require(ROOT_PATH . "system/core/classes/classloader.php");
ClassLoader::init();

//require config
require(ROOT_PATH . "config/config.php");

//initialize cache
Cache::init();

//initialize database
Database::getInstance();

/**
 * create database schema here
 */

/**
 * test table to check upgrade system
 */

echo "Create / Upgrade table <b>test</b>...<br />";

//create or upgrade test table
$table = new DBTable("test", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addInt("id", 11, true, true);
$table->addVarchar("name", 255, true);
$table->addVarchar("value", 255, true);
$table->addInt("activated", 10, true, false, "1");

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("name", "UNIQUE_NAME");
$table->addIndex("activated", "ix_activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * test table to check upgrade system with added column, removed column and changed column
 */

echo "Create / Upgrade table <b>test</b>...<br />";

//create or upgrade test table
$table = new DBTable("test", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addInt("id", 11, true, true);
//$table->addVarchar("name", 255, true);
$table->addVarchar("name2", 255, true);
$table->addVarchar("value", 200, false);
$table->addInt("activated", 10, true, false, "1");

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("name", "UNIQUE_NAME");
$table->addIndex("activated", "ix_activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table EVENTS
 */

echo "Create / Upgrade table <b>events</b>...<br />";

//create or upgrade test table
$table = new DBTable("events", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addInt("id", 11, true, true);
$table->addVarchar("name", 255, true);
$table->addEnum("type", array("FILE", "FUNCTION", "CLASS_STATIC_METHOD", ""), true);
$table->addVarchar("file", 255, false, "NULL");
$table->addVarchar("class_name", 255, true, "");
$table->addVarchar("class_method", 255, true, "");
$table->addVarchar("created_from", 255, true);
$table->addInt("activated", 10, true, false, "1");

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique(array("name", "file", "class_name", "class_method"), "UNIQUE_EVENTS");
$table->addIndex(array("name", "activated"), "name");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table DOMAIN
 */

echo "Create / Upgrade table <b>domain</b>...<br />";

//create or upgrade test table
$table = new DBTable("domain", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addInt("id", 10, true, true);
$table->addVarchar("domain", 255, true);
$table->addInt("alias", 10, true, false, "-1");
$table->addVarchar("home_page", 255, true, "home");
$table->addEnum("wildcard", array("YES", "NO"), true, "NO");
$table->addInt("styleID", 10, true, false, "-1");
$table->addVarchar("redirect_url", 255, true, "none");#
$table->addInt("redirect_code", 10, true, false, "301");
$table->addVarchar("base_dir", 255, true, "/");
$table->addInt("force_ssl", 10, true, false, "0");//if 1 then all http urls would be rewritten to https urls
$table->addTimestamp("lastUpdate", true, "CURRENT_TIMESTAMP");
$table->addInt("activated", 10, true, false, "1");

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("domain");
$table->addIndex("alias");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table global_settings
 *
 * Package: com.jukusoft.cms.settings
 */

echo "Create / Upgrade table <b>global_settings</b>...<br />";

//create or upgrade test table
$table = new DBTable("global_settings", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addVarchar("key", 255, true);
$table->addText("value", true);
$table->addVarchar("title", 255, true);
$table->addVarchar("description", 600, true);
$table->addVarchar("visible_permission", 255, true, "can_see_global_settings");
$table->addVarchar("change_permission", 255, true, "can_change_global_settings");
$table->addVarchar("owner", 255, true, "system");
$table->addInt("order", 10, true, false, 10);
$table->addVarchar("icon_path", 600, true, "none");
$table->addTimestamp("last_update", true, "0000-00-00 00:00:00", true);
$table->addVarchar("category", 255, true, "general");
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("key");
$table->addIndex("last_update");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table global_settings_category
 *
 * Package: com.jukusoft.cms.settings
 */

echo "Create / Upgrade table <b>global_settings_category</b>...<br />";

//create or upgrade test table
$table = new DBTable("global_settings_category", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addVarchar("category", 255, true);
$table->addVarchar("title", 255, true);
$table->addVarchar("owner", 255, true, "system");
$table->addInt("order", 10, true, false, 10);

//add keys to table
$table->addPrimaryKey("category");
$table->addIndex("order");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table api_methods
 *
 * Package: com.jukusoft.cms.apimethods
 */

echo "Create / Upgrade table <b>api_methods</b>...<br />";

//create or upgrade test table
$table = new DBTable("api_methods", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addVarchar("api_method", 255, true);
$table->addVarchar("classname", 255, true);
$table->addVarchar("method", 255, true);
$table->addVarchar("response_type", 255, true, " 	application/json");
$table->addVarchar("owner", 255, true, "system");
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("api_method");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table robots
 *
 * Package: com.jukusoft.cms.robots
 */

echo "Create / Upgrade table <b>robots</b>...<br />";

//create or upgrade test table
$table = new DBTable("robots", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addVarchar("useragent", 255, true);
$table->addEnum("option", array("ALLOW", "DISALLOW", "SITEMAP", "CRAWL-DELAY"), true, "ALLOW");
$table->addVarchar("value", 600, true);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey(array("useragent", "option"));
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table folder
 *
 * Package: com.jukusoft.cms.folder
 */

echo "Create / Upgrade table <b>folder</b>...<br />";

//create or upgrade test table
$table = new DBTable("folder", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addVarchar("folder", 255, true);
$table->addVarchar("force_template", 255, true, "none");
$table->addInt("hidden", 10, true, false, 0);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("folder");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table style_rules
 *
 * Package: com.jukusoft.cms.style
 */

echo "Create / Upgrade table <b>style_rules</b>...<br />";

//create or upgrade test table
$table = new DBTable("style_rules", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addInt("rule_id", 10, true, true);
$table->addEnum("type", array("DOMAIN", "FOLDER", "MEDIA", "LANGUAGE"), true);
$table->addVarchar("expected_value", 255, true);
$table->addVarchar("style_name", 255, true);
$table->addInt("parent", 10, true, false, "-1");
$table->addInt("order", 10, true, false, 1);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("rule_id");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table supported_languages
 *
 * Package: com.jukusoft.cms.style
 */

echo "Create / Upgrade table <b>supported_languages</b>...<br />";

//create or upgrade test table
$table = new DBTable("supported_languages", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addVarchar("lang_token", 255, true);
$table->addVarchar("title", 255, true);

//add keys to table
$table->addPrimaryKey("lang_token");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table pages
 *
 * Package: com.jukusoft.cms.page
 */

echo "Create / Upgrade table <b>pages</b>...<br />";

//create or upgrade test table
$table = new DBTable("pages", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//add int coloum with length 10, NOT NULL and AUTO_INCREMENT
$table->addInt("id", 10, true, true);
$table->addVarchar("alias", 255, true);
$table->addVarchar("title", 255, true);
$table->addText("content", true, "");
$table->addVarchar("content_type", 255, true, "text/html");
$table->addInt("redirect_code", 10, true, false, "301");
$table->addInt("parent", 10, true, false, -1);
$table->addVarchar("folder", 255, true, "/");
$table->addInt("global_menu", 10, true, false, -1);
$table->addInt("local_menu", 10, true, false, -1);
$table->addVarchar("page_type", 255, true, "HTMLPage");
$table->addVarchar("design", 255, true, "none");
$table->addInt("sitemap", 10, true, false, 1);//should page be shown in sitemap?
$table->addInt("published", 10, true, false, 0);
$table->addInt("version", 10, true, false, 1);
$table->addTimestamp("last_update", true, "0000-00-00 00:00:00", true);
$table->addTimestamp("created", true, "0000-00-00 00:00:00", false);
$table->addInt("editable", 10, true, false, 1);
$table->addInt("author", 10, true, false, -1);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("alias");
$table->addIndex("folder");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table page_types
 *
 * Package: com.jukusoft.cms.page
 */

echo "Create / Upgrade table <b>page_types</b>...<br />";

//create or upgrade test table
$table = new DBTable("page_types", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("page_type", 255, true);
$table->addVarchar("title", 255, true);

//add keys to table
$table->addPrimaryKey("page_type");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table menu_names
 *
 * Package: com.jukusoft.cms.menu
 */

echo "Create / Upgrade table <b>menu_names</b>...<br />";

//create or upgrade test table
$table = new DBTable("menu_names", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("menuID", 10, true, true);
$table->addVarchar("title", 255, true);
$table->addInt("editable", 10, true, false, 1);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("menuID");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table menu
 *
 * Package: com.jukusoft.cms.menu
 */

echo "Create / Upgrade table <b>menu</b>...<br />";

//create or upgrade test table
$table = new DBTable("menu", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addInt("menuID", 10, true, false);
$table->addVarchar("title", 255, true);
$table->addVarchar("url", 600, true);
$table->addVarchar("type", 255, true, "page");//page / link / url / external_link / js_link
$table->addVarchar("icon", 255, true, "none");
$table->addVarchar("permissions", 600, true, "all");
$table->addInt("login_required", 10, true, false, 0);
$table->addInt("parent", 10, true, false, -1);
$table->addInt("order", 10, true, false, 10);
$table->addVarchar("owner", 255, true, "user");
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("id");
$table->addIndex("menuID");
$table->addIndex("order");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table user
 *
 * Package: com.jukusoft.cms.user
 */

echo "Create / Upgrade table <b>user</b>...<br />";

//create or upgrade test table
$table = new DBTable("user", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("userID", 10, true, true);
$table->addVarchar("username", 255, true);
$table->addVarchar("password", 255, true);
$table->addVarchar("salt", 255, true);
$table->addVarchar("mail", 255, true);
$table->addVarchar("ip", 255, true);
$table->addInt("main_group", 10, true, false, "2");
$table->addVarchar("specific_title", 255, true, "none");
$table->addInt("online", 10, true, false, "0");
$table->addTimestamp("last_online", true, "0000-00-00 00:00:00");
$table->addTimestamp("registered", true, "CURRENT_TIMESTAMP");
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("userID");
$table->addUnique("username");
$table->addIndex("mail");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

//create default wildcard domain, if absent
Domain::createWildcardDomain();

echo "Create folder...<br />";

//create default folders, if absent
Folder::createFolderIfAbsent("/", false);
Folder::createFolderIfAbsent("/admin/", true);

echo "Create default (supported) languages...<br />";

//add supported languages
Lang::addLangOrUpdate("de", "German");
Lang::addLangOrUpdate("en", "English");

echo "Create default global settings...<br />";

//create or update default settings (value will be only set, if key doesnt exists)
Settings::create("default_lang", "de", "Default Language", "Default (fallback) language, if no other languages are supported", "system", "general");
Settings::create("default_style_name", "default", "Default Style", "Default (fallback) style name, which will be used, if no other design was set by style rules.", "system", "general");
Settings::create("default_mobile_style_name", "default", "Default mobile Style", "Like default style name, but for mobiledevices", "system", "general");
Settings::create("guest_userid", -1, "Guest UserID", "UserID of not-logged-in users (default: -1).", "system", "general");
Settings::create("guest_username", "Guest", "Guest Username", "Username of not-logged-in users (default: Guest).", "system", "general");
Settings::create("online_interval", 5, "Online Interval", "Interval-Angabe in minues, how long a user is set as online (since last page request). IMPORTANT: This is independent from login interval!", "system", "general");
Settings::create("x_frame_options", "DENY", "X-Frame-Options header value (none = dont set header).", "values: DENY, SAMEORIGIN, ALLOW-FROM https://example.com/, none", "system", "security");
Settings::create("login_page","login", "Alias of Login Page (incl. directory, if necessary)", "Alias of Login Page (incl. directory, if necessary). Default: login", "system", "general");
Settings::create("logout_page", "logout", "Alias of Logout Page (incl. directory, if necessary)", "Alias of Logout Page (incl. directory, if necessary). Default: logout", "system", "general");
//Settings::create("base_dir", "/", "Base directory", "Base directory (if this CMS is installed in root directory, the right option is '/', but if it is installed in a sub directory, the right option is '/sub-dir/'). Default: /", "system", "general");

$main_menuID = -1;
$local_menuID = -1;
$admin_menuID = -1;

//get main menuID
if (!Settings::contains("main_menuID")) {
	//create menu_names if absent
	$main_menuID = Menu::createMenuName("Main Menu");

	//set setting
	Settings::create("main_menuID", $admin_menuID, "Main MenuID", "id of main menu (in menu area)", "system", "general", "none", "none", 1);
} else {
	$main_menuID = Settings::get("main_menuID");
}

//get admin area menuID
if (!Settings::contains("admin_menuID")) {
	//create menu_names if absent
	$admin_menuID = Menu::createMenuName("Admin Menu");

	//set setting
	Settings::create("admin_menuID", $admin_menuID, "Admin MenuID", "id of admin menu (in admin area)", "system", "general", "none", "none", 2);
} else {
	$admin_menuID = Settings::get("admin_menuID");
}

//get local menuID
if (!Settings::contains("local_menuID")) {
	//create menu_names if absent
	$local_menuID = Menu::createMenuName("Default Local Menu");

	//set setting
	Settings::create("local_menuID", $admin_menuID, "Default Local MenuID", "id of default local menu (in menu area)", "system", "general", "none", "none", 1);
} else {
	$local_menuID = Settings::get("local_menuID");
}

//TODO: create menus if absent

echo "Create default pages if absent...<br />";

Page::createIfAbsent("home", "Home", "HTMLPage", "Home page", "/");
Page::createIfAbsent("error404", "Error 404", "Error404Page", "Error 404 - Couldn't find this page.", "/");
Page::createIfAbsent("login", "Login", "LoginPage", "", "/");
Page::createIfAbsent("logout", "Logout", "LogoutPage", "", "/");

echo "Create admin pages if absent...<br />";
Page::createIfAbsent("admin/home", "Admin Area", "Admin_Dashboard", "", "/admin/", $admin_menuID, -1, -1, false, true, false);

//TODO: create groups

echo "Create default administrator user if absent...<br />";
User::createIfIdAbsent(1, "admin", "admin", "admin@example.com", 1, "Administrator", 1);

echo "<br /><br />Finished DB Upgrade!";

?>
