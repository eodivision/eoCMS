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
function deleteboard($id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel']) {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM forum_boards WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
			$error[] = 'This board no longer exists';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "DELETE FROM forum_boards WHERE id = '$id'");
		$query_2 = call('sql_query', "DELETE FROM forum_topics WHERE board_id = '$id'");
		$query_3 = call('sql_query', "DELETE FROM forum_posts WHERE board_id = '$id'");
		if($query)
			return true;
	}
}
?>