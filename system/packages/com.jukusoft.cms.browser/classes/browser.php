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
 * Time: 01:57
 */

class Browser {

	//cached values
	protected static $isMobile = false;
	protected static $mobile_checked = false;
	protected static $isTablet = false;
	protected static $tablet_checked = false;

	//https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.php

	/**
	 * check, if browser is mobile
	 *
	 * @return true, if browser is mobile
	 */
	public static function isMobile () : bool {
		//in-memory cache
		if (self::$mobile_checked) {
			return self::$isMobile;
		}

		//customized from: https://stackoverflow.com/questions/4117555/simplest-way-to-detect-a-mobile-device
		//https://stackoverflow.com/questions/4117555/simplest-way-to-detect-a-mobile-device
		$value = preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|bo‌​ost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", self::getUserAgent());

		//cache values (in local in-memory cache)
		self::$isMobile = $value;
		self::$mobile_checked = true;

		return $value;
	}

	public static function isMobilePhone () : bool {
		throw new Exception("method Browser::isMobilePhone() isnt implemented yet.");

		//TODO: add code here
	}

	public static function isTablet () : bool {
		//in-memory cache
		if (self::$tablet_checked) {
			return self::$isTablet;
		}

		//https://www.phpclasses.org/browse/file/48225.html
		//https://mobiforge.com/design-development/tablet-and-mobile-device-detection-php

		//TODO: ATTENTION! Rewrite this method so it will result into better performance!

		$user_agent = self::getUserAgent();

		$tablet_browser = 0;

		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($user_agent))) {
			$tablet_browser++;
		}

		if (strpos(strtolower($user_agent),'opera mini') > 0) {
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));

			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				$tablet_browser++;
			}
		}

		$value = $tablet_browser > 0;

		//cache values (in local in-memory cache)
		self::$isTablet = $value;
		self::$tablet_checked = true;

		return $value;
	}

	public static function isAppleiOS () : bool {
		$user_agent = self::getUserAgent();

		$iPod    = stripos($user_agent,"iPod");
		$iPhone  = stripos($user_agent,"iPhone");
		$iPad    = stripos($user_agent,"iPad");
		//$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
		//$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

		return $iPod !== false || $iPhone !== false || $iPad !== false;
	}

	public static function isAndroid () : bool {
		return stripos(self::getUserAgent(),'android') !== false;
	}

	public static function getUserAgent () : string {
		$user_agent = "";

		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$user_agent = strtolower(htmlentities($_SERVER['HTTP_USER_AGENT']));
		}

		//throw event, so plugins can modify user agent
		Events::throwEvent("get_user_agent", array(
			'user_agent' => &$user_agent,
		));

		return $user_agent;
	}

}

?>
