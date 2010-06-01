<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
error_reporting(E_ALL);
//updater for 0.9.02 to 0.9.03 fixes theme settings bug
define('IN_ECMS', true);
define('INSTALLER', true);
define('IN_PATH', realpath('.') . '/');
require('config.php');
require('framework.php');

//lets run through all the themes and fix them, first lets remove the primary key off of the theme settings table

//due to the nature of SQLite, we need to re-create the entire table!
if(DB_TYPE == 'sqlite') {
	$array = array("CREATE TEMPORARY TABLE theme_settings2(theme_id, variable, value)", "INSERT INTO theme_settings2 SELECT theme_id, variable, value FROM theme_settings", "DROP TABLE theme_settings", "CREATE TABLE theme_settings ( theme_id INTEGER, variable text, value text )", "INSERT INTO theme_settings SELECT theme_id, variable, value FROM theme_settings2", "DROP TABLE theme_settings2");
	foreach($array as $query) {
		call('sql_query', $query);	
	}
}
//mysql however we do not
if(DB_TYPE == 'mysql') {
	call('sql_query', "ALTER TABLE theme_settings CHANGE theme_id theme_id INT( 255 )");
	call('sql_query', "ALTER TABLE theme_settings DROP PRIMARY KEY");
}
//now that the table is edited, lets run through the themes and reinstall their settings!
$sql = call('sql_query', "SELECT * FROM themes");
while($p = call('sql_fetch_array', $sql)) {
	include(IN_PATH.'themes/'.$p['folder'].'/theme-info.php');
	if(is_array($theme['settings'])) {
		foreach($theme['settings'] as $variable => $value) {
			call('sql_query', "INSERT INTO theme_settings (theme_id, variable, value) VALUES ('".$p['theme_id']."', '".$variable."', '".$value."')");
		}
	}
	unset($theme);
}
//lets update the version in the database :D
$sql = call('sql_query', "UPDATE settings SET value = '0.9.03' WHERE variable = 'version'");
//all fixed!
echo 'Thank you for updating to eoCMS v0.9.03, please delete this file for security reasons';