<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

   Added Language - 01/06/09 - Paul Wratt
*/
function installtheme($folder, $visible) {
	global $user, $settings, $FUNCTIONS_LANG, $error, $error_die;
	if(empty($visible)) {
		$error[] = $FUNCTIONS_LANG["e_th_use_theme"];
		return false;
	} else {
		foreach($visible as $key => $value) {
			$visible .= $value.',';
		}
		$visible = str_replace('Array', '', $visible);
		$count = strlen($visible);
		$visible = substr($visible, 0, $count - 1);
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