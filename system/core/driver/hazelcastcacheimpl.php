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
 * Created by PhpStorm.
 * User: Justin
 * Date: 13.07.2016
 * Time: 01:53
 */
class HazelcastCacheImpl extends MemcacheImpl {

    /**
     * time to live in seconds
     */
    protected $ttl = 0;

    public function init($config) {
        require(CONFIG_PATH . "hazelcast.php");

        $host = $hazelcast_config['host'];
        $port = $hazelcast_config['port'];
        $this->ttl = $hazelcast_config['ttl'];

        //conenct to hazelcast server
        $this->connect($host, $port);
    }

    public function put($area, $key, $value, $ttl = 0) {
        $this->memcache->set($this->getKey($area, $key), serialize($value), 0, $this->ttl);
    }

    public function get($area, $key) {
        return unserialize($this->memcache->get($this->getKey($area, $key)));
    }

    public function contains($area, $key) {
        return $this->memcache->get($this->getKey($area, $key)) != false;
    }

}