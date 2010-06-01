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