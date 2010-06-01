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
function deletecomment($id) {
	global $user, $error, $error_die;
	$existcheck = call('sql_query', "SELECT author FROM polls WHERE id = '$id'");
	$fetch = call('sql_fetch_array', $existcheck);
	if(call('sql_num_rows', $existcheck) == 0) {
		$error_die[] = 'This poll no longer exists so deleting it was not possible';
		return false;
	}
	if(($fetch['author'] != $user['user'] && !$user['delete_own_poll']) || (!$user['delete_any_poll'])) {
		$error_die[] = 'You do not have permission to delete this poll';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "DELETE FROM polls WHERE id = '$id'");
		$sql2 = call('sql_query', "DELETE FROM poll_options WHERE poll_id = '$id'");
		if($sql)
			return true;
	}
}
?>