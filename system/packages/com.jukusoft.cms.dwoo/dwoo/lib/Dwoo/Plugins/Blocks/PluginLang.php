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

namespace Dwoo\Plugins\Blocks;

use Dwoo\Compiler;
use Dwoo\Block\Plugin as Plugin;
use Dwoo\ICompilable\Block as ICompilableBlock;


/**
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 07.04.2018
 * Time: 12:56
 */

class PluginLang extends Plugin implements ICompilableBlock {

	protected static $domain = "";

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
		$domain = "";

		/*if (isset($params[1])) {
			$domain = $params[1][1];
		}

		return Compiler::PHP_OPEN . $prepend . " echo Translator::translate(\"" . $params[0][1] . "\", \"" . $domain . "\"); " . $append . Compiler::PHP_CLOSE;*/

		if (isset($params[0])) {
			$domain = $params[0][1];
		}

		self::$domain = $domain;

		return Compiler::PHP_OPEN . "echo Translator::translate(\"";
	}

	public static function postProcessing(Compiler $compiler, array $params, $prepend, $append, $content) {
		/**
		 * the block is responsible for outputting it's entire content (passed as $content),
		 * so you can transform it and then return it, but in this case we don't because
		 * we want the content to be uppercased at runtime and not at compile time
		 */
		//return $content . Compiler::PHP_OPEN . $prepend . ' ' . $append . Compiler::PHP_CLOSE;

		return $content . "\", \"" . self::$domain . "\"); " . Compiler::PHP_CLOSE;
	}

}

?>
