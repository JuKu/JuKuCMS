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
 * Shows CSS
 *
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 22.03.2018
 * Time: 13:25
 */

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/");

error_reporting(E_ALL);

require("system/core/init.php");

//reset OpCache in debug mode
if (CLEAR_OP_CACHE) {
	//http://php.net/manual/en/function.opcache-reset.php
	//http://php.net/manual/en/function.opcache-invalidate.php
	opcache_reset();
}

ob_start("ob_gzhandler");

//set css header
header("Content-Type: text/css");

Events::throwEvent("http_header");

Events::throwEvent("init_css");

$style = "";

//get required style
if (!isset($_REQUEST['style']) || empty($_REQUEST['style'])) {
	echo "No style set. Use css.php?style=your-style-name .";
	exit;
}

$style = $_REQUEST['style'];
$media = "ALL";
$position = "header";

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

//create css builder
$css_builder = new CSSBuilder();

//get style cache path
$css_cache_path = $css_builder->getCachePath($style, $media, $position);

//generate css file, if neccessary
if (!$css_builder->existsCache($style, $media, $position)) {
	$css_builder->generateCSS($style, $media, $position);
}

//http://blog.franky.ws/php-und-das-caching-via-http-header-etag/

$cache_strategy = Settings::get("css_cache_strategy", "expires_header");//etag / expires

//intelligent caching
if (file_exists($css_cache_path)) {
	if ($cache_strategy === "expires_header") {
		//https://www.electrictoolbox.com/php-caching-headers/

		//set expires header, so browser can cache this css file
		$seconds_to_cache = (int) Settings::get("css_cache_expires_header_ttl", "31536000");
		$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
		header("Expires: " . $ts);
		header("Pragma: cache");
		header("Cache-Control: max-age=" . $seconds_to_cache);
	} else if ($cache_strategy === "etag_header") {
		//get the last-modified-date of this very file
		$lastModified=filemtime($css_cache_path);

		//get a unique hash of this file (etag)
		$etagFile = md5_file($css_cache_path);

		//get the HTTP_IF_MODIFIED_SINCE header if set
		$ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);

		//get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
		$etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

		//set last-modified header
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");

		//set etag-header
		header("Etag: $etagFile");

		//make sure caching is turned on
		header('Cache-Control: public');

		//check if page has changed. If not, send 304 and exit
		if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified || $etagHeader == $etagFile) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
	} else if ($cache_strategy === "none") {
		//dont set browser cache header
	} else {
		echo "Unknown css_cache_strategy '" . $cache_strategy . "'!";
		exit;
	}
}

//load css builder
$css_builder->load($style, $media, $position);

//get css output
echo $css_builder->getBuffer();

//flush gzip cache
ob_end_flush();

//send logs to server
if (LOGGING_ENABLED) {
	Logger::send();
}

Events::throwEvent("after_show_css");

?>
