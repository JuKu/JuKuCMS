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
 * Memcached configuration
 *
 * PSF Framework doesnt require memcached, but you can use it to speed up your website.
 * Note: If you want to use memcached, you have also to configure in file cache.php
 *
 * Attention! - memcache and memcached arent the same!
 */

//Memcache Statistic Report: https://github.com/DBezemer/memcachephp

//http://php.net/manual/de/memcache.examples-overview.php
//http://php.net/manual/de/class.memcached.php

//you can configure more than 1 memcache server, if you want to use redundancy

$memcached_config = array(
    'server' => array(
        array(
            'host' => "127.0.0.1",
            'port' => "11211"
        ),
        /*array(
            'host' => "127.0.0.1",
            'port' => "11211"
        ),*/
    ),
    /**
     * authentification is optional with memcached
     *
     * If you want to use authentification, your memcached PECL extension has to be build with SASL!
     */
    'authentification' => array(
        /**
         * enabled:
         *
         * true - authentification for memcached is enabled
         * false - authentification will not be used for memcached
         */
        'enabled' => false,
        'username' => "",
        'password' => "",
    ),
    //some optional memcached options, for more options visit http://php.net/manual/de/memcached.constants.php
    'options' => array(
        Memcached::OPT_CONNECT_TIMEOUT => 10,
        Memcached::OPT_DISTRIBUTION => Memcached::DISTRIBUTION_CONSISTENT,
        Memcached::OPT_SERVER_FAILURE_LIMIT => 2,
        Memcached::OPT_RETRY_TIMEOUT => 1,
        Memcached::OPT_REMOVE_FAILED_SERVERS => true,

        //you can also use an prefix like in an mysql database for tables
        Memcached::OPT_PREFIX_KEY => "cms_",
    )
);

?>