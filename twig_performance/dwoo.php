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
 * Date: 03.04.2018
 * Time: 20:16
 */

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
define('DWOO_PATH', ROOT_PATH . "system/packages/com.jukusoft.cms.dwoo/dwoo/lib/Dwoo/");

require(DWOO_PATH . "Core.php");
require(DWOO_PATH . "ITemplate.php");
require(DWOO_PATH . "Template/Str.php");
require(DWOO_PATH . "Template/File.php");

// Create the controller, it is reusable and can render multiple templates
$core = new Dwoo\Core();

// Create some data
$data = array('a'=>5, 'b'=>6);

$data['CHARSET'] = "UTF-8";
$data['TITLE'] = "My title";
$data['CSS_HASH_ALL'] = "" . md5("test");
$data['JS_HASH_ALL_HEADER'] = md5("test");
$data['JS_HASH_ALL_FOOTER'] = md5("test");
$data['HTML_TEXT'] = "<b>A bold text</b>";
$data['{BASE_URL}'] = "/twig_performance/";
$data['STYLE_PATH'] = "styles/";
$data['USERID'] = -1;
$data['USERNAME'] = "Guest";
$data['LOGOUT_URL'] = "logout.html";
$data['CONTENT'] = "test content";
$data['FOOTER'] = "FOOTER";
$data['COPYRIGHT'] = "Copyright (c) 2018 JuKuSoft.com";
$data['VERSION'] = "1.0.0";
$data['BUILD'] = "1001";

// Output the result
echo $core->get(__FILLE__ . "/index_dwoo.html", $data);

echo "<!-- Execution time: " . $exec_time . " seconds -->";

?>
