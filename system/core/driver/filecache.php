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

/*
 * File cache implementation
 */

class FileCache implements ICache {

    public function put($area, $key, $value, $ttl = 0) {
        //create directory, if neccessary
        $this->check_directory(md5($area));

        //write value to file
        file_put_contents($this->getFilePath($area, $key), serialize($value));
    }

    public function get($area, $key) {
		if ($this->contains($area, $key)) {
			$file_path = $this->getFilePath($area, $key);
			//echo "area: " . $area . ", key: " . $key . ", File path: " . $file_path . "<br />\n";

			$content = file_get_contents($file_path);

			//echo $content;

            return unserialize($content);
        } else {
            throw new Exception("File cache object " . $area . "/" . $key + "(" . $this->getFilePath($area, $key) . ") doesnt exists.");
        }
    }

    public function contains ($area, $key) : bool {
		return CACHING && file_exists($this->getFilePath($area, $key));
    }

    protected function getFilePath (string $area, string $key) : string {
    	return CACHE_PATH . md5($area) . "/" . md5($key) . ".php";
	}

    private function check_directory ($name) {
        if (!file_exists(CACHE_PATH . $name . "/")) {
            mkdir(CACHE_PATH . $name . "/");
        }
    }

    public function init($config) {
        //check directory
        if (!file_exists(CACHE_PATH)) {
            mkdir(CACHE_PATH);
        }

        //check template directory
        if (!file_exists(CACHE_PATH . "template")) {
            mkdir(CACHE_PATH . "template");
        }
    }

    public function clear($area = "", $key = "") {
        if (empty($area)) {
            $this->rrmdir(CACHE_PATH, CACHE_PATH);
        } else {
        	//area is not null

			$area_path = CACHE_PATH . md5($area) . "/";

			if (!empty($key)) {
				$file_path = CACHE_PATH . md5($area) . "/" + md5($key) + ".php";

				//check, if file exists
				if (file_exists($file_path)) {
					//remove file
					unlink($file_path);
				}
			} else {
				//clear full area
				$this->rrmdir($area_path, CACHE_PATH);
			}
		}
        // TODO: Implement clear() method.
    }

    protected function rrmdir ($dir, $cache_dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        $this->rrmdir($dir."/".$object, $cache_dir);
                    else
                        unlink($dir."/".$object);
                }
            }

            if ($dir != $cache_dir) {
                rmdir($dir);
            }
        }
    }

}

?>