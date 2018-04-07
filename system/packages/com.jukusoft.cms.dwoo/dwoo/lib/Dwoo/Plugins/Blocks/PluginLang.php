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

namespace Dwoo\Plugins\Functions;

use Dwoo\Compiler;
use Dwoo\Block\Plugin as Plugin;
use Dwoo\ICompilable\Block as ICompilableBlock;

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
 * Date: 07.04.2018
 * Time: 12:56
 */

class PluginLang extends Plugin/* implements ICompilableBlock*/ {

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
	public function init($value, $domain = "") {
		var_dump($value);
		var_dump($domain);
	}

	// this can be ommitted, it's called once when the block ends, don't implement if you don't need it
	public function end() {
		//
	}

	// this is called when the block is required to output it's data, it should read $this->buffer, process it and return it
	public function process(){
		var_dump($this->buffer);

		exit;
		return strtoupper($this->buffer);
	}

}

?>
