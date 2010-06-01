<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons 
	Attribution-Share Alike 3.0 United States License. 
	To view a copy of this license, visit 
	http://creativecommons.org/licenses/by-sa/3.0/us/
	or send a letter to Creative Commons, 171 Second Street, 
	Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(file_exists('installprogress.php')) {
	$file = file_get_contents('installprogress.php', 'FILE_TEXT', NULL, 16);
	$file = eval($file);
	if(isset($db) && !empty($db))
		$_REQUEST['dbtype'] = $db;
	if(isset($location) && !empty($location))
		$_REQUEST['location'] = $location;
}
//add datbase details
$file = file_put_contents('installprogress.php', "<?php die(); ?>\n".'$step = 3; $db = "'.$_REQUEST['dbtype'].'"; $location = "'.$_REQUEST['location'].'";');
$con = @sqlite_open($_REQUEST['location'], 0666, $sqliteerror);
if(!$con)
	echo $INSTALLER_LANG["error_connect"];
else
	echo '1';
?>