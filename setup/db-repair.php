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
$table->addVarchar("datatype", 255, true, "DataType_String");
$table->addText("datatype_params", true);
$table->addInt("editable", 10, true, false, 1);
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
$table->addVarchar("response_type", 255, true, "application/json");
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
$table->addInt("main_menu", 10, true, false, -1);
$table->addInt("local_menu", 10, true, false, -1);
$table->addVarchar("permissions", 600, true, "none");
$table->addInt("title_translation_support", 10, true, false, 1);
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
$table->addEnum("sitemap_changefreq", array("ALWAYS", "HOURLY", "DAILY", "WEEKLY", "MONTHLY", "YEARLY", "NEVER"), true, "weekly");
$table->addDecimal("sitemap_priority", 5, 2, true, "0.5");
$table->addInt("published", 10, true, false, 0);
$table->addInt("version", 10, true, false, 1);
$table->addTimestamp("last_update", true, "0000-00-00 00:00:00", true);
$table->addTimestamp("created", true, "0000-00-00 00:00:00", false);
$table->addInt("editable", 10, true, false, 1);
//$table->addInt("deletable", 10, true, false, 1);
$table->addInt("author", 10, true, false, -1);
$table->addVarchar("can_see_permissions", 255, true, "none");
$table->addVarchar("template", 255, true, "none");
$table->addInt("sidebar_left", 10, true, false, -1);
$table->addInt("sidebar_right", 10, true, false, -1);
$table->addVarchar("meta_description", 600, true, "");
$table->addVarchar("meta_keywords", 255, true, "");
$table->addVarchar("meta_robots", 255, true, "");//none means not set
$table->addVarchar("meta_canonicals", 255, true, "");
$table->addInt("locked_by", 10, true, false, -1);
$table->addTimestamp("locked_timestamp", true, "0000-00-00 00:00:00");
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
$table->addVarchar("owner", 255, true, "system");
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
$table->addVarchar("unique_name", 255, false, "");
$table->addVarchar("extensions", 255, true, "none");//for example: private messages
$table->addVarchar("owner", 255, true, "user");
$table->addInt("order", 10, true, false, 10);
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("unique_name");
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
$table->addVarchar("authentificator", 255, true, "LocalAuthentificator");
$table->addVarchar("owner", 255, true, "system");
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
$table->addVarchar("style_json_name", 255, true, "header");
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

/**
 * table register_mail_verification
 *
 * Package: com.jukusoft.cms.user
 */

echo "Create / Upgrade table <b>register_mail_verification</b>...<br />";

//create or upgrade test table
$table = new DBTable("register_mail_verification", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("userID", 10, true, false);
$table->addVarchar("token", 255, true);

//add keys to table
$table->addPrimaryKey("userID");
$table->addUnique("token");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table installed_plugins
 *
 * Package: com.jukusoft.cms.plugin
 */

echo "Create / Upgrade table <b>plugins</b>...<br />";

//create or upgrade test table
$table = new DBTable("plugins", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("name", 255, true);//directory name of plugin
$table->addVarchar("version", 255, true, "1.0.0");//installed version of plugin
//$table->addInt("build", 10, true, false, 1);//optional: build number of plugin
$table->addInt("installed", 10, true, false, 1);//flag, if plugin is installed
$table->addInt("activated", 10, true, false, 1);//flag, if plugin is activated

//add keys to table
$table->addPrimaryKey("name");
$table->addIndex("installed");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table plugin_installer_plugins
 *
 * Package: com.jukusoft.cms.plugin
 */

echo "Create / Upgrade table <b>plugin_installer_plugins</b>...<br />";

//create or upgrade test table
$table = new DBTable("plugin_installer_plugins", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("class_name", 255, true);//directory name of plugin
$table->addVarchar("path", 600, true);

//add keys to table
$table->addPrimaryKey("class_name");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table tasks
 *
 * Package: com.jukusoft.cms.tasks
 */

echo "Create / Upgrade table <b>tasks</b>...<br />";

//create or upgrade test table
$table = new DBTable("tasks", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addVarchar("title", 255, true);
$table->addVarchar("unique_name", 255, true, "");//unique name, so plugins or upgrade can find task easier
$table->addEnum("type", array("FILE", "FUNCTION", "CLASS_STATIC_METHOD", ""), true);
$table->addVarchar("type_params", 255, false, "NULL");
$table->addText("params", true);
$table->addVarchar("owner", 255, true, "system");
$table->addInt("delete_after_execution", 10, true, false, 0);
$table->addInt("interval", 10, true, false, 60);
$table->addTimestamp("last_execution", true, "0000-00-00 00:00:00");
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("unique_name");
$table->addIndex("last_execution");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table preferences
 *
 * Package: com.jukusoft.cms.preferences
 */

echo "Create / Upgrade table <b>preferences</b>...<br />";

//create or upgrade test table
$table = new DBTable("preferences", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("key", 255, true);
$table->addVarchar("area", 255, true);
$table->addText("value");

//add keys to table
$table->addPrimaryKey(array("key", "area"));

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table api_apps
 *
 * Package: com.jukusoft.cms.api
 */

echo "Create / Upgrade table <b>api_apps</b>...<br />";

//create or upgrade test table
$table = new DBTable("api_apps", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("app_id", 10, true, true);
$table->addVarchar("app_name", 255, true);

//add keys to table
$table->addPrimaryKey(array("app_id"));
$table->addUnique("app_name");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table api_keys
 *
 * Package: com.jukusoft.cms.api
 */

echo "Create / Upgrade table <b>api_keys</b>...<br />";

//create or upgrade test table
$table = new DBTable("api_keys", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("key_id", 10, true, true);
$table->addVarchar("secret", 255, true);
$table->addInt("app_id", 10, true, false);

//add keys to table
$table->addPrimaryKey(array("key_id"));
$table->addIndex("app_id");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table api_oauth
 *
 * Package: com.jukusoft.cms.api
 */

echo "Create / Upgrade table <b>api_oauth</b>...<br />";

//create or upgrade test table
$table = new DBTable("api_oauth", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addVarchar("secret_key", 255, true);
$table->addInt("userID", 10, true, false);
$table->addTimestamp("created", true, "CURRENT_TIMESTAMP", false);//to check, if secret key is valide
$table->addTimestamp("expires", true, "0000-00-00 00:00:00", false);

//add keys to table
$table->addPrimaryKey(array("secret_key"));

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table widget_types
 *
 * Package: com.jukusoft.cms.widgets
 */

echo "Create / Upgrade table <b>widget_types</b>...<br />";

//create or upgrade test table
$table = new DBTable("widget_types", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addVarchar("name", 255, true);
$table->addVarchar("description", 600, true, "");
$table->addVarchar("class_name", 255, true);
$table->addInt("editable", 10, true, false, 1);//flag, if widget type is editable (this means added widgets with this type can be edited)
$table->addVarchar("owner", 255, true, "system");

//add keys to table
$table->addPrimaryKey(array("id"));
$table->addUnique("class_name");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table sidebars
 *
 * Package: com.jukusoft.cms.sidebar
 */

echo "Create / Upgrade table <b>sidebars</b>...<br />";

//create or upgrade test table
$table = new DBTable("sidebars", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("sidebar_id", 10, true, true);
$table->addVarchar("unique_name", 255, true);
$table->addVarchar("title", 255, true, "");
$table->addInt("deletable", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey(array("sidebar_id"));
$table->addUnique("unique_name");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

/**
 * table sidebar_widgets
 *
 * Package: com.jukusoft.cms.sidebar
 */

echo "Create / Upgrade table <b>sidebar_widgets</b>...<br />";

//create or upgrade test table
$table = new DBTable("sidebar_widgets", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addInt("sidebar_id", 10, true, false);
$table->addVarchar("title", 255, true, "");
$table->addText("content", true, "");
$table->addVarchar("class_name", 255, true, "");
$table->addText("widget_params", true);//json encoded string, e.q. "{}"
$table->addVarchar("css_id", 255, true, "");
$table->addVarchar("css_class", 255, true, "");
$table->addVarchar("before_widget", 600, true, "");
$table->addVarchar("after_widget", 600, true, "");
//$table->addVarchar("before_title", 600, true, "");
//$table->addVarchar("after_title", 600, true, "");
$table->addVarchar("unique_name", 255, true);
$table->addInt("order", 10, true, false, 10);

//add keys to table
$table->addPrimaryKey(array("id"));
$table->addUnique("unique_name");
$table->addIndex("sidebar_id");

//create or upgrade table
$table->upgrade();

echo "Finished!<br />";

//create default wildcard domain, if absent
Domain::createWildcardDomain();

echo "Create folder...<br />";

echo "Create default (supported) languages...<br />";

//add supported languages
Lang::addLangOrUpdate("de", "German");
Lang::addLangOrUpdate("en", "English");

echo "Create default sidebars...<br />";

Sidebar::create("Left sidebar", "sidebar_left", false);
Sidebar::create("Right sidebar", "sidebar_right", false);

echo "Create default global setting categories...<br />";

SettingsCategory::createIfAbsent("general", "General", 1, "system");
SettingsCategory::createIfAbsent("sidebar", "Sidebar", 2, "system");
SettingsCategory::createIfAbsent("mail", "Mail", 15, "system");
SettingsCategory::createIfAbsent("user", "User", 16, "system");
SettingsCategory::createIfAbsent("api", "API", 17, "system");
SettingsCategory::createIfAbsent("logging", "Logging", 18, "system");
SettingsCategory::createIfAbsent("tools", "Tools", 19, "system");
SettingsCategory::createIfAbsent("tasks", "Tasks", 20, "system");

echo "Create default global settings...<br />";

//create or update default settings (value will be only set, if key doesnt exists)
Settings::create("default_lang", "de", "Default Language", "Default (fallback) language, if no other languages are supported", "system", "general", "DataType_LangChooser");
Settings::create("default_style_name", "texturedblue", "Default Style", "Default (fallback) style name, which will be used, if no other design was set by style rules.", "system", "general", "DataType_StyleChooser");
Settings::create("default_mobile_style_name", "texturedblue", "Default mobile Style", "Like default style name, but for mobiledevices", "system", "general", "DataType_StyleChooser");
Settings::create("guest_userid", -1, "Guest UserID", "UserID of not-logged-in users (default: -1).", "system", "general", "DataType_Integer", array("min" => -1));
Settings::create("guest_username", "Guest", "Guest Username", "Username of not-logged-in users (default: Guest).", "system", "general", "DataType_Username");
Settings::create("online_interval", 5, "Online Interval", "Interval in minues, how long a user is set as online (since last page request). IMPORTANT: This is independent from login interval!", "system", "general", "DataType_Integer");
Settings::create("x_frame_options", "DENY", "X-Frame-Options header value (none = dont set header).", "values: DENY, SAMEORIGIN, ALLOW-FROM https://example.com/, none", "system", "security", "DataType_String");
Settings::create("login_page","login", "Alias of Login Page (incl. directory, if necessary)", "Alias of Login Page (incl. directory, if necessary). Default: login", "system", "general", "DataType_String");
Settings::create("logout_page", "logout", "Alias of Logout Page (incl. directory, if necessary)", "Alias of Logout Page (incl. directory, if necessary). Default: logout", "system", "general", "DataType_String");
//Settings::create("base_dir", "/", "Base directory", "Base directory (if this CMS is installed in root directory, the right option is '/', but if it is installed in a sub directory, the right option is '/sub-dir/'). Default: /", "system", "general");
Settings::create("css_cache_strategy", "expires_header", "CSS Browser Cache Strategy", "CSS Browser Cache Strategy, values: expires_header, etag_header, none", "system", "general", "DataType_SelectBox", array("expires_header", "etag_header", "none"));
Settings::create("css_cache_expires_header_ttl", "31536000", "CSS Expires Header Seconds to cache", "Seconds to cache (on client browser). Only used, if cache strategy 'expired_header' is used! Default: 31536000 seconds (1 year)", "system", "general", "DataType_Integer");
Settings::create("js_cache_strategy", "expires_header", "JS Browser Cache Strategy", "JS Browser Cache Strategy, values: expires_header, etag_header, none", "system", "general", "DataType_SelectBox", array("expires_header", "etag_header", "none"));
Settings::create("js_cache_expires_header_ttl", "31536000", "JS Expires Header Seconds to cache", "Seconds to cache (on client browser). Only used, if cache strategy 'expired_header' is used! Default: 31536000 seconds (1 year)", "system", "general", "DataType_Integer");
Settings::create("title_praefix", "", "Title Praefix", "Title Praefix", "system", "general", "DataType_String");
Settings::create("title_suffix", "", "Title Suffix", "Title Suffix", "system", "general", "DataType_String");
Settings::create("copyright", "<strong>Copyright &copy; 2018 <a href=\"http://jukusoft.com\">JuKuSoft.com</a></strong>, All Rights Reserved.", "Copyright Notice on page", "Copyright notice on every page", "system", "general", "DataType_HTML");
Settings::create("website_name", "" . DomainUtils::getDomain(), "Website name", "Name of your website, e.q. used for mail templates", "system", "general", "DataType_String");

Settings::create("gzip_compression", true, "GZip compression enabled", "GZip compression enabled", "system", "general", "DataType_Boolean");
Settings::create("session_ttl", 3600, "Session TTL", "Session Time-To-Live in seconds, default: 3600 seconds", "system", "general", "DataType_Integer", array("unit" => "seconds"));

//maintenance mode
Settings::create("maintenance_mode_enabled", false, "Maintenance Mode enabled (boolean)", "Maintenance Mode enabled (boolean), Default: false", "system", "general", "DataType_Boolean");
Settings::create("maintenance_text", "This domain is currently under scheduled maintenance mode. Sorry for the inconvenience! Look in a few minutes over again!", "Maintenance Text", "Text which is shown, if maintenance mode is enabled", "system", "general", "DataType_HTML");

//external tools
Settings::create("phpmyadmin_link", "#", "Link to PhpMyAdmin", "Link to PhpMyAdmin", "system", "tools", "DataType_URL");
Settings::create("webmail_link", "#", "Link to Webmail", "Link to Webmail", "system", "tools", "DataType_URL");

//send mail
Settings::create("send_mails_enabled", true, "Enable send mails from CMS", "Enable send mails from CMS - if disabled no mails can be sended! If deactivated, it can influence other features like registration.", "system", "mail", "DataType_Boolean");
Settings::create("sendmail_method", "PHPMail", "Method for sending mails", "Method for sending mails (class name of send mail implementation)", "system", "mail", "DataType_SelectBox", array("PHPMail", "SMTPMail"));
Settings::create("mail_sender_address", "none", "Sender mail address", "sender mail address, e.q. admin@example.com", "system", "mail", "DataType_Mail");
Settings::create("mail_sender_name", "", "Sender name", "Name of mail sender, e.q. John Doe", "system", "mail", "DataType_String");
Settings::create("mail_reply_to", "", "Reply-to mail address", "Reply-to mail address, e.q. admin@example.com", "system", "mail", "DataType_Mail");
Settings::create("mail_signature", "", "Mail Signature", "This text will be added as footer on all mails.", "system", "mail", "DataType_Text");
Settings::create("mail_default_charset", "utf-8", "Default mail charset", "Default mail charset, default: utf-8", "system", "mail", "DataType_String");

//registration
Settings::create("registration_enabled", false, "Registration enabled", "Registration enabled", "system", "general", "DataType_Boolean");
Settings::create("agb_page", "agb", "Terms of use Page", "Terms of use page", "system", "general", "DataType_String");
Settings::create("username_min_length", 4, "Minimal length (characters) of allowed username", "Minimal length (characters) of allowed username", "general", "general", "DataType_Integer");
Settings::create("username_max_length", 20, "Maximal length (characters) of allowed usernames", "Maximal length (characters) of allowed usernames", "general", "general", "DataType_Integer");
Settings::create("username_regex", "a-zA-Z0-9\.\-", "Username Regex", "Allowed characters of usernames (regex)", "system", "general", "DataType_String");
Settings::create("password_min_length", 6, "Minimal password length", "Minimal password length, default: 8", "system", "general", "DataType_Integer");
Settings::create("password_max_length", 64, "Maximal password length", "Maximal password length, default: 8", "system", "general", "DataType_Integer");
Settings::create("register_activation_method", "auto", "Register Activation Method", "Activation method for new user accounts, Default: auto (which means, that users are automatically activated)", "system", "general", "DataType_SelectBox", array("auto", "mail_verification", "manual_verification"));
Settings::create("default_main_group", 2, "Default main group ID", "ID of default main group, Default: 2 (registered users)", "system", "general", "DataType_Integer");

//captcha
Settings::create("captcha_enabled", true, "Captcha enabled", "Option, if captcha is enabled on forms", "system", "general", "DataType_Boolean");
Settings::create("captcha_class_name", "ReCaptcha", "Captcha Classname", "Captcha Classname", "system", "general", "DataType_String");
Settings::create("recaptcha_website_key", "", "reCAPTCHA website key", "reCAPTCHA website key, provided by google", "system", "general", "DataType_String");
Settings::create("recaptcha_secret_key", "", "reCAPTCHA secret key", "reCAPTCHA secret key, provided by google", "system", "general", "DataType_String");

//cronjob
Settings::create("cronjob_enabled", true, "Cronjob enabled", "Option if cronjob is enabled", "system", "tasks", "DataType_Boolean");
Settings::create("cronjob_auth_key", "", "Cronjob Auth Key", "Only set this key, if you want, that only a restricted source can call cronjob.php file.", "owner", "tasks", "DataType_String");

//menuID
Settings::create("menu_plugin_settings_id", -1, "id of plugin settings menu", "id of plugin settings menu - Dont change this value manually!", "system", "hidden", "DataType_Integer", array(), false);

//user / ldap authentification
Settings::create("default_authentificator", "LocalAuthentificator", "Authentificator Class", "Classname of Authentificator method", "system", "user", "DataType_String", array(""), true);
Settings::create("user_default_title", "Registered User", "Default User Title", "default user title, shown on pages", "system", "user", "DataType_String", array(), true);

//oauth
Settings::create("oauth_key_length", 255, "oAuth key length", "Length of oauth key in characters", "system", "api", "DataType_Integer", array("unit" => "characters"), true);
Settings::create("oauth_expire_seconds", 86400, "oAuth key Validity", "oAuth key Validity in seconds", "system", "api", "DataType_Integer", array("unit" => "seconds"), true);

//logging
Settings::create("logging_provider", "EmptyLogProvider", "Class name of logging provider", "full class name of logging provider", "system", "logging", "DataType_String", array(), false);

//sidebar
Settings::create("default_sidebar_left", 1, "ID of default left sidebar", "ID of default left sidebar", "system", "sidebar", "DataType_SidebarChooser", array(), false);
Settings::create("default_sidebar_right", 2, "ID of default right sidebar", "ID of default right sidebar", "system", "sidebar", "DataType_SidebarChooser", array(), false);

$main_menuID = -1;
$local_menuID = -1;
$admin_menuID = -1;

//get main menuID
if (!Settings::contains("main_menuID")) {
	//create menu_names if absent
	$main_menuID = Menu::createMenuName("Main Menu", "main_menu");

	//set setting
	Settings::create("main_menuID", $main_menuID, "Main MenuID", "id of main menu (in menu area)", "system", "general", "DataType_MenuSelector", "", true, "none", "none", 1);
} else {
	$main_menuID = Settings::get("main_menuID");
}

//get admin area menuID
if (!Settings::contains("admin_menuID")) {
	//create menu_names if absent
	$admin_menuID = Menu::createMenuName("Admin Menu", "admin_menu");

	//set setting
	Settings::create("admin_menuID", $admin_menuID, "Admin MenuID", "id of admin menu (in admin area)", "system", "general", "DataType_MenuSelector", "", true, "none", "none", 2);
} else {
	$admin_menuID = Settings::get("admin_menuID");
}

//get local menuID
if (!Settings::contains("local_menuID")) {
	//create menu_names if absent
	$local_menuID = Menu::createMenuName("Default Local Menu", "local_menu");

	//set setting
	Settings::create("local_menuID", $local_menuID, "Default Local MenuID", "id of default local menu (in menu area)", "system", "general", "DataType_MenuSelector", "", true, "none", "none", 3);
} else {
	$local_menuID = Settings::get("local_menuID");
}

//create default folders, if absent
Folder::createFolderIfAbsent("/", false);
Folder::createFolderIfAbsent("/admin/", true, array("can_access_admin_area"), $admin_menuID, -1);

echo "Create default menu if absent...<br />";

//create menus if absent
Menu::createMenu(1, $main_menuID, "Home", "home", -1, "home", "page", "all", false, "none", 1, "user");
Menu::createMenu(2, $admin_menuID, "Dashboard", "admin/home", -1, "admin_home", "page", array("can_access_admin_area"), true, "fa fa-tachometer-alt", 1, "system");
Menu::createMenu(3, $admin_menuID, "Dashboard", "admin/home", 2, "", "page", array("can_access_admin_area"), true, "fa fa-tachometer-alt", 1, "system");
Menu::createMenu(4, $admin_menuID, "Settings", "admin/settings", 2, "", "page", array("can_see_global_settings"), true, "fa fa-cog", 2, "system");
Menu::createMenu(5, $admin_menuID, "Website", "", 2, "", "page", "none", false, "fa fa-desktop", 3, "system");
Menu::createMenu(6, $admin_menuID, "Updates", "admin/update", 2, "updates", "page", array("can_see_cms_version", "can_update_cms"), true, "fa fa-download", 4, "system");
Menu::createMenu(7, $admin_menuID, "Posts", "#", -1, "posts", "no_link", array("can_see_all_pages"), true, "fa fa-list-alt", 2, "system");
Menu::createMenu(8, $admin_menuID, "Pages", "admin/pages", -1, "pages", "page", array("can_see_all_pages"), true, "fa fa-file", 3, "system");
Menu::createMenu(9, $admin_menuID, "All Pages", "admin/pages", 8, "all_pages", "page", array("can_see_all_pages"), true, "fa fa-circle", 1, "system");
Menu::createMenu(10, $admin_menuID, "My Pages", "admin/my_pages", 8, "my_pages", "page", array("can_see_all_pages"), true, "fa fa-circle", 2, "system");
Menu::createMenu(11, $admin_menuID, "Media", "admin/media", -1, "media", "page", array("can_see_all_media", "can_upload_media"), true, "fa fa-file-image", 3, "system");
Menu::createMenu(12, $admin_menuID, "All Media", "admin/media", 11, "all_media", "page", array("can_see_all_media"), true, "fa fa-file-image", 1, "system");
Menu::createMenu(14, $admin_menuID, "Upload Media", "admin/media/upload", 11, "upload_media", "page", array("can_upload_media"), true, "fa fa-upload", 4, "system");
Menu::createMenu(15, $admin_menuID, "Menu", "admin/menu", -1, "menu", "page", array("can_see_menus", "can_edit_menus"), true, "fa fa-anchor", 5, "system");
Menu::createMenu(16, $admin_menuID, "Users", "#", -1, "admin_users", "no_link", array("can_see_all_users", "can_create_user", "can_edit_users"), true, "fa fa-users", 6, "system");

Menu::createMenu(17, $admin_menuID, "All Users", "admin/users", 16, "all_users", "page", array("can_see_all_users"), true, "fa fa-id-card", 1, "system");
Menu::createMenu(18, $admin_menuID, "Create User", "admin/create_user", 16, "create_user", "page", array("can_create_user"), true, "fa fa-user-plus", 2, "system");
Menu::createMenu(19, $admin_menuID, "Groups", "admin/groups", 16, "groups", "page", array("can_see_all_groups"), true, "fa fa-users", 3, "system");
Menu::createMenu(20, $admin_menuID, "My groups", "admin/my_groups", 16, "admin_own_groups", "page", array("can_see_own_groups"), true, "fa fa-id-badge", 4, "system");
Menu::createMenu(21, $admin_menuID, "My profile", "admin/profile", 16, "admin_own_profile", "page", array("can_see_own_profile", "can_edit_own_profile"), true, "fa fa-user-circle", 5, "system");
Menu::createMenu(22, $admin_menuID, "Change password", "admin/change_password", 16, "admin_password", "page", array("can_edit_own_password"), true, "fa fa-key", 6, "system");

Menu::createMenu(25, $admin_menuID, "Design", "admin/design", -1, "design", "page", array("can_see_global_settings"), true, "fa fa-paint-brush", 7, "system");

Menu::createMenu(35, $admin_menuID, "Plugins", "admin/plugins", -1, "plugins", "no_link", array("can_see_installed_plugins"), true, "fa fa-cubes", 8, "system");
Menu::createMenu(36, $admin_menuID, "Plugins", "admin/plugins", 35, "plugins_page", "page", array("can_see_installed_plugins"), true, "fa fa-cubes", 1, "systen");
Menu::createMenu(37, $admin_menuID, "Settings", "#", 35, "plugins_settings", "no_link", array("can_see_installed_plugins"), true, "fa fa-cogs", 100, "system");

Settings::set("menu_plugin_settings_id", 37);

Menu::createMenu(45, $admin_menuID, "Tools", "#", -1, "tools", "no_link", array("none"), true, "fa fa-wrench", 9, "system");

Menu::createMenu(53, $admin_menuID, "phpinfo()", "admin/phpinfo", 45, "phpinfo", "page", array("can_see_phpinfo"), true, "fab fa-php", 9, "system");
Menu::createMenu(54, $admin_menuID, "PhpMyAdmin", "settings:phpmyadmin_link", 45, "phpmyadmin", "dynamic_link", array("can_see_phpmyadmin_menu"), true, "fa fa-laptop", 10, "system");
Menu::createMenu(55, $admin_menuID, "Webmail", "settings:webmail_link", 45, "webmail", "dynamic_link", array("can_see_webmail_menu"), true, "fa fa-envelope", 11, "system");
Menu::createMenu(56, $admin_menuID, "Send Mail", "admin/sendmail", 45, "sendmail", "page", array("can_send_board_mails"), true, "fa fa-envelope-open", 12, "system");
Menu::createMenu(57, $admin_menuID, "Clear Cache", "admin/clearcache", 45, "clear_cache", "page", array("can_clear_cache"), true, "fa fa-trash", 12, "system");

Menu::createMenu(60, $admin_menuID, "Settings", "#", -1, "settings", "no_link", array("can_see_global_settings"), true, "fa fa-cogs", 10, "system");
Menu::createMenu(61, $admin_menuID, "Settings", "admin/settings", 60, "", "page", array("can_see_global_settings", "can_edit_global_settings"), true, "fa fa-cog", 1, "system");

Menu::createMenu(100, $main_menuID, "lang_Admin Area", "admin/home", -1, "", "page", array("can_access_admin_area"), true, "none", 2, "user");
Menu::createMenu(101, $main_menuID, "lang_Login", "LOGIN_URL", -1, "login", "external_link", "not_logged_in", false, "none", 3, "user");
Menu::createMenu(102, $main_menuID, "lang_Logout", "LOGOUT_URL", -1, "logout", "external_link", "all", true, "none", 100, "user");

//privacy policy & imprint
Menu::createMenu(200, $main_menuID, "lang_Privacy Policy", "privacy-policy", -1, "privacy_policy", "page", array("none"), false, "none", 101, "system");
Menu::createMenu(201, $main_menuID, "lang_Imprint", "imprint", -1, "imprint", "page", array("none"), false, "none", 102, "system");

echo "Create default pages if absent...<br />";

Page::createIfAbsent("home", "Home", "IndexPage", "Home page", "/");
Page::createIfAbsent("error404", "Error 404", "Error404Page", "Error 404 - Couldn't find this page.", "/", -1, -1, -1, false);
Page::createIfAbsent("login", "Login", "LoginPage", "", "/", -1, -1, -1, false);
Page::createIfAbsent("logout", "Logout", "LogoutPage", "", "/", -1, -1, -1, false);
Page::createIfAbsent("register", "Registration", "RegisterPage", "", "/", -1, -1, -1, false);

//only at installation process
Page::createIfAbsent("privacy-policy", "lang_Privacy Policy", "HTMLPage", "Private Policy - Add privaty policy here", "/", -1, -1, -1, false);
Page::createIfAbsent("imprint", "lang_Imprint", "HTMLPage", "Imprint - Add contact data here", "/", -1, -1, -1, false);

Page::createIfAbsent("user/verify_mail", "Mail Verification", "MailVerifyPage", "", "/user/", -1, -1, -1, false, true, false);

//create robots.txt page
Page::createIfAbsent("robots.txt", "Robots.txt", "RobotsPage", "", "/", -1, -1, -1, false, true, false);

//create sitemap page
Page::createIfAbsent("sitemap.xml", "Sitemap", "SitemapPage", "", "/", -1, -1, -1, false, true, false);

//create forbidden pages
Page::createIfAbsent("error403", "Error 403", "Error403Page", "Error 403 - Forbidden!<br /> You dont have permissions to access this page or folder. Maybe you have to login.", "/", -1, -1, -1, false);

echo "Create admin pages if absent...<br />";
Page::createIfAbsent("admin/home", "Admin Dashboard", "Admin_Dashboard", "", "/admin/", -1, -1, -1, false, true, false);
Page::createIfAbsent("admin/plugins", "lang_Plugins", "PluginsPage", "", "/admin/", -1, -1, -1, false, true, false);
Page::createIfAbsent("admin/plugin_installer", "lang_Plugin Installer", "PluginInstallerPage", "", "/admin/", -1, -1, -1, false, true, false);
Page::createIfAbsent("admin/change_password", "lang_Change password", "ChangePasswordPage", "", "/admin/", -1, -1, -1, false, true, false);
Page::createIfAbsent("admin/sendmail", "lang_Send Mail", "SendMailPage", "", "/admin/", -1, -1, -1, false, true, false);
Page::createIfAbsent("admin/clearcache", "lang_Clear cache", "ClearCachePage", "", "/admin/", -1, -1, -1, false, true, false);

//create some tool pages
Page::createIfAbsent("admin/phpinfo", "phpinfo()", "PHPInfoPage", "", "/admin/", -1, -1, -1, false, true, false);

//admin pages
Page::createIfAbsent("admin/settings", "Settings", "SettingsPage", "", "/admin/", -1, -1, -1, false, true, false);
Page::createIfAbsent("admin/pages", "Pages", "PageListPage", "", "/admin/", -1, -1, -1, false, true, false);

echo "Create default page types if absent...<br />";

PageType::createPageType("HTMLPage", "HTML page", false, 1);//order 1 - so show as first page type in admin area
PageType::createPageType("Error404Page", "Error 404 page", true);
PageType::createPageType("Error403Page", "Error 403 page", true);
PageType::createPageType("IndexPage", "index page (supports extra template)", true);
PageType::createPageType("LoginPage", "Login page", true);
PageType::createPageType("LogoutPage", "Logout page", true);
PageType::createPageType("SitemapPage", "Sitemap page", true);
PageType::createPageType("ChangePasswordPage", "Change password", true);

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
Permissions::createOrUpdateCategory("users", "Users", 2);//user permissions, like "can_create_user"
Permissions::createOrUpdateCategory("groups", "Groups", 3);
Permissions::createOrUpdateCategory("pages", "Pages", 4);
Permissions::createOrUpdateCategory("media", "Media", 5);
Permissions::createOrUpdateCategory("permissions", "Permissions", 6);
Permissions::createOrUpdateCategory("plugins", "Plugins", 7);
Permissions::createOrUpdateCategory("admin", "Admin", 8);

echo "Create default permissions...<br />";
//general permissions
Permissions::createPermission("can_access_admin_area", "Can access admin area", "Can access admin area", "admin", "system", 1);
Permissions::createPermission("can_edit_own_password", "Can edit own password", "Can edit own password", "general", "system", 2);
Permissions::createPermission("can_edit_own_mail", "Can edit his own mail address", "Can edit his own mail address", "general", 3);
Permissions::createPermission("can_edit_own_profile", "Can edit own profile", "Can edit own profile (except mail & password)", "general", 4);

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

//group permissions
Permissions::createPermission("can_see_all_groups", "Can see all groups", "Can see list with all groups", "groups", "system", 1);
Permissions::createPermission("can_see_own_groups", "Can see own groups", "Can see list with own groups", "groups", "system", 2);
Permissions::createPermission("can_edit_all_groups", "Can edit all groups", "Can edit all groups", "groups", "system", 3);
Permissions::createPermission("can_edit_own_groups", "Can edit own groups", "Can edit own groups, where user is group leader", "groups", "system", 4);

//page permissions
Permissions::createPermission("can_see_all_pages", "Can see all pages in admin area", "pages", "pages", "system", 1);
Permissions::createPermission("can_create_pages", "Can create pages", "Can create pages", "pages", "system", 2);
Permissions::createPermission("can_edit_own_pages", "Can edit own pages", "Can edit pages which was created by user", "pages", "system", 3);
Permissions::createPermission("can_edit_all_pages", "Can edit all pages", "Can edit all pages, including pages which was created by other users", "pages", "system", 4);
Permissions::createPermission("can_publish_own_pages", "Can publish his own pages", "Can publish his own pages", "pages", "system", 5);
Permissions::createPermission("can_publish_all_pages", "Can publish all pages", "Can publish all pages, including pages which was created by other users", "pages", "system", 6);
Permissions::createPermission("can_unlock_all_pages", "Can unlock all pages", "Can unlock all pages from every user", "pages", "system", 7);
Permissions::createPermission("can_delete_own_pages", "Can delete own pages", "Can delete pages which was created by user", "pages", "system", 8);
Permissions::createPermission("can_delete_all_pages", "Can delete all pages", "Can delete all pages, including pages which was created by other users", "pages", "system", 9);

//media permissions
Permissions::createPermission("can_see_all_media", "Can see all media", "Can see all media files", "media", "system", 1);
Permissions::createPermission("can_see_own_media", "Can see own media", "Can see own media files", "media", "system", 2);
Permissions::createPermission("can_upload_media", "Can upload media", "Can upload media files", "media", "system", 3);

//menu permissions
Permissions::createPermission("can_see_menus", "Can see menus", "Can see menus", "menu", "system", 1);
Permissions::createPermission("can_edit_menus", "Can edit menus", "Can edit menus", "menu", "system", 2);

//plugin permissions
Permissions::createPermission("can_see_installed_plugins", "Can see installed plugins", "Can see installed plugins", "plugins", "system", 1);
Permissions::createPermission("can_install_plugins", "Can install plugins", "User is allowed to install plugins", "plugins", "system", 2);

//admin permissions
Permissions::createPermission("can_see_cms_version", "Can see version of CMS system", "Can see version of CMS system", "admin", "system", 1);
Permissions::createPermission("can_update_cms", "Can update CMS system", "Can update CMS system", "admin", "system", 2);
Permissions::createPermission("can_see_global_settings", "Can see global CMS settings", "Can see global CMS settings", "admin", "system", 3);
Permissions::createPermission("can_edit_global_settings", "Can edit global settings", "Can edit global settings", "admin", "system", 4);
Permissions::createPermission("can_see_phpinfo", "Can see phpinfo()", "Can see phpinfo()", "admin", "system", 5);
Permissions::createPermission("can_see_system_info", "Can see system information", "Can see system information, like os or opcache information", "admin", "system", 6);
Permissions::createPermission("can_see_phpmyadmin_menu", "Can see PhpMyAdmin menu", "Can see PhpMyAdmin menu", "admin", "system", 7);
Permissions::createPermission("can_see_webmail_menu", "Can see Webmail menu", "Can see Webmail menu", "admin", "system", 8);
Permissions::createPermission("can_send_board_mails", "Can send mails to users", "Can send mails to users", "admin", "system", 9);
Permissions::createPermission("can_clear_cache", "Can clear cache", "Can clear cache", "admin", "system", 10);
Permissions::createPermission("super_admin", "Is super admin and CAN EVERYTHING", "Is super admin and CAN EVERYTHING (overrides all other values!)", "admin", "system", 11);

echo "Set default permissions for userID 1...<br />";
$user_rights = new UserRights(1);

//userID 1 should be super_admin
$user_rights->setRight("super_admin", 1);

echo "Set default permissions for userID -1 (guest)...<br />";
$user_rights = new UserRights(-1);
$user_rights->setRight("not_logged_in", 1);

echo "Create default robots.txt rules...<br />";
Robots::addRule("DISALLOW", "/system/*");
Robots::addRule("DISALLOW", "/cache/*");
Robots::addRule("DISALLOW", "/docs/*");
Robots::addRule("DISALLOW", "/plugins/*");
Robots::addRule("DISALLOW", "/styles/*");
Robots::addRule("DISALLOW", "/admin/*");

//dont allow indexing of privacy policy and imprint pages, because they can contain sensitive information
Robots::addRule("DISALLOW", "/privacy-policy");
Robots::addRule("DISALLOW", "/imprint");

echo "Create default administrator user if absent...<br />";
//User::createIfIdAbsent(0, "system", md5(time() . "test_"), "admin1@example.com", 1, "System", 1);
User::createIfIdAbsent(1, "admin", "admin", "admin@example.com", 1, "Administrator", 1);

echo "Add PluginInstaller plugins...<br />";
PluginInstaller::addInstallerPluginIfAbsent("EventInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/eventinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("PermissionInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/permissioninstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("PageTypeInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/pagetypeinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("PageInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/pageinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("StoreInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/storeinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("FilePermissionsInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/filepermissionsinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("ApiMethodInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/apimethodinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("MenuInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/menuinstaller.php");
PluginInstaller::addInstallerPluginIfAbsent("SettingsInstaller", "system/packages/com.jukusoft.cms.plugin/extensions/settingsinstaller.php");

echo "Add apimethod 'oauth'...<br />";
ApiMethod::addMethod("oauth", "ApiOAuth", "apiOAuth", "package_com.jukusoft.cms.api");

echo "Add default tasks...<br />";
Task::createStaticMethodTask("Cleanup outdated oauth tokens", "ApiOAuth", "removeAllOutdatedTokens", 1440, "oauth_cleanup_tokens", "system", array(), false);//cleanup outdated oauth tokens every day

echo "Add default widgets...<br />";
WidgetType::register("TextWidget", "Text Widget", "Widget which shows text without html code.", true, "system");
WidgetType::register("HTMLWidget", "HTML Widget", "Widget which shows html code.", true, "system");

//TODO: remove this lines
echo "Add default sidebar widgets...<br />";
HTMLWidget::create(2, "Latest News", "<h4>New Website Launched</h4>
            <h5>August 1st, 2013</h5>
            <p>2013 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href=\"#\">Read more</a></p>
            <p></p>
            <h4>New Website Launched</h4>
            <h5>August 1st, 2013</h5>
            <p>2013 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href=\"#\">Read more</a></p>", "default_right_widget_1");
HTMLWidget::create(2, "Useful links", "<ul>
                <li><a href=\"#\">link 1</a></li>
                <li><a href=\"#\">link 2</a></li>
                <li><a href=\"#\">link 3</a></li>
                <li><a href=\"#\">link 4</a></li>
            </ul>", "default_right_widget_2");
HTMLWidget::create(2, "Search", "<form method=\"post\" action=\"#\" id=\"search_form\">
                <p>
                    <input class=\"search\" type=\"text\" name=\"search_field\" placeholder=\"Enter keywords\" />
                    <input class=\"search_button\" name=\"search\" type=\"submit\" value=\"&#x1f50d;\" />
                </p>
            </form>", "default_right_widget_3");

echo "Clear gettext cache<br />";
PHPUtils::clearGetTextCache();

echo "Clear cache<br />";
Cache::clear();

echo "<br /><br />Finished DB Upgrade!";

if (file_exists(ROOT_PATH . "setup/add-install.php")) {
	echo "<br />Call add-install.php...<br />";

	require(ROOT_PATH . "setup/add-install.php");

	echo "<br /><br />Finished additional Upgrade!";
}

?>
