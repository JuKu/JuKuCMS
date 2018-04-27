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

if (!defined("PLUGIN_INSTALLER")) {
	echo "You cannot access this file directly!";
	exit;
}

/**
 * table plugin_workshops_workshops
 *
 * Plugin: workshops
 */

//create or upgrade test table
$table = new DBTable("plugin_workshops_workshops", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addVarchar("title", 255, true);
$table->addText("description", true);
$table->addVarchar("image", 600, true, "none");
$table->addVarchar("day", 255, true, "");
$table->addVarchar("time", 255, true, "");
$table->addVarchar("interval", 255, true, "");
$table->addVarchar("location", 255, true, "");
$table->addVarchar("responsible", 255, true, "");
$table->addInt("order", 10, true, false, 10);

//add keys to table
$table->addPrimaryKey("id");
$table->addIndex("order");

//create or upgrade table
$table->upgrade();

?>
