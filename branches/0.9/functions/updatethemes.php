<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

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
			$visible .= $value.',';
		}
		$visible = str_replace('Array', '', $visible);
		$count = strlen($visible);
		$visible = substr($visible, 0, $count - 1);
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