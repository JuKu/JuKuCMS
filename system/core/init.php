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

//check php version
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
    echo "CMS is required PHP 7.0.0 or greater! Please install PHP 7.";
    exit;
}

//define some constants
define('CACHE_PATH', ROOT_PATH . "cache/");
define('CONFIG_PATH', ROOT_PATH . "config/");
define('STORE_PATH', ROOT_PATH . "store/");

//require config
require(ROOT_PATH . "config/config.php");

//require autoloader cache
require(ROOT_PATH . "system/core/classes/autoloadercache.php");

//initialize autoloader cache
AutoLoaderCache::init();

//load classes
AutoLoaderCache::load();

?>
