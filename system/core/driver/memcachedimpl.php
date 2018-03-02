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

class MemcachedImpl implements ICache {

    protected $memcached = null;

    //TODO: use locks https://packagist.org/packages/cheprasov/php-memcached-lock , if neccessary

    /**
     * time to live of 1000 seconds
     */
    protected $TTL = 1000;

    protected $local_cache = array();

    /**
     * length of characters returned by function md5()
     */
    protected static $MD5_LENGTH = 32;

    public function __construct() {
        if (!PHPUtils::isMemcachedAvailable()) {
            throw new ConfigurationException("PECL extension memcached for PHP isnt available.");
        }

        //create new instance of memcached
        $this->memcached = new Memcached();
    }

    public function init($config) {
        if (!PHPUtils::isMemcachedAvailable()) {
            throw new ConfigurationException("PECL extension memcached for PHP isnt available.");
        }

        require(CONFIG_PATH . "memcached.example.php");

        //get configuration parameters
        $server_list = $memcached_config['server'];
        $auth = $memcached_config['authentification'];
        $options = $memcached_config['options'];

        //set options
        foreach ($options as $option=>$value) {
            $this->memcached->setOption($option, $value);
        }

        //check, if SASL authentification is enabled
        if ($auth['enabled'] == true) {
            //use authentification
            $this->memcached->setSaslAuthData($auth['username'], $auth['password']);
        }

        //add servers
        foreach ($server_list as $server) {
            $this->memcached->addServer($server['host'], $server['port']);
        }
    }

    public function put($area, $key, $value) {
        $data = serialize($value);

        //put serialized data to memcache
        $this->memcached->set($this->getKey($area, $key), $data);

        //check, if area exists in local cache
        if (!isset($this->local_cache[md5($area)])) {
            //create new index
            $this->local_cache[md5($area)] = array();
        }

        //put serialized data to local cache to reduce memcache queries
        $this->local_cache[md5($area)][md5($key)] = $data;
    }

    public function get($area, $key, $ttl = 0) {
        //check local cache first
        if (isset($this->local_cache[md5($area)]) && isset($this->local_cache[md5($area)][md5($key)])) {
            //get value from cache
            return unserialize($this->local_cache[md5($area)][md5($key)]);
        }

        //get data from memcache
        $data = $this->memcached->get($this->getKey($area, $key));

        //check, if key exists
        if ($data != false) {
            return unserialize($data);
        } else {
            //data doesnt exists in cache
            return "";
        }
    }

    public function contains($area, $key) {
        //check local cache first
        if (isset($this->local_cache[md5($area)]) && isset($this->local_cache[md5($area)][md5($key)])) {
            return true;
        }

        //get data from memcache
        $data = $this->memcached->get($this->getKey($area, $key));

        return $data != false;
    }

    public function clear($area = "", $key = "") {
        if ($area != "" && $key != "") {
            //remove specific key
            $this->memcached->delete($this->getKey($area, $key));
        } else if ($area != "") {
            //remove all entries from area

            //TODO: if possible, find an effiecenter solution

            //because memcached hasnt any build-in solution for this problem, we have to do some map reduce

            //list all keys
            $keys = $this->memcached->getAllKeys();

            //create new array with keys to remove
            $keys_to_remove = array();

            //generate md5 hash of area
            $area_md5 = md5($area);

            foreach ($keys as $key) {
                //get memcached area md5 hash
                $memcached_area = substr($key, 0, self::$MD5_LENGTH);

                //check, if key belongs to this area
                if ($area_md5 == $memcached_area) {
                    //add key to keys to remove
                    $keys_to_remove[] = $key;
                }
            }

            //remove keys from memcache
            $this->memcached->deleteMulti($keys_to_remove);

            //check, if area exists in local cache
            if (isset($this->local_cache[md5($area)])) {
                //remove area from local cache
                unset($this->local_cache[md5($area)]);
            }
        } else {
            //remove all entries, flush() invalides cache, but there isnt any guarantee, that entries will deleted now
            $this->memcached->flush();
        }
    }

    protected function getKey ($area, $key) {
        return md5($area) . md5($key);
    }

}
