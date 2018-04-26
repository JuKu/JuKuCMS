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
$table->addText("description", true);
$table->addEnum("type", array("public", "internal", "private"), true, "private");

//add keys to table
$table->addPrimaryKey("id");

//create or upgrade table
$table->upgrade();

/**
 * table plugin_calender_events
 *
 * Plugin: calender
 */

//create or upgrade test table
$table = new DBTable("plugin_calender_events", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("id", 10, true, true);
$table->addInt("calenderID", 10, true, false);
$table->addVarchar("title", 255, true);
$table->addText("description", true);
$table->addVarchar("image", 600, true, "none");
$table->addInt("all_day", 10, true, false, 0);
$table->addTimestamp("from_date", true, "0000-00-00 00:00:00");
$table->addTimestamp("to_date",true, "0000-00-00 00:00:00");
$table->addVarchar("color", 255, true, "none");
$table->addInt("activated", 10, true, false, 1);

//add keys to table
$table->addPrimaryKey("id");
$table->addIndex("calenderID");
$table->addIndex("from_date");
$table->addIndex("activated");

//create or upgrade table
$table->upgrade();

/**
 * table plugin_calender_group_rights
 *
 * Plugin: calender
 */

//create or upgrade test table
$table = new DBTable("plugin_calender_group_rights", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("groupID", 10, true, false);
$table->addInt("calenderID", 10, true, false);
$table->addEnum("value", array("read", "write", "owner"), true, "read");

//add keys to table
$table->addPrimaryKey(array("groupID", "calenderID"));

//create or upgrade table
$table->upgrade();

/**
 * table plugin_calender_user_rights
 *
 * Plugin: calender
 */

//create or upgrade test table
$table = new DBTable("plugin_calender_user_rights", Database::getInstance());
$table->setEngine("InnoDB");
$table->setCharset("utf8");

//fields
$table->addInt("userID", 10, true, false);
$table->addInt("calenderID", 10, true, false);
$table->addEnum("value", array("read", "write", "owner"), true, "read");

//add keys to table
$table->addPrimaryKey(array("userID", "calenderID"));

//create or upgrade table
$table->upgrade();

?>
