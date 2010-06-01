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

   Added Language - 02/06/09 - Paul Wratt
*/
function updatethemes($footer, $logo, $left, $right, $upper, $lower, $visible, $id) {
	global $user, $FUNCTIONS_LANG;
	if(!$user['admin_panel'])  {
# No one should be able to actually use this function except an admin,
#  its there just to be safe ;)
		$error_die[] = $FUNCTIONS_LANG["e_permissions"];
		return false;
	}
	if(empty($visible)) {
		$error[] = $FUNCTIONS_LANG["e_th_use_theme"];
		return false;
	} else {
		foreach($visible as $key => $value) {
			$newvisible .= '|'.$value.'|';
		}
		$visible = $newvisible;
	}
	if(!errors()) {
		$site_footer = call('sql_query', "UPDATE theme_settings SET value = '$footer' WHERE variable = 'footer' AND theme_id = '$id'");
		$site_logo = call('sql_query', "UPDATE theme_settings SET value = '$logo' WHERE variable = 'logo'AND theme_id = '$id'");
		$exclude_left = call('sql_query', "UPDATE theme_settings SET value = '$left' WHERE variable = 'exclude_left'AND theme_id = '$id'");
		$exclude_right = call('sql_query', "UPDATE theme_settings SET value = '$right' WHERE variable = 'exclude_right'AND theme_id = '$id'");
		$exclude_upper = call('sql_query', "UPDATE theme_settings SET value = '$upper' WHERE variable = 'exclude_upper'AND theme_id = '$id'");
		$exclude_lower = call('sql_query', "UPDATE theme_settings SET value = '$lower' WHERE variable = 'exclude_lower'AND theme_id = '$id'");
		$theme_perms = call('sql_query', "UPDATE themes SET theme_visibility = '$visible' WHERE theme_id = '$id'");

		if($site_footer && $site_logo && $exclude_left && $exclude_right && $exclude_upper && $exclude_lower && $theme_perms) {
			return true;
		}
	}
}
?>