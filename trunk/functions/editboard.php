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
function editboard($name, $description, $postgroup, $visible, $id, $moderators, $category, $sticky, $lock) {
	global $user, $error, $error_die;
	if(empty($visible)) {
		$error[] = 'You must state who this board is visible to';
		return false;
	} else {
		foreach($visible as $key => $value) {
			$newvisible .= '|'.$value.'|';
		}
		$visible = $newvisible;
	}
	if(empty($postgroup)) {
		$error[] = 'You must state who may post in this board';
		return false;
	} else {
		foreach($postgroup as $key => $value) {
			$newpostgroup .= '|'.$value.'|';
		}
		$postgroup = $newpostgroup;
	}
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
	$sql = call('sql_query', "SELECT * FROM forum_boards WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This board no longer exists';
		return false;
	}
	$sticky = (empty($sticky) || !$sticky ? 0 : 1);
	$lock = (empty($lock) || !$lock ? 0 : 1);
	if(!errors()) {
		$query = call('sql_query', "UPDATE forum_boards SET board_name = '$name', board_description = '$description', visible = '$visible', post_group = '$postgroup', cat = '$category', creation_sticky = '$sticky', creation_lock = '$lock' WHERE id = '$id'");
		$username = explode(", ", $moderators);
		foreach ($username as $recipient) {
			$sql_2 = call('sql_query', "INSERT INTO forum_moderators (user_id, board_id) VALUES ('$recipient', '$id')");
		}
		if($query)
			return true;
	}
}
?>