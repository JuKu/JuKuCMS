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
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 08.03.2018
 * Time: 00:57
 */

class TwigTemplate {

	protected $template = null;
	protected static $registeredTemplate = array();

	public function __construct ($file) {
		require_once(ROOT_PATH . "system/packages/com.jukusoft.cms.xtpl/xtpl/xtemplate.class.php");

		if (isset(self::$registeredTemplate[$file])) {
			$file = self::$registeredTemplate[$file];
		}

		$this->template = new XTemplate($file);

		/*$registry = Registry::singleton();
		$this->template->assign("REGISTRY", $registry->listSettings());*/
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

}

?>
