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

	public static function strEqs (string $str1, string $str2) : bool {
		return strcmp($str1, $str2) === 0;
	}

	/**
	 * Generate a random string, using a cryptographically secure
	 * pseudorandom number generator (random_int)
	 *
	 * For PHP 7, random_int is a PHP core function
	 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
	 *
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 *
	 * @link https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
	 *
	 * @return string
	 */
	public static function randomString(int $length, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : string {
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;

		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}

		return $str;
	}

	public static function getHostname () : string {
		if (function_exists("gethostname")) {
			return gethostname();
		} else {
			//Or, an option that also works before PHP 5.3
			return php_uname('n');
		}
	}

}
