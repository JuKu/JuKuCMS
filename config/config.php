<?php

/**
 * configuration file
 */

//should a HTML comment "<!-- page was generated in xxx seconds -->" be added to every page?
define('ACTIVATE_BENCHMARK', true);

define('OPTION_PRELOAD_CLASSES', false);

define('DEBUG_MODE', true);

/**
 * if this option is enabled you will see a sql query history on end of every page as html comment
 *
 * depends on DEBUG_MODE option
 */
define("DEBUG_SQL_QUERIES", false);

define("CACHING", true);

//clear PHP 7 OpCache f
define('CLEAR_OP_CACHE', true);

//show all errors
error_reporting(E_ALL);

$config = array(
	'PHP_BENCHMARK' => true
);

?>
