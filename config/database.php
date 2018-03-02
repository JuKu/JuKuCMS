<?php

/**
 * Database configuration
 */

$database = array(
    'driver' => "MySQLDriver",
    'config' => "mysql.php",
    'primary' => array(
        'readonly' => false,
        'config' => "mysql.php",
        'driver' => "MySQLDriver"
    ),
    'second' => array(
        //
    )
);

?>