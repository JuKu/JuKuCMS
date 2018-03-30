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
 * Date: 30.03.2018
 * Time: 16:25
 */

$start_time = microtime(true);

$end_time = microtime(true);
$exec_time = $end_time - $start_time;

define('ROOT_PATH', dirname(__FILE__) . "/../");

require(ROOT_PATH . "system/packages/com.jukusoft.cms.xtpl/xtpl/xtemplate.class.php");
require(ROOT_PATH . "system/packages/com.jukusoft.cms.xtpl/xtpl/caching_xtemplate.class.php");

if (!file_exists(ROOT_PATH . "/twig/performance/cache/")) {
	mkdir(ROOT_PATH . "/twig/performance/cache/");
}

$template = new CachingXTemplate(dirname(__FILLE__) . "/index.tpl");
$template->cache_dir = ROOT_PATH . "/twig/performance/cache";

$template->assign("CHARSET", "UTF-8");
$template->assign("TITLE", "My title");
$template->assign("CSS_HASH_ALL", "" . md5("test"));
$template->assign("JS_HASH_ALL_HEADER", md5("test"));
$template->assign("JS_HASH_ALL_FOOTER", md5("test"));
$template->assign("HTML_TEXT", "<b>A bold text</b>");
$template->assign("{BASE_URL}", "/twig_performance/");
$template->assign("STYLE_PATH", "styles/");
$template->assign("USERID", -1);
$template->assign("USERNAME", "Guest");
$template->assign("LOGOUT_URL", "logout.html");
$template->assign("CONTENT", "test content");
$template->assign("FOOTER", "FOOTER");
$template->assign("COPYRIGHT", "Copyright (c) 2018 JuKuSoft.com");
$template->assign("VERSION", "1.0.0");
$template->assign("BUILD", "1001");

$template->parse("main");
echo $template->text("main");

echo "<!-- Execution time: " . $exec_time . " seconds -->";

?>
