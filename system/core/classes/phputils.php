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

	public static function sendPOSTRequest (string $url, array $data = array()) {
		//check, if allow_url_fopen is enabled
		if (PHPUtils::isUrlfopenEnabled()) {
			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($data)
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);

			if ($result === FALSE) {
				return false;
			}

			return $result;
		} else {
			//try to use curl instead

			//https://stackoverflow.com/questions/2138527/php-curl-http-post-sample-code?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa

			//create a new curl session
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//"postvar1=value1&postvar2=value2&postvar3=value3"

			//receive server response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec ($ch);

			//close curl session
			curl_close ($ch);

			return $result;
		}
	}

	public static function isUrlfopenEnabled () : bool {
		$res = ini_get("allow_url_fopen");

		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public static function isCurlAvailable () : bool {
		/*if  (in_array  ('curl', get_loaded_extensions())) {
			return true;
		}
		else {
			return false;
		}*/

		return function_exists('curl_version');
	}

	public static function isGettextAvailable () : bool {
		return function_exists("gettext");
	}

	public static function clearGetTextCache () {
		//clear stats cache, often this clears also gettext cache
		clearstatcache();
	}

	public static function checkSessionStarted (bool $throw_exception = true) : bool {
		if (session_status() !== PHP_SESSION_ACTIVE) {
			if ($throw_exception) {
				throw new IllegalStateException("session wasnt started yet.");
			}

			return false;
		}

		return true;
	}

	public static function containsStr (string $haystack, string $needle) : bool {
		return strpos($haystack, $needle) !== FALSE;
	}

}
