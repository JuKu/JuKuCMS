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

//require twig autoloader
require("system/packages/com.jukusoft.cms.twig/twig/test_autoloader.php");

$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . "/twig_performance/");
$twig = new Twig_Environment($loader, array(
	'cache' => false
));

echo $twig->render('index.html', array('name' => 'Fabien'));

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

echo "<!-- Execution time: " . $exec_time . " seconds -->";

?>
