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

	//https://www.forumsys.com/tutorials/integration-how-to/ldap/api-identity-management-ldap-server/

	//https://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/

	'params' => array(
		LDAP_OPT_PROTOCOL_VERSION => 3,
		LDAP_OPT_REFERRALS => 0
	),
);

?>
