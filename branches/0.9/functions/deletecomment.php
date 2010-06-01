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
function deletecomment($id) {
	global $user, $error, $error_die;
	if(!$user['post_comment']) {
		$error[] = 'You do not have permission to post a comment';
		return false;
	}
	$existcheck = call('sql_query', "SELECT author FROM comments WHERE id = '$id'");
	$fetch = call('sql_fetch_array',$existcheck);
	if(call('sql_num_rows', $existcheck) == 0) {
		$error_die[] = 'This comment no longer exists so deleting it was not possible';
		return false;
	}
	if(($fetch['author'] != $user['user'] && !$user['delete_own_comment']) || (!$user['delete_any_comment'])) {
		$error_die[] = 'You do not have permission to delete this comment';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "DELETE FROM comments WHERE id = '$id'");
		if ($sql)
			return true;
	}
}
?>