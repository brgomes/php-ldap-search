<?php

ini_set('display_errors', 'on');
error_reporting(-1);


////////////////////////////////////////////
// FILL THESE FIELDS ///////////////////////
define('LDAP_HOST', '');
define('LDAP_PORT', '');
define('LDAP_DOMAIN', '');
define('LDAP_DN', '');

define('USERNAME', '');
define('PASSWORD', '');
////////////////////////////////////////////



$conn = ldap_connect(LDAP_HOST, LDAP_PORT);

if ($conn) {
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
	ldap_set_option($conn, LDAP_OPT_DEBUG_LEVEL, 7);

	if (@ldap_bind($conn, USERNAME . LDAP_DOMAIN, PASSWORD)) {
		$filter = 'samaccountname=' . USERNAME;
		$fields = array('samaccountname', 'memberof', 'displayname');

		$user  	= ldap_search($conn, LDAP_DN, $filter, $fields);
		$count 	= ldap_count_entries($conn, $user);

		if ($count == 1) {
			$entries = ldap_get_entries($conn, $user);

			echo '<div style="line-height:30px; border:1px solid green; padding:10px 15px; background-color:#C5FFB8">';
			echo 'sAMAccountName: <strong>', $entries[0]['samaccountname'][0] , '</strong><br />';
			echo 'Display name: <strong>', $entries[0]['displayname'][0] , '</strong><br />';
			echo 'Member of: <strong>', $entries[0]['memberof'][0] , '</strong><br />';
			echo '</div>';
		}
	} else {
		echo '<div style="line-height:30px; border:1px solid red; padding:10px 15px; background-color:#FFBBBB">';
		echo 'Error: '. ldap_error($conn);
		echo '</div>';
	}
}
