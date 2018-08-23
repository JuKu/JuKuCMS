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
 * Date: 22.08.2018
 * Time: 16:39
 */

namespace Dwoo\Plugins\Blocks;

use Dwoo\Compiler;
use Dwoo\Block\Plugin as Plugin;
use Dwoo\ICompilable\Block as ICompilableBlock;

use DomainUtils;
use Registry;
use CSSBuilder;
use JSBuilder;
use PHPUtils;

class PluginRes extends Plugin implements ICompilableBlock {

	protected static $load = "";
	protected static $media = "";

	protected static $base_url = "";

	/**
	 * @param Compiler $compiler
	 * @param mixed    $value
	 * @param mixed    $var
	 *
	 * @return string
	 */
	/*public static function compile(Compiler $compiler, $value, $domain = "") {
		return 'Translator::translate(' . $value . ', ' . $domain . ')';
	}*/

	// parameters go here if you need any settings
	public function init() {
		//
	}

	// this can be ommitted, it's called once when the block ends, don't implement if you don't need it
	public function end() {
		//
	}

	// this is called when the block is required to output it's data, it should read $this->buffer, process it and return it
	/*public function process(){
		var_dump($this->buffer);

		return strtoupper($this->buffer);
	}*/

	public static function preProcessing(Compiler $compiler, array $params, $prepend, $append, $type) {
		//cache BASE_URL
		if (empty(self::$base_url)) {
			self::$base_url = DomainUtils::getBaseURL();
		}

		//reset values
		self::$load = "";
		self::$media = "ALL";

		$load = "";

		/*if (isset($params[1])) {
			$domain = $params[1][1];
		}

		return Compiler::PHP_OPEN . $prepend . " echo Translator::translate(\"" . $params[0][1] . "\", \"" . $domain . "\"); " . $append . Compiler::PHP_CLOSE;*/

		if (isset($params[0])) {
			self::$load = $params[0][1];
		}

		if (isset($params[1])) {
			self::$media = $params[1][1];
		}

		//var_dump($params);

		return Compiler::PHP_OPEN . "$" . "position = \"";
	}

	public static function postProcessing(Compiler $compiler, array $params, $prepend, $append, $content) {
		/**
		 * the block is responsible for outputting it's entire content (passed as $content),
		 * so you can transform it and then return it, but in this case we don't because
		 * we want the content to be uppercased at runtime and not at compile time
		 */
		//return $content . Compiler::PHP_OPEN . $prepend . ' ' . $append . Compiler::PHP_CLOSE;

		return $content . "\"; \\Dwoo\Plugins\\Blocks\\PluginRes::helperFuncInsertCode($" . "position, \"" . self::$media . "\", \"" . self::$load . "\"); " . Compiler::PHP_CLOSE;
	}

	/**
	 * helper function for dwoo plugin
	 *
	 * @param $position string position name
	 * @param $media string media
	 * @param $load string optional load (default: "")
	 */
	public static function helperFuncInsertCode (string $position, string $media = "ALL", string $load = "") {
		//get style name
		$registry = Registry::singleton();
		$style_name = $registry->getSetting("current_style_name");

		if ($position === "css") {
			$position = "css_header";
		} else if ($position === "js") {
			$position = "js_header";
		}

		if (PHPUtils::startsWith($position, "css_")) {
			//its a css file

			//remove "css_" from position
			$position = substr($position, 4);

			//create new css builder
			$css_builder = new CSSBuilder();

			//get hash first, because else css file isn't generated
			$hash = $css_builder->getHash($style_name, $media, $position);

			//check, if file is empty
			$empty_flag = $css_builder->isEmpty($style_name, $media, $position);

			if (!$empty_flag) {
				//show css file
				echo "<link rel=\"stylesheet\" href=\"" . self::$base_url . "/css.php?style=" . $style_name . "&amp;media=" . $media . "&amp;position=" . $position . "&amp;hash=" . $hash . "\" />";
			} else {
				echo "<!-- DEBUG: " . $position . " css file was removed for optimization, because generated css file was empty -->";
			}
		} else if (PHPUtils::startsWith($position, "js_")) {
			//its a javascript file

			//remove "js_" from position
			$position = substr($position, 3);

			//create new js builder
			$js_builder = new JSBuilder();

			//get hash
			$hash = $js_builder->getHash($style_name, $media, $position);

			$empty_flag = $js_builder->isEmpty($style_name, $media, $position);

			if (!$empty_flag) {
				$load_str = "";

				//support for async / defer, see also https://bitsofco.de/async-vs-defer/
				if (!empty($load)) {
					$load_str = $load . " ";
				}

				//show js file
				echo "<script language=\"javascript\" type=\"text/javascript\" " . $load_str . "src=\"" . self::$base_url . "/js.php?style=" . $style_name . "&amp;media=" . $media . "&amp;hash=" . $hash . "&amp;position=" . $position . "\"></script>";
			} else {
				echo "<!-- DEBUG: " . $position . " javascript was removed for optimization, because generated js script was empty -->";
			}
		} else {
			//its a unknown file
			throw new IllegalArgumentException("Unknown resource type in template between {res ...}...{/res}, position has to start with 'css_' or 'js_'");
		}
	}

}


?>
