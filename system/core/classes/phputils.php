<?php

/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 12.07.2016
 * Time: 23:53
 */
class PHPUtils {

    public static function isMemcacheAvailable () {
        return class_exists('Memcache');
    }

    public static function isMemcachedAvailable () {
        return class_exists('Memcached');
    }

}