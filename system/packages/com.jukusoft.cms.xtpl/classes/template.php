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

class Template {

	protected $template = null;
	protected static $registeredTemplate = array();

	public function __construct ($file, Registry &$registry = null) {
		if ($registry == null) {
			$registry = Registry::singleton();
		}

		if (!class_exists("XTemplate", false)) {
			require_once(ROOT_PATH . "system/packages/com.jukusoft.cms.xtpl/xtpl/xtemplate.class.php");
		}

		if (isset(self::$registeredTemplate[$file])) {
			$file = self::$registeredTemplate[$file];
		}

		//find file
		$file = self::findTemplate($file, $registry);

		$this->template = new XTemplate($file);
		$this->template->assign("REGISTRY", $registry->listSettings());

		//set CSRF token
		$this->template->assign("CSRF_TOKEN", Security::getCSRFToken());

		//set domain, current page and so on
		$this->template->assign("DOMAIN", DomainUtils::getCurrentDomain());
		$this->template->assign("BASE_URL", DomainUtils::getBaseURL());
		$this->template->assign("CURRENT_URL", DomainUtils::getURL());
		$this->template->assign("FOLDER", $registry->getSetting("folder"));

		//set language settings
		$this->template->assign("PREF_LANG", $registry->getSetting("pref_lang"));
		$this->template->assign("LANG_TOKEN", $registry->getSetting("lang_token"));

		$redirect_url = urlencode(DomainUtils::getURL());

		if (isset($_REQUEST['redirect_url']) && !empty($_REQUEST['redirect_url'])) {
			$redirect_url = $_REQUEST['redirect_url'];
		}

		$domain = $registry->getObject("domain");
		$this->template->assign("HOME_PAGE", $domain->getHomePage());
		$this->template->assign("LOGIN_PAGE", Settings::get("login_page", "login"));
		$this->template->assign("LOGIN_URL", DomainUtils::getBaseURL() . "/" . Settings::get("login_page", "login") . "?action=login&redirect_url=" . $redirect_url);
		$this->template->assign("LOGOUT_PAGE", Settings::get("logout_page", "logout"));
		$this->template->assign("LOGOUT_URL", DomainUtils::getBaseURL() . "/" . Settings::get("logout_page", "logout") . "?csrf_token=" . urlencode(Security::getCSRFToken()));

		//set user variables
		$this->template->assign("USERID", User::current()->getID());
		$this->template->assign("USERNAME", User::current()->getUsername());

		$style_name = $registry->getSetting("current_style_name");
		$this->template->assign("STYLE_PATH",DomainUtils::getBaseURL() . "/styles/" . $style_name . "/");

		Events::throwEvent("init_template", array(
			'file' => &$file,
			'template' => &$this,
			'template_instance' => &$this->template,
			'registry' => &$registry
		));

	}

	public function assign ($var, $value) {
		$this->template->assign($var, $value);
	}

	public function parse ($name = "main") {
		$this->template->parse($name);
	}

	public function getCode ($name = "main") {
		return $this->template->text($name);
	}

	public static function registerTemplate ($template, $file) {
		self::$registeredTemplate[$template] = $file;
	}

	public static function clearTemplates () {
		self::$registeredTemplate = array();
	}

	public static function createTemplate ($file) {
		$class = (String) __CLASS__;
		return new $class($file);
	}

	public static function getName () {
		return __CLASS__;
	}

	public static function findTemplate (string $tpl_name, Registry &$registry) : string {
		if (strpos($tpl_name, ".tpl") !== FALSE) {
			//remove file extension
			$tpl_name = str_replace(".tpl", "", $tpl_name);
		}

		//check, if file path was set
		if (file_exists($tpl_name . ".tpl")) {
			return $tpl_name . ".tpl";
		}

		//find file
		$current_style = $registry->getSetting("current_style_name");
		$style_path = STYLE_PATH . $current_style . "/";

		$array = explode("_", $tpl_name);

		if (sizeof($array) == 3) {
			//plugin or style template
			if ($array[0] === "plugin") {
				return PLUGIN_PATH . $array[1] . "/templates/" . $array[2] . ".tpl";
			} else {
				throw new Exception("templates with 2 '_' (expect 'plugin') arent supported yet.");
			}
		} else if (sizeof($array) == 1) {
			//search in style path
			if (file_exists($style_path . $tpl_name . ".tpl")) {
				return $style_path . $tpl_name . ".tpl";
			} else if (file_exists(STYLE_PATH . "default/" . $tpl_name . ".tpl")) {
				//use default template
				return STYLE_PATH . "default/" . $tpl_name . ".tpl";
			} else {
				throw new Exception("Coulnd't found template '" . $tpl_name . "' (search path: '" . $style_path . $tpl_name . ".tpl" . "'!");
			}
		} else {
			throw new IllegalStateException("Coulndt found template file '" . $tpl_name . "', because unknown array size: " . sizeof($array));
		}
	}

}

?>
