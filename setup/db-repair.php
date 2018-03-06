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

echo "Create / Upgrade table <b>global_settings</b>...</br>";

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

echo "Finished!</br>";

/**
 * table global_settings_category
 *
 * Package: com.jukusoft.cms.settings
 */

echo "Create / Upgrade table <b>global_settings_category</b>...</br>";

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

echo "Finished!</br>";

?>
