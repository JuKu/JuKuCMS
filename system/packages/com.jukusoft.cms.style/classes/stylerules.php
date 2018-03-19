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

class StyleRules {

	protected static $rules = array();
	protected static $initialized = false;

	protected static $allowed_types = array("DOMAIN", "FOLDER", "MEDIA", "LANGUAGE");

	/**
	 * default constructor
	 */
	public function __construct() {
		//
	}

	public static function getStyle (Registry &$registry, string $default_style_name) : string {
		//load all rules, if absent
		self::initIfAbsent();

		return self::applyRules(-1, $registry, $default_style_name);
	}

	protected static function applyRules (int $parentID, Registry &$registry, $default_value) : string {
		//get all root rules
		$root_rules = self::$rules[$parentID];

		$style_name = $default_value;

		//iterate through rules
		foreach ($root_rules as $rule) {
			$type = $rule['type'];
			$expected_value = $rule['expected_value'];

			//validate condition
			if (self::checkCondition($type, $expected_value, $registry)) {
				//set new default value
				$style_name = $rule['style_name'];
				$parentID = $rule['rule_id'];

				//search for next rules
				return self::applyRules($parentID, $registry, $style_name);

				break;
			}
		}

		return $style_name;
	}

	/**
	 * check, if a condition is true
	 *
	 * @param $type condition type ("DOMAIN", "FOLDER", "MEDIA", "LANGUAGE")
	 * @param $expected_value expected value
	 * @param $registry instance of Registry
	 *
	 * @return true, if condition is true
	 */
	public static function checkCondition (string $type, string $expected_value, Registry &$registry) : bool {
		$type = strtoupper($type);

		//check, if condition type is allowed
		if (!in_array($type, self::$allowed_types)) {
			throw new IllegalArgumentException("condition type '" . $type . "' is unknown.");
		}

		//TODO: check condition
		switch ($type) {
			case "DOMAIN":
				//get current domain
				$current_domain = $registry->getSetting("domain_name");

				//compare expected domain with
				return strcmp($current_domain, $expected_value) === 0;

				break;
			case "FOLDER":
				$page_folder = $registry->getSetting("folder");

				return PHPUtils::startsWith($page_folder, $expected_value);

				break;
			case "MEDIA":
				switch (strtoupper($expected_value)) {
					case "MOBILE":
						//mobile devices (mbile phone / tablet)
						return $registry->getSetting("isMobile");

						break;
					case "MOBILE_PHONE":
						//mobile phone (iOS / android phone)
						return Browser::isMobilePhone();

						break;
					case "TABLET":
						//tablet (iPad / android tablet)
						return Browser::isTablet();

						break;
					case "DESKTOP":
						//desktop browsers
						return !$registry->getSetting("isMobile");

						break;
					case "ANDROID":
						//android phones & tablets
						return Browser::isAndroid();

						break;
					case "IOS":
						//iPod / iPhone / iPad
						return Browser::isAppleiOS();

						break;
					case "ALL":
						//all devices
						return true;

						break;
				}

				break;
			case "PREF_LANG":
				//get prefered user language token
				$lang_token = Lang::getPrefLangToken();

				return strcmp($lang_token, strtolower($expected_value)) == 0;

				break;
			case "SUPPORTED_LANG":
				//get current user language token
				$lang_tokens = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

				//check, if language is supported by browser
				return stripos($lang_tokens, $expected_value) !== false;

				break;
			case "DEFAULT":
				//default value, if all other conditions are false
				return true;

				break;
			default:
				throw new IllegalStateException("Unknown style rule type: " . $type);
				break;
		}
	}

	public static function loadAllRules () {
		if (Cache::contains("style", "style-rules")) {
			self::$rules = Cache::get("style", "style-rules");
		} else {
			//get all style rules from database
			$rows = Database::getInstance()->listRows("SELECT * FROM `{praefix}style_rules` WHERE `activated` = '1' ORDER BY `order`; ");

			$rules = array();

			foreach ($rows as $row) {
				$parentID = $row['parent'];

				if (!isset($rules[$parentID])) {
					$rules[$parentID] = array();
				}

				$rules[$parentID][] = $row;
			}

			if (!isset($rules[-1])) {
				$rules[-1] = array();
			}

			self::$rules = $rules;

			//cache array
			Cache::put("style", "style-rules", self::$rules);
		}

		//set initialized flag
		self::$initialized = true;
	}

	protected static function initIfAbsent () {
		if (!self::$initialized) {
			self::loadAllRules();
		}
	}

}

?>
