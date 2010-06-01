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

   Clear Cache written by FWishbringer and confuser
*/

function clearcache() {
	global $user, $FUNCTIONS_LANG, $error, $error_die;
	if(!$user['admin_panel'])  {
		$error_die[] = $FUNCTIONS_LANG["e_permissions"];
		return false;
	}
	// Reset the tables.php
	$file = './'.CACHE.'/tables.php';
	$OUTPUT = "<?php die(); ?>\n";
	file_put_contents($file, $OUTPUT);
	// Remove any cache files
	$dir = opendir(CACHE.'/');
		while(($file=readdir($dir))!==false) {
			if(strlen($file) == 36 && $file != 'index.php' && $file != 'tables.php')
				unlink('./'.CACHE.'/' . $file);
		}
	closedir($dir);
}
?>