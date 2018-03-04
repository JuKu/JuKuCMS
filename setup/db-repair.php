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

echo "Create / Upgrade table <b>events</b>...</br>";

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

echo "Finished!</br>";

?>
