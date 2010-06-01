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
function activate($id, $key) {
	global $error_die;
	if(!is_numeric($id)) {
		$error_die[] = 'The key and or id given is not valid';
		return false;	
	} else {
		$sql = call('sql_query', "SELECT * FROM activation_keys WHERE key_number = '$key' AND user_id = '$id'");
		if(call('sql_num_rows', $sql) > 0) {
			$sql = call('sql_query', "UPDATE users SET membergroup = '1' WHERE id = '$id'");
			if($sql)
				return true;
		$delete = call('sql_query', "DELETE FROM activation_keys WHERE key_number = '$key' AND user_id = '$id'");
		} else {
			$error_die[] = 'The key and or id given is not valid';
			return false;
		}
	}
}
?>