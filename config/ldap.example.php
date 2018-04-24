<?php

/**
 * LDAP configuration (optional)
 *
 * CMS doesnt require ldap, but you can use it to integrate ldap authorization
 */

$ldap_config = array(
	'enabled' => false,

	'host' => "localhost",
	'port' => 389,

	'ssl' => false,

	'auth' => true,
	'user' => "uname",//ldap rdn or dn,
	'password' => "admin",

	'readonly' => true,//only readonly access
);

?>
