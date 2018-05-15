<?php

/**
 * LDAP configuration (optional)
 *
 * CMS doesnt require ldap, but you can use it to integrate ldap authorization
 */

$ldap_config = array(
	'enabled' => false,

	// active directory server
	'host' => "localhost",
	'port' => 389,

	'use_uri' => true,

	'ssl' => false,

	'auth' => true,
	'user' => "uname",//ldap rdn or dn,
	'password' => "admin",

	// domain, for purposes of constructing $user
	'ldap_usr_dom' => "",//e.q. @college.school.edu --> user "user1" => user1@college.school.edu

	// active directory DN (base location of ldap search)
	'dn' => "",//$dn = "OU=Departments,DC=college,DC=school,DC=edu";

	'readonly' => true,//only readonly access

	//https://www.forumsys.com/tutorials/integration-how-to/ldap/api-identity-management-ldap-server/

	//https://www.forumsys.com/tutorials/integration-how-to/ldap/online-ldap-test-server/

	//ldap params
	'params' => array(
		LDAP_OPT_PROTOCOL_VERSION => 3,
		LDAP_OPT_REFERRALS => 0
	),
);

?>
