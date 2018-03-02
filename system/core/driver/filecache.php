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
        file_put_contents(CACHE_PATH . "/" . md5($area) . "/" + md5($key) + ".php", serialize($value));
    }

    public function get($area, $key) {
        if ($this->contains($area, $key)) {
            return unserialize(file_get_contents(CACHE_PATH . "/" . md5($area) . "/" + md5($key) + ".php"));
        } else {
            throw new Exception("File cache object " . $area . "/" . $key + "(" . CACHE_PATH . md5($area) . "/" . md5($key) . ") doesnt exists.");
        }
    }

    public function contains($area, $key) {
        return file_exists(CACHE_PATH . "/" . md5($area) . "/" + md5($key) + ".php");
    }

    private function check_directory ($name) {
        if (!file_exists(CACHE_PATH . $name)) {
            mkdir(CACHE_PATH . $name);
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
        if ($area == "") {
            $this->rrmdir(CACHE_PATH, CACHE_PATH);
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