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
 * This file is a performance benchmark for twig template engine
 *
 * @link https://twig.symfony.com/
 */

$start_time = microtime(true);

//define root path
define('ROOT_PATH', dirname(__FILE__) . "/../");

//require twig autoloader
require("../system/packages/com.jukusoft.cms.twig/twig/test_autoloader.php");

//register autoloader
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . "/");
$twig = new Twig_Environment($loader, array(
	'cache' => false
));

$twig->addGlobal("charset", "UTF-8");
$twig->addGlobal("title", "My title");

$template = $twig->load('index.html');

$template->addGlobal("charset", "UTF-8");
$template->addGlobal("title", "My title");
$template->addGlobal("CSS_HASH_ALL", "" . md5("test"));
$template->addGlobal("JS_HASH_ALL_HEADER", md5("test"));
$template->addGlobal("JS_HASH_ALL_FOOTER", md5("test"));
$template->addGlobal("HTML_TEXT", "<b>A bold text</b>");
$template->addGlobal("{BASE_URL}", "/twig_performance/");
$template->addGlobal("STYLE_PATH", "styles/");
$template->addGlobal("USERID", -1);
$template->addGlobal("USERNAME", "Guest");
$template->addGlobal("LOGOUT_URL", "logout.html");
$template->addGlobal("CONTENT", "test content");
$template->addGlobal("FOOTER", "FOOTER");
$template->addGlobal("COPYRIGHT", "Copyright (c) 2018 JuKuSoft.com");
$template->addGlobal("VERSION", "1.0.0");
$template->addGlobal("BUILD", "1001");

echo $template->render(array(
	'name' => 'Fabien',
	'title' => "My title",
	'charset' => "UTF-8"
));

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

echo "<!-- Execution time: " . $exec_time . " seconds -->";

?>
