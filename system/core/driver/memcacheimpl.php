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
 * Memcache Cache Implementation
 *
 * Warning! - Memcache and Memcached arent the same!
 *
 * We do not recommend to use memcache anymore, use memcached instead.
 */
class MemcacheImpl implements ICache {

    protected $memcache = null;

    public function __construct() {
        if (!PHPUtils::isMemcacheAvailable()) {
            throw new ConfigurationException("Memcache PECL extension for PHP isnt loaded.");
        }

        //create new instance of memcache
        $this->memcache = new Memcache();
    }

    public function connect ($host, $port) {
        $this->memcache->connect($host, $port);
    }

    public function init($config) {
        require(CONFIG_PATH . "memcache.example.php");
        
        $host = $memcache_config['host'];
        $port = $memcache_config['port'];

        //conenct to memcache server
        $this->connect($host, $port);
    }

    public function put($area, $key, $value, $ttl = 0) {
        $this->memcache->set($this->getKey($area, $key), serialize($value));
    }

    public function get($area, $key) {
        return unserialize($this->memcache->get($this->getKey($area, $key)));
    }

    public function contains($area, $key) {
        return $this->memcache->get($this->getKey($area, $key)) != false;
    }

    public function clear($area = "", $key = "") {
        if ($area != "" && $key != "") {
            $this->memcache->delete($this->getKey($area, $key));
        } else if ($area != "" && $key == "") {
            //remove area from cache

            //TODO: find an efficienter way

            //list all keys
            $keys = $this->listAllKeys();

            foreach ($keys as $key) {
                //check, if key belongs to this area
                if (substr($key, 0, strlen($area)) === $$area) {
                    //remove key
                    $this->memcache->delete($key);
                } else {
                    //key doesnt belongs to this area
                    continue;
                }
            }
        } else {
            //remove all cache entries
            $this->memcache->flush();
        }
    }

    protected function getKey ($area, $key) {
        return md5($area) . md5($key);
    }

    private function listAllKeys ($limit = 10000) {
        //list with keys
        $keys = array();

        //get extended states
        $stats = $this->memcache->getExtendedStats('slabs');

        foreach ($stats as $serverStats) {
            foreach ($serverStats as $id => $stateMeta) {
                try {
                    $cacheDump = $this->memcache->getExtendedStats('cachedump', (int) $id, 1000);
                } catch (Exception $e) {
                    continue;
                }

                if (!is_array($cacheDump)) {
                    continue;
                }

                foreach ($cacheDump as $dump) {

                    if (!is_array($dump)) {
                        continue;
                    }

                    foreach ($dump as $key => $value) {
                        //add key to list
                        $keys[] = $key;

                        if (count($keys) >= $limit) {
                            return $keys;
                        }
                    }
                }
            }
        }

        return $keys;
    }

}