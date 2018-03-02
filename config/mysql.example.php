<?php

/**
 * Configuration file for CMS
 *
 * User: Justin
 * Date: 01.03.2018
 * Time: 18:19
 */

//allow including of mysql.cfg only once (for security reasons)
if (defined('MYSQL_CONFIG_INCLUDED')) {
	echo "Error! Cannot include mysql.cfg again (because for security reasons).";
	exit;
}

define('MYSQL_CONFIG_INCLUDED', true);

$mysql_settings = array(
	/**
	 * MySQL Host
	 *
	 * For example "localhost" or the ip of your mysql server
	 */
	'host' => "localhost",

	/**
	 * MySQL Port
	 *
	 * by default 3306
	 */
	'port' => 3306,

	/**
	 * MySQL database user
	 *
	 * If you have an choice: Dont use "root" !
	 */
	'username' => "root",

	/**
	 * MySQL database password
	 */
	'password' => "<Insert your password here>",

	/**
	 * MySQL database name
	 */
	'database' => "pscf",

	/**
	 * MySQL table praefix (praefix before table name, for example "prefix_<Table Name>")
	 *
	 * Also "" is allowed
	 */
	'praefix' => "pscf_",

	/**
	 * optional PhpMyAdmin configuration to show PhpMyAdmin menu or other thinks in administration panel
	 */
	'phpmyadmin' => array(
		'enabled' => false,
		'link' => "<Insert your PhpMyAdmin Link here (optional)>",

		/**
		 * should phpMyAdmin shown in administration panel?
		 *
		 * true / false
		 */
		'admin_access' => true
	),

	'options' => array(
		/**
		 * use connection pooling, dont create an new connection on every request, use connection caching
		 *
		 * @link http://php.net/manual/de/pdo.connections.php
		 */
		PDO::ATTR_PERSISTENT => true
	)
);

?>
