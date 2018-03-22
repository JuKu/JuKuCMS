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
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 22.03.2018
 * Time: 17:33
 */

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

ob_start("ob_gzhandler");

//set javascript header
header("Content-Type: application/javascript");

Events::throwEvent("init_js");

$style = "";
$position = "header";

//get required style
if (!isset($_REQUEST['style']) || empty($_REQUEST['style'])) {
	echo "No style set. Use css.php?style=your-style-name .";
	exit;
}

$style = $_REQUEST['style'];
$media = "ALL";

//check, if stlye name is valide
$validator = new Validator_Filename();

if (!$validator->isValide($style)) {
	echo "Invalide style name '" . htmlentities($style) . "' (only allowed characters: a-z, A-Z and 0-9)!";
	exit;
}

$style = $validator->validate($style);

//check, if style exists
if (!file_exists(STYLE_PATH . $style)) {
	echo "Style '" . $style . "' doesnt exists!";
	exit;
}

if (isset($_REQUEST['media']) && !empty($_REQUEST['media'])) {
	if (!$validator->isValide($_REQUEST['media'])) {
		echo "Invalide media '" . htmlentities($_REQUEST['media']) . "'!";
		exit;
	}

	$media = $validator->validate($_REQUEST['media']);
}

if (isset($_REQUEST['position']) && !empty($_REQUEST['position'])) {
	if (!$validator->isValide($_REQUEST['position'])) {
		echo "Invalide position '" . htmlentities($_REQUEST['position']) . "'!";
		exit;
	}

	$position = $validator->validate($_REQUEST['position']);
}

//TODO: add code here

//flush gzip cache
ob_end_flush();

Events::throwEvent("after_show_js");

?>
