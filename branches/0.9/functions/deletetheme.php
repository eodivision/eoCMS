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
function deletetheme($themeid, $reset) {
	global $user, $FUNCTIONS_LANG, $error, $error_die;
	if(!$user['admin_panel'])  {
# No one should be able to actually use this function except an admin,
#  its there just to be safe ;)
		$error_die[] = $FUNCTIONS_LANG["e_permissions"];
		return false;
	}
	if(empty($themeid)) {
# tut tut, dont mess around with the URLs
		$error[] = $FUNCTIONS_LANG["e_th_specify_delete"];
		return false;
	}
# tut tut, dont mess around with the URLs
	if(empty($reset)) {
		$error[] = $FUNCTIONS_LANG["e_th_specify_everyone"];
		return false;
	}
	if(!errors()) {
		$themes = call('sql_query', "SELECT * FROM themes WHERE theme_id = '$themeid'");
		$r = call('sql_fetch_array', $themes);
		$query = call('sql_query', "DELETE FROM themes WHERE theme_id = '$themeid'");
		$sql = call('sql_query', "DELETE FROM theme_settings WHERE theme_id = '$themeid'");
		$sql = call('sql_query', "UPDATE users SET theme = '$reset' WHERE theme = '".$r['theme_name']."'");
		if($sql) {
			return true;
		}
	}
}
?>