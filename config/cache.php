<?php

/**
 * Cache configuration
 */

$config = array(
    'first_lvl_cache' => array(
        'activated' => true,
        'class_name' => "FileCache",
        'name' => "first_lvl_cache",
        'names' => array(
            "session_cache",
        )
    ),
    'second_lvl_cache' => array(
        'activated' => true,
        'class_name' => "FileCache",
        'name' => "second_lvl_cache"
    )
);

?>