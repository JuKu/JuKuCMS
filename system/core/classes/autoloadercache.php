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

class AutoLoaderCache {

	public static function init () {
		//check, if directory autoloader in cache exists
		if (!file_exists(CACHE_PATH . "autoloader")) {
			mkdir(CACHE_PATH . "autoloader");
		}
		//check, if autoloader configuration exists
		if (!file_exists(STORE_PATH . "autoloader/autoloader.php")) {
			//TODO: generate file automatically (self repair)

			throw new ConfigurationException("autoloader configuration not found in store.");
		}
		if (!file_exists(CACHE_PATH . "autoloader/preloaded_classes.php")) {
			//create cache
			self::createCache();
		}
	}
	public static function load () {
		if (!file_exists(STORE_PATH . "autoloader/autoloader.php")) {
			throw new ConfigurationException("autoloader configuration not found in store.");
		}
		if (!file_exists(CACHE_PATH . "autoloader/preloaded_classes.php")) {
			//cache file doesnt exists
			self::createCache();
		}
		//load classes from cache to save I/O
		require(CACHE_PATH . "autoloader/preloaded_classes.php");
	}
	private static function createCache () {
		require(STORE_PATH . "autoloader/autoloader.php");
		//create new cache file
		$data = "";

		foreach ($autoloader_classes as $class_path) {
			if (file_exists($class_path)) {
				//get php code directly
				$data .= file_get_contents($class_path);
			} else if (file_exists(ROOT_PATH . $class_path)) {
				//use ROOT_PATH prefix
				$data .= file_get_contents(ROOT_PATH . $class_path);
			} else {
				echo "<!-- class " . $class_path . " couldnt be cached, file path doesnt exists. -->";
			}
		}

		//remove unneccessary php tags
		$data = str_replace("<?php", "", $data);
		$data = str_replace("?>", "", $data);
		$text = "<" . "?" . "php " . $data . " ?" . ">";
		
		//write data to file in uncrommpressed way
		file_put_contents(CACHE_PATH . "autoloader/preloaded_classes_uncompressed.php", $text);

		//compress code, remove comments and unneccessary whitespaces with php_strip_whitespace()
		file_put_contents(CACHE_PATH . "autoloader/preloaded_classes.php", php_strip_whitespace(CACHE_PATH . "autoloader/preloaded_classes_uncompressed.php"));
	}

}

?>
