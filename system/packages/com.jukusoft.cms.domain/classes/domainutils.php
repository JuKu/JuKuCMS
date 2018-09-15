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
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 05.03.2018
 * Time: 01:12
 */

class DomainUtils {

	public static function getTLD ($url) {
		$domain_tld = "";

		//http://news.mullerdigital.com/2013/10/30/how-to-get-the-domain-and-tld-from-a-url-using-php-and-regular-expression/

		preg_match("/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/", parse_url($url, PHP_URL_HOST), $domain_tld);

		return $domain_tld[0];
	}

	public static function isHTTPS () {
		return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off";
	}

	public static function getPort () {
		return (int) $_SERVER['SERVER_PORT'];
	}

	public static function isProxyUsed () {
		return isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST']);
	}

	public static function getHost () {
		$host = "";

		if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			$host = $_SERVER['HTTP_X_FORWARDED_HOST'];

			//because HTTP_X_FORWARDED_HOST can contains more than 1 host, we only want to get the last host name
			$elements = explode(',', $host);
			$host = end($elements);
		} else if (isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME'])) {
			$host = $_SERVER['SERVER_NAME'];
		} else if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
			$host = $_SERVER['HTTP_HOST'];
		} else {
			//unknown host

			//use server ip
			return htmlentities($_SERVER['SERVER_ADDR']);
		}

		if (PHPUtils::containsStr($host, "cms.")) {
			var_dump($_SERVER);
		}

		// Remove port number from host
		$host = preg_replace("%:\d+$%", "", $host);

		return trim($host);
	}

	/**
	 * get domain
	 *
	 * alias to getHost()
	 */
	public static function getDomain () {
		return self::getHost();
	}

	public static function getReferer () {
		return htmlentities($_SERVER['HTTP_REFERER']);
	}

	public static function getRequestMethod () {
		return htmlspecialchars($_SERVER['REQUEST_METHOD']);
	}

	public static function getRequestURI () {
		return htmlentities($_SERVER['REQUEST_URI']);
	}

	public static function getBaseURL (bool $without_protocol = false) {
		$url = "";

		if (!$without_protocol) {//add protocol
			if (self::isHTTPS()) {
				$url .= "https://";
			} else {
				$url .= "http://";
			}
		}

		//add domain
		$url .= self::getDomain();

		//check, if an specific server port is used
		if (self::getPort() != 80 && self::getPort() != 433) {
			$url .= ":" . self::getPort();
		}

		return $url;
	}

	/**
	 * generate an url for a page in this form: http(s)://<Domain><Base URL><Page>
	 */
	public static function generateURL (string $page, array $params = array()) : string {
		$params_str = "";

		if (count($params) > 0) {
			$params_str = "?";

			$array = "";

			foreach ($params as $key=>$value) {
				$array[] = $key . "=" . $value;
			}

			$params_str .= implode("&amp;", $array);
		}

		return self::getBaseURL() . "/" . $page . $params_str;
	}

	public static function getURL () {
		return self::getBaseURL() . self::getRequestURI();
	}

	/**
	 * faster implementation of getTld()
	 */
	public static function getCurrentDomain () {
		$host = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$domain = explode("?", $host);
		$host = $domain[0];
		$array = explode("/", $host);
		$host = $array[0];

		/*$domain = "";

		for ($i = 0; $i < count($array) - 1; $i++) {
			$domain .= $array[$i];
		}*/

		$array1 = explode(":", $host);
		$host = $array1[0];

		return /*$domain*/$host;
	}

	public static function getProtocol () : string {
		if (self::isHTTPS()) {
			return "https://";
		} else {
			return "http://";
		}
	}

}

?>
