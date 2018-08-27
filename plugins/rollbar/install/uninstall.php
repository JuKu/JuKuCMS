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
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 26.04.2018
 * Time: 19:40
 */

if (!defined("PLUGIN_INSTALLER")) {
	echo "You cannot access this file directly!";
	exit;
}

$preferences = new Preferences("plugin_rollbar");
$preferences->clearAll();

//reset logging provider
Settings::set("logging_provider", "EmptyLogProvider");

?>
