<?php

$plugin['name']    = 'My Navigation Panel';
$plugin['version'] = '0.1';
$plugin['author']  = 'paulwratt'
$plugin['description'] = 'Uses Navigation Panel plugin to allow user maintained list and panel';

$plugin['requires']['plugins'] = 'navpanel';

$plugin['options']['plugin'] = 'Options.php';
$plugin['options']['users']  = 'Users.php';
$plugin['options']['admin']  = 'Admin.php';

# probably needs
$plugin['settings']['table']['copy']['navigation_menu'] = 'my_navigation_menu'
$plugin['settings']['table']['alter']['my_navigation_menu'] = array(
	'columns' => 'userid',
	'types'   => 'int(225)',
	'keys'    => array(
		'key'     => 'userid'
	)
);

#DEV: copy table function, and alter tables function, but check for copy+alter
#     also develop sql calls so they can be append to other plugin calls

?>