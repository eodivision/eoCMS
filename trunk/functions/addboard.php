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
function addboard($name, $description, $category, $sticky, $lock, $permissions) {
	global $user, $error, $error_die;
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($description)) {
		$error[] = 'You must specify a description';
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name for the board'; //silly, but seriously why make a board without naming it, how are people meant to know what it is!
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "SELECT item_order FROM forum_boards WHERE cat = '$category' ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array', $sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);
		$sticky = (empty($sticky) || !$sticky ? 0 : 1);
		$lock = (empty($lock) || !$lock ? 0 : 1);
		$query = call('sql_query', "INSERT INTO forum_boards (board_name, board_description, item_order, cat, creation_sticky, creation_lock) VALUES ('$name', '$description', '$order', '$category', '$sticky', '$lock')");
		$id = call('sql_insert_id');
		//insert the permissions
		foreach($permissions as $membergroup => $array) {
			foreach($array as $variable => $value) {
				if(empty($value))
					$value = 1;
				$perms = call('sql_query', "INSERT INTO board_permissions (membergroup_id, board_id, variable, value) VALUES ('".$membergroup."', '".$id."', '".$variable."', '".$value."')");
			}
		}
		$return = array('board_id' => $id, 'board_name' => $name, 'board_description' => $description, 'cat' => $category, 'creation_sticky' => $sticky, 'creation_lock' => $lock, 'permissions' => $permissions);
		if($query)
			return $return;
		else
			return false;
	}
}
?>