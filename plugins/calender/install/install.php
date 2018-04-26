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

if (!defined("PLUIGIN_INSTALLER")) {
	echo "You cannot access this file directly!";
	exit;
}

/**
 * table plugin_calender_calenders
 *
 * Plugin: calender
 */

//create or upgrade test table
$table = new DBTable("plugin_calender_calenders", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addVarchar("title", 255, true);
$table->addVarchar("unique_name", 255, true);

//add keys to table
$table->addPrimaryKey("id");
$table->addUnique("unique_name");

//create or upgrade table
$table->upgrade();

?>
