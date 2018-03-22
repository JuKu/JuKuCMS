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
 * Date: 22.03.2018
 * Time: 13:23
 */

class CSSBuilder {

	public function __construct() {
		//
	}

	public function generateCSS (string $style_name) : string {
		$md5_filename = md5($style_name);
		$css_cache_path = CACHE_PATH . "cssbuilder/" . $md5_filename . ".css";

		$css_files = array();

		//get css files from style.json
		if (file_exists(STYLE_PATH . $style_name . "/style.json")) {
			$json = json_decode(file_get_contents(STYLE_PATH . $style_name . "/style.json"), true);

			var_dump($json);
		}

		//TODO: load css files from database
	}

}

?>
