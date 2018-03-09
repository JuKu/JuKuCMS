<?php

class PHPUtils {

    public static function isMemcacheAvailable () {
        return class_exists('Memcache');
    }

    public static function isMemcachedAvailable () {
        return class_exists('Memcached');
    }

    public static function isModRewriteAvailable () {
    	if (function_exists("apache_get_modules")) {
    		if (in_array('mod_rewrite',apache_get_modules())) {
    			return true;
			}

			return false;
		}

		return false;
	}

}
