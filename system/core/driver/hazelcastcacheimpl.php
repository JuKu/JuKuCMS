<?php

class HazelcastCacheImpl extends MemcacheImpl {

    /**
     * time to live in seconds
     */
    protected $ttl = 0;

    public function init($config) {
        require(LIB_PSF_CONFIG . "hazelcast.php");

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
