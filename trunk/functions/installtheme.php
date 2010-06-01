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

   Added Language - 01/06/09 - Paul Wratt
*/
function installtheme($folder, $visible) {
	global $user, $settings, $FUNCTIONS_LANG, $error, $error_die;
	if(empty($visible)) {
		$error[] = $FUNCTIONS_LANG["e_th_use_theme"];
		return false;
	} else {
		foreach($visible as $key => $value) {
			$newvisible .= '|'.$value.'|';
		}
		$visible = $newvisible;
	}
	if(!$user['admin_panel'])  {
		$error_die[] = $FUNCTIONS_LANG["e_permissions"]; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
# tut tut, dont mess around with the URLs
	if(empty($folder)) {
		$error[] = $FUNCTIONS_LANG["e_th_install_theme"];
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM themes WHERE folder = '$folder'");
# Must have pressed refresh, silly admin
	if(call('sql_num_rows', $sql) != 0) {
		$error[] = $FUNCTIONS_LANG["e_th_already_installed"];
		return false;
	}
	if(!errors()) {
		include('themes/' . $folder . '/theme-info.php');
		$query = call('sql_query', "INSERT INTO themes (theme_name, theme_author, author_site, author_email, theme_version, folder, theme_visibility, theme_preview) VALUES ('".$theme['name']."', '".$theme['author']."', '".$theme['site']."', '".$theme['email']."', '".$theme['version']."', '$folder', '$visible', '".$theme['preview']."')");
		$theme_id = call('sql_insert_id');
# lets install all the theme settings
		if(is_array($theme['settings'])) {
			foreach($theme['settings'] as $variable => $value) {
				$sql = call('sql_query', "INSERT INTO theme_settings (theme_id, variable, value) VALUES ('".$theme_id."', '".$variable."', '".$value."')");
			}
		}
		if($query)
			return true;
	}
}
?>