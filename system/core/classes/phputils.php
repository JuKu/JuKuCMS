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

	public static function startsWith ($haystack, $needle) : bool {
    	//https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php

		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public static function endsWith ($haystack, $needle) : bool {
		$length = strlen($needle);

		return $length === 0 || (substr($haystack, -$length) === $needle);
	}

	/**
	 * get IP address of client browser
	 *
	 * @return IPv4 / IPv6 address (up to 45 characters)
	 */
	public static function getClientIP () : string {
    	//https://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php

    	$ip = "";

		if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

}
