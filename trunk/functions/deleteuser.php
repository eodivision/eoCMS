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
function deleteuser($id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if($user['id'] == $id)  {
		$error_die[] = 'You can not delete your own account'; //why would you want to delete your own account in the admin panel? Makes no sense
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM users WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This user no longer exists';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "DELETE FROM users WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>