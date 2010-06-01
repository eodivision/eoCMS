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
function addboard($name, $description, $postgroup, $visible, $category, $sticky, $lock) {
	global $user, $error, $error_die;
	if(empty($visible))
		$error[] = 'You must state who this board is visible to';
	else {
		foreach($visible as $key => $value) {
			$visible .= $value.',';
		}
		$visible = str_replace('Array', '', $visible);
		$count = strlen($visible);
		$visible = substr($visible, 0, $count - 1);
	}
	if(empty($postgroup))
		$error[] = 'You must state who may post in this board';
	else {
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
	if(!errors()) {
		$sql = call('sql_query', "SELECT * FROM forum_boards WHERE cat = '$category' ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array', $sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);
		$sticky = (empty($sticky) || !$sticky ? 0 : 1);
		$lock = (empty($lock) || !$lock ? 0 : 1);
		$query = call('sql_query', "INSERT INTO forum_boards (board_name, board_description, post_group, visible, item_order, cat, creation_sticky, creation_lock) VALUES ('$name', '$description', '$postgroup', '$visible', '$order', '$category', '$sticky', '$lock')");
		if($query)
			return true;
	}
}
?>