<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
function editboard($name, $description, $postgroup, $visible, $id, $moderators, $category, $sticky, $lock) {
	global $user, $error, $error_die;
	if(empty($visible)) {
		$error[] = 'You must state who this board is visible to';
		return false;
	} else {
		foreach($visible as $key => $value) {
			$visible .= $value.',';
		}
		$visible = str_replace('Array', '', $visible);
		$count = strlen($visible);
		$visible = substr($visible, 0, $count - 1);
	}
	if(empty($postgroup)) {
		$error[] = 'You must state who may post in this board';
		return false;
	} else {
		foreach($postgroup as $key => $value) {
			$postgroup .= $value.',';
		}
		$postgroup = str_replace('Array', '', $postgroup);
		$count2 = strlen($postgroup);
		$postgroup = substr($postgroup, 0, $count2 - 1);
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