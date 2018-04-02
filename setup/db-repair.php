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

define('INSTALL_SCRIPT', true);

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
$table->addVarchar("value", 255, true);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey(array("useragent", "value"));
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
$table->addVarchar("permissions", 600, true, "none");
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
$table->addEnum("type", array("DOMAIN", "FOLDER", "MEDIA", "PREF_LANG", "SUPPORTED_LANG"), true);
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
$table->addVarchar("can_see_permissions", 255, true, "none");
$table->addVarchar("template", 255, true, "none");
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
$table->addVarchar("create_permissions", 255, true, "none");//list with permissions (OR), one of this permissions is required to create pages with this page type
$table->addInt("advanced", 10, true, false, 0);//flag, if page type is only shown in expert (advanced) mode
$table->addInt("order", 10, true, false, 10);//order in admin area on page creation selection
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("page_type");
$table->addIndex("advanced");
$table->addIndex("activated");

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
$table->addVarchar("unique_name", 255, true);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("menuID");
$table->addUnique("unique_name");
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

/**
 * table groups
 *
 * Package: com.jukusoft.cms.groups
 */

echo "Create / Upgrade table <b>groups</b>...<br />";

//create or upgrade test table
$table = new DBTable("groups", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("groupID", 10, true, true);
$table->addVarchar("name", 255, true);
$table->addText("description", true);
$table->addVarchar("color", 50, true, "#0066ff");
$table->addVarchar("rank_title", 255, true, "none");
$table->addVarchar("rank_image", 255, true, "none");
$table->addInt("auto_assign_regist", 10, true, false, "0");//flag, if group is automatically assigned to registered users
$table->addInt("system_group", 10, true, false, "0");
$table->addInt("show", 10, true, false, "1");//show group name on index page
$table->addInt("activated", 10, true, false, "1");

//https://www.w3schools.com/colors/colors_picker.asp

//add keys to table
$table->addPrimaryKey("groupID");
$table->addIndex("auto_assign_regist");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table group_members
 *
 * Package: com.jukusoft.cms.groups
 */

echo "Create / Upgrade table <b>group_members</b>...<br />";

//create or upgrade test table
$table = new DBTable("group_members", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("groupID", 10, true, false);
$table->addInt("userID", 10, true, false);
$table->addInt("group_leader", 10, true, false, "0");
$table->addInt("activated", 10, false, false, "1");

//https://www.w3schools.com/colors/colors_picker.asp

//add keys to table
$table->addPrimaryKey(array("groupID", "userID"));
$table->addIndex("groupID");
$table->addIndex("userID");
$table->addIndex("group_leader");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table css_files
 *
 * Package: com.jukusoft.cms.cssbuilder
 */

echo "Create / Upgrade table <b>css_files</b>...<br />";

//create or upgrade test table
$table = new DBTable("css_files", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("style", 255, true);
$table->addVarchar("css_file", 255, true);
$table->addVarchar("media", 255, true, "ALL");
$table->addInt("activated", 10, true, false, 1);

//https://www.w3schools.com/colors/colors_picker.asp

//add keys to table
$table->addPrimaryKey(array("style", "css_file"));
$table->addIndex("css_file");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table js_files
 *
 * Package: com.jukusoft.cms.jsbuilder
 */

echo "Create / Upgrade table <b>js_files</b>...<br />";

//create or upgrade test table
$table = new DBTable("js_files", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("style", 255, true);
$table->addVarchar("js_file", 255, true);
$table->addVarchar("media", 255, true, "ALL");
$table->addVarchar("position", 255, true, "FOOTER");
$table->addInt("activated", 10, true, false, 1);

//https://www.w3schools.com/colors/colors_picker.asp

//add keys to table
$table->addPrimaryKey(array("style", "js_file"));
$table->addIndex("js_file");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table permission_category
 *
 * Package: com.jukusoft.cms.permissions
 */

echo "Create / Upgrade table <b>permission_category</b>...<br />";

//create or upgrade test table
$table = new DBTable("permission_category", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("category", 255, true);
$table->addVarchar("title", 255, true);
$table->addVarchar("area", 255, true, "global");
$table->addInt("show", 10, true, false, 1);
$table->addInt("order", 10, true, false, 100);
$table->addInt("activated", 10, false, false, 1);

//https://www.w3schools.com/colors/colors_picker.asp

//add keys to table
$table->addPrimaryKey("category");
$table->addIndex("area");
$table->addIndex("order");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table permissions
 *
 * Package: com.jukusoft.cms.permissions
 */

echo "Create / Upgrade table <b>permissions</b>...<br />";

//create or upgrade test table
$table = new DBTable("permissions", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("token", 255, true);
$table->addVarchar("title", 255, true);
$table->addVarchar("description", 600, true);
$table->addVarchar("category", 255, true, "general");
$table->addVarchar("owner", 255, true, "system");
$table->addInt("show", 10, true, false, 1);//flag, if permission is shown on permissions page
$table->addInt("order", 10, true, false, 100);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("token");
$table->addIndex("category");
$table->addIndex("order");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table group_rights
 *
 * Package: com.jukusoft.cms.permissions
 */

echo "Create / Upgrade table <b>group_rights</b>...<br />";

//create or upgrade test table
$table = new DBTable("group_rights", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("groupID", 10, true, false);
$table->addVarchar("token", 255, true);
$table->addInt("value", 10, true, false);

//add keys to table
$table->addPrimaryKey(array("groupID", "token"));

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table user_rights
 *
 * Package: com.jukusoft.cms.permissions
 */

echo "Create / Upgrade table <b>user_rights</b>...<br />";

//create or upgrade test table
$table = new DBTable("user_rights", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("userID", 10, true, false);
$table->addVarchar("token", 255, true);
$table->addInt("value", 10, true, false);

//add keys to table
$table->addPrimaryKey(array("userID", "token"));

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table page_rights
 *
 * Package: com.jukusoft.cms.page
 */

echo "Create / Upgrade table <b>page_rights</b>...<br />";

//create or upgrade test table
$table = new DBTable("page_rights", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("groupID", 10, true, false);
$table->addInt("pageID", 10, true, false);
$table->addVarchar("token", 255, true);
$table->addInt("value", 10, true, false);

//add keys to table
$table->addPrimaryKey(array("groupID", "pageID", "token"));

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table page_user_rights
 *
 * Package: com.jukusoft.cms.page
 */

echo "Create / Upgrade table <b>page_user_rights</b>...<br />";

//create or upgrade test table
$table = new DBTable("page_user_rights", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("userID", 10, true, false);
$table->addInt("pageID", 10, true, false);
$table->addVarchar("token", 255, true);
$table->addInt("value", 10, true, false);

//add keys to table
$table->addPrimaryKey(array("userID", "pageID", "token"));

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

//create default wildcard domain, if absent
Domain::createWildcardDomain();

echo "Create folder...<br />";

//create default folders, if absent
Folder::createFolderIfAbsent("/", false);
Folder::createFolderIfAbsent("/admin/", true, array("can_access_admin_area"));

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
Settings::create("css_cache_strategy", "expires_header", "CSS Browser Cache Strategy", "CSS Browser Cache Strategy, values: expires_header, etag_header, none", "system", "general");
Settings::create("css_cache_expires_header_ttl", "31536000", "CSS Expires Header Seconds to cache", "Seconds to cache (on client browser). Only used, if cache strategy 'expired_header' is used! Default: 31536000 seconds (1 year)", "system", "general");
Settings::create("js_cache_strategy", "expires_header", "JS Browser Cache Strategy", "JS Browser Cache Strategy, values: expires_header, etag_header, none", "system", "general");
Settings::create("js_cache_expires_header_ttl", "31536000", "JS Expires Header Seconds to cache", "Seconds to cache (on client browser). Only used, if cache strategy 'expired_header' is used! Default: 31536000 seconds (1 year)", "system", "general");
Settings::create("title_praefix", "", "Title Praefix", "Title Praefix", "system", "general");
Settings::create("title_suffix", "", "Title Suffix", "Title Suffix", "system", "general");
Settings::create("copyright", "<strong>Copyright &copy; 2018 <a href=\"http://jukusoft.com\">JuKuSoft.com</a></strong>, All Rights Reserved.", "Copyright Notice on page", "Copyright notice on every page", "system", "general");

//maintenance mode
Settings::create("maintenance_mode_enabled", false, "Maintenance Mode enabled (boolean)", "Maintenance Mode enabled (boolean), Default: false", "system", "general");
Settings::create("maintenance_text", "This domain is currently under scheduled maintenance mode. Sorry for the inconvenience! Look in a few minutes over again!", "Maintenance Text", "Text which is shown, if maintenance mode is enabled", "system", "general");

$main_menuID = -1;
$local_menuID = -1;
$admin_menuID = -1;

//get main menuID
if (!Settings::contains("main_menuID")) {
	//create menu_names if absent
	$main_menuID = Menu::createMenuName("Main Menu", "main_menu");

	//set setting
	Settings::create("main_menuID", $main_menuID, "Main MenuID", "id of main menu (in menu area)", "system", "general", "none", "none", 1);
} else {
	$main_menuID = Settings::get("main_menuID");
}

//get admin area menuID
if (!Settings::contains("admin_menuID")) {
	//create menu_names if absent
	$admin_menuID = Menu::createMenuName("Admin Menu", "admin_menu");

	//set setting
	Settings::create("admin_menuID", $admin_menuID, "Admin MenuID", "id of admin menu (in admin area)", "system", "general", "none", "none", 2);
} else {
	$admin_menuID = Settings::get("admin_menuID");
}

//get local menuID
if (!Settings::contains("local_menuID")) {
	//create menu_names if absent
	$local_menuID = Menu::createMenuName("Default Local Menu", "local_menu");

	//set setting
	Settings::create("local_menuID", $local_menuID, "Default Local MenuID", "id of default local menu (in menu area)", "system", "general", "none", "none", 1);
} else {
	$local_menuID = Settings::get("local_menuID");
}

echo "Create default menu if absent...<br />";

//create menus if absent
Menu::createMenu(1, $main_menuID, "Home", "home", -1, "page", "all", false, "none", 1, "user");
Menu::createMenu(2, $admin_menuID, "Dashboard", "admin/home", -1, "page", array(can_access_admin_area), true, "none", 1, "system");

echo "Create default pages if absent...<br />";

Page::createIfAbsent("home", "Home", "IndexPage", "Home page", "/");
Page::createIfAbsent("error404", "Error 404", "Error404Page", "Error 404 - Couldn't find this page.", "/");
Page::createIfAbsent("login", "Login", "LoginPage", "", "/");
Page::createIfAbsent("logout", "Logout", "LogoutPage", "", "/");

//create robots.txt page
Page::createIfAbsent("robots.txt", "Robots.txt", "RobotsPage", "", "/", -1, -1, -1, false, true, false);

//create forbidden pages
Page::createIfAbsent("error403", "Error 403", "Error403Page", "Error 403 - Forbidden!<br /> You dont have permissions to access this page or folder. Maybe you have to login.", "/");

echo "Create admin pages if absent...<br />";
Page::createIfAbsent("admin/home", "Admin Dashboard", "Admin_Dashboard", "", "/admin/", $admin_menuID, -1, -1, false, true, false);

echo "Create default page types if absent...<br />";

PageType::createPageType("HTMLPage", "HTML page", false, 1);//order 1 - so show as first page type in admin area
PageType::createPageType("Error404Page", "Error 404 page", true);
PageType::createPageType("Error403Page", "Error 403 page", true);
PageType::createPageType("IndexPage", "index page (supports extra template)", true);
PageType::createPageType("LoginPage", "Login page", true);
PageType::createPageType("LogoutPage", "Logout page", true);

echo "Create style rule for admin area if absent...<br />";
StyleRules::createRuleWithPredefinedID(1, "FOLDER", "/admin/", "admin", -1, 1);

//create groups
echo "Create default groups if absent...<br />";

//#0099cc
Groups::createGroupIfIdAbsent(1, "Administrator", "Administrator group with full permissions", "#cc0000", true, true, false);
Groups::createGroupIfIdAbsent(2, "Registered Users", "Registered users (every new user is automatically added to this group on registration process)", "#33cc33", false, true, true);
Groups::createGroupIfIdAbsent(3, "Guests", "Not-logged-in users", "#669999", false, true, false);
Groups::createGroupIfIdAbsent(4, "Bots", "Bots (Google bot and so on)", "#cc00ff", false, true, false);

//Redakteur
Groups::createGroupIfIdAbsent(5, "Editor", "Editors (can create & edit every post and every page, can publish and delete pages)", "#ff9933", true, true, false);
Groups::createGroupIfIdAbsent(6, "Author", "Authors (can create & edit OWN posts and OWN pages, can publish and delete OWN pages)", "#ffcc00", true, true, false);

echo "Assign default users to default groups...<br />";

Groups::addGroupToUser(1, 1, true);
Groups::addGroupToUser(2, 1, true);
Groups::addGroupToUser(3, -1);

echo "Create default permission categories...<br />";
Permissions::createOrUpdateCategory("general", "General", 1);
Permissions::createOrUpdateCategory("users", "Users", 3);//user permissions, like "can_create_user"
Permissions::createOrUpdateCategory("groups", "Groups", 3);
Permissions::createOrUpdateCategory("pages", "Pages", 4);
Permissions::createOrUpdateCategory("permissions", "Permissions", 5);
Permissions::createOrUpdateCategory("admin", "Admin", 6);

echo "Create default permissions...<br />";
//general permissions
Permissions::createPermission("can_access_admin_area", "Can access admin area", "Can access admin area", "admin", "system", 1);
Permissions::createPermission("can_edit_own_password", "Can edit own password", "Can edit own password", "general", "system", 2);
Permissions::createPermission("can_edit_own_mail", "Can edit his own mail address", "Can edit his own mail address", "general", 3);

//permissions
Permissions::createPermission("can_see_own_permissions", "Can see own permissions", "User can see his own permissions", "permissions", "system", 1);
Permissions::createPermission("can_see_permissions", "Can see permissions of other users", "Can see permissions of other users", "permissions", "system", 2);
Permissions::createPermission("can_edit_group_permissions", "Can edit group permissions", "Can edit group permissions (expect administrator group)", "permissions", "system", 3);
Permissions::createPermission("can_edit_administrator_group_permissions", "Can edit administrator group permissions", "Can edit administrator group permissions", "permissions", "system", 4);//can edit permissions of group "administrator"

//user permissions
Permissions::createPermission("can_see_all_users", "Can see all users", "Can see all users and see their private information like mail address, ip address and so on", "users", "system", 1);
Permissions::createPermission("can_create_user", "Can create new user", "Can create new user", "users", "system", 2);
Permissions::createPermission("can_edit_users", "Can edit users", "Can edit users", "users", "system", 3);
Permissions::createPermission("can_edit_users_password", "Can edit password of users", "Can edit password of users (without super-admin with userID 1)", "users", "system", 4);

//page permissions
Permissions::createPermission("can_see_all_pages", "Can see all pages in admin area", "pages", "system", 1);
Permissions::createPermission("can_create_pages", "Can create pages", "Can create pages", "pages", "system", 2);
Permissions::createPermission("can_edit_own_pages", "Can edit own pages", "Can edit pages which was created by user", "pages", "system", 3);
Permissions::createPermission("can_edit_all_pages", "Can edit all pages", "Can edit all pages, including pages which was created by other users", "pages", "system", 4);
Permissions::createPermission("can_publish_own_pages", "Can publish his own pages", "Can publish his own pages", "pages", "system", 5);
Permissions::createPermission("can_publish_all_pages", "Can publish all pages", "Can publish all pages, including pages which was created by other users", "pages", "system", 6);
Permissions::createPermission("can_delete_own_pages", "Can delete own pages", "Can delete pages which was created by user", "pages", "system", 7);
Permissions::createPermission("can_delete_all_pages", "Can delete all pages", "Can delete all pages, including pages which was created by other users", "pages", "system", 8);

//admin permissions
Permissions::createPermission("can_see_cms_version", "Can see version of CMS system", "Can see version of CMS system", "admin", "system", 1);
Permissions::createPermission("can_see_global_settings", "Can see global CMS settings", "Can see global CMS settings", "admin", "system", 2);
Permissions::createPermission("can_edit_global_settings", "Can edit global settings", "Can edit global settings", "admin", "system", 3);
Permissions::createPermission("super_admin", "Is super admin and CAN EVERYTHING", "Is super admin and CAN EVERYTHING (overrides all other values!)", "admin", "system", 4);

echo "Set default permissions for userID 1...<br />";
$user_rights = new UserRights(1);

//userID 1 should be super_admin
$user_rights->setRight("super_admin", 1);

echo "Create default robots.txt rules...<br />";
Robots::addRule("DISALLOW", "/system/*");
Robots::addRule("DISALLOW", "/cache/*");
Robots::addRule("DISALLOW", "/docs/*");
Robots::addRule("DISALLOW", "/plugins/*");
Robots::addRule("DISALLOW", "/styles/*");
Robots::addRule("DISALLOW", "/admin/*");

echo "Create default administrator user if absent...<br />";
User::createIfIdAbsent(1, "admin", "admin", "admin@example.com", 1, "Administrator", 1);

echo "<br /><br />Finished DB Upgrade!";

if (file_exists(ROOT_PATH . "setup/add-install.php")) {
	echo "<br />Call add-install.php...<br />";

	require(ROOT_PATH . "setup/add-install.php");

	echo "<br /><br />Finished additional Upgrade!";
}

?>
