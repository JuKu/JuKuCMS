<?php

/**
 * Hazelcast Cache Client (optional)
 *
 * PSF Framework doesnt require hazelcast cache, but you can use it to speed up your website.
 * Note: If you want to use hazelcast cache, you have also to configure in file cache.php
 */

$hazelcast_config = array(
    'host' => "127.0.0.1",
    'port' => "5701",
    
    /**
     * time to live in seconds
     */
    'ttl' => "3600"
);

?>