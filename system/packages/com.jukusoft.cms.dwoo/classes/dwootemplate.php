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
 * Date: 03.04.2018
 * Time: 20:07
 */

class DwooTemplate extends Template {

	protected static $core = null;

	protected static $files = array();

	protected static $benchmark = array();

	//variables
	//protected $vars = array();
	protected $data = null;

	protected $file = "";
	protected $template = null;

	public function __construct($file, Registry $registry = null) {
		if ($registry == null) {
			$registry = Registry::singleton();
		}

		//find file
		$this->file = Template::findTemplate($file, $registry);

		//load a template file, this is reusable if you want to render multiple times the same template with different data
		if (isset(self::$files[$file])) {
			$this->template = self::$files[$this->file];
		} else {
			$this->template = new Dwoo\Template\File($this->file);
			self::$files[$file] = $this->template;
		}

		//create a data set, this data set can be reused to render multiple templates if it contains enough data to fill them all
		$this->data = new Dwoo\Data();
	}

	public function assign ($var, $value) {
		//$this->vars[$var] = $value;
		$this->data->assign($var, $value);
	}

	public function parse ($name = "main") {
		throw new Exception("Method DwooTemplate::parse() is not supported from Dwoo template engine.");
	}

	public function getCode ($name = "main") {
		$start_time = microtime(true);

		// Output the result
		$html = self::$core->get($this->template, $this->data);

		$end_time = microtime(true);
		$exec_time = $end_time - $start_time;

		//store benchmark
		self::$benchmark[$this->file] = $exec_time;

		return $html;
	}

	protected static function initCoreIfAbsent () {
		if (self::$core == null) {
			self::$core = new Dwoo\Core();

			$cache_dir = CACHE_PATH . "dwoo/";
			$compile_dir = CACHE_PATH . "dwoo-compile/";

			//check, if cache dir exists
			if (!file_exists($cache_dir)) {
				mkdir($cache_dir);
			}

			//check, if compile dir exists
			if (!file_exists($compile_dir)) {
				mkdir($compile_dir);
			}

			//set cache dir
			self::$core->setCacheDir($cache_dir);
			self::$core->setCompileDir($compile_dir);

			//add plugins
			//self::$core->addPlugin("if");

			Events::throwEvent("init_dwoo", array(
				'core' => &self::$core,
				'cache_path' => CACHE_PATH,
				'cache_dir' => $cache_dir
			));
		}
	}

	public static function listFileBenchmark () {
		return self::$benchmark;
	}

}

?>
