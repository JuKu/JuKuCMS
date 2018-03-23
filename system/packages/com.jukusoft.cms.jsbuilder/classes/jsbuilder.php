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
 * Date: 23.03.2018
 * Time: 14:02
 */

class JSBuilder {

	protected $content = "";
	
	public function __construct() {
		//
	}

	public function generateJS (string $style_name, string $media = "ALL", string $position = "header") {
		//
	}

	public function getCachePath (string $style, string $media = "ALL", string $position = "header") : string {
		$md5_filename = md5("js_" . $style . "_" . $media . "_" . $position);
		$js_cache_path = CACHE_PATH . "jsbuilder/" . $md5_filename . ".js";

		return $js_cache_path;
	}

	public function existsCache (string $style, string $media = "ALL", string $position = "header") : bool {
		return file_exists($this->getCachePath($style, $media, $position));
	}

	public function getHash (string $style, string $media = "ALL", string $position = "header") : string {
		if (!$this->existsCache($style, $media, $position)) {
			//generate cached css file
			$this->generateCSS($style, $media, $position);
		}

		if (!Cache::contains("jsbuilder", "hash_" . $style . "_" . $media . "_" . $position)) {
			throw new IllegalStateException("cached js file 'hash_" . $style . "_" . $media . "_" . $position . "' doesnt exists.");
		}

		return Cache::get("jsbuilder", "hash_" . $style . "_" . $media . "_" . $position);
	}

	public function load (string $style, string $media = "ALL", string $position = "header") {
		$cache_path = $this->getCachePath($style, $media, $position);

		if (!$this->existsCache($style, $media, $position)) {
			$this->generateJS($style, $media, $position);
		} else {
			$this->content = file_get_contents($cache_path);
		}
	}

	public function getBuffer () : string {
		return $this->content;
	}

}

?>
