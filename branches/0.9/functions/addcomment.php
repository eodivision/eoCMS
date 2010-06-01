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
function addcomment($id, $type, $message, $token) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	if(!$user['post_comment']) {
		$error[] = 'You do not have permission to post a comment';
		return false;
	}
	$existcheck = call('sql_query', "SELECT id, comments FROM $type WHERE id = '$id'");
	$fetch = call('sql_fetch_array',$existcheck);
	if(call('sql_num_rows', $existcheck) == 0) {
		$error_die[] = 'This ' . $type . ' no longer exists so adding a comment was not possible';
		return false;
	}
	if($fetch['comments'] != '1') {
		$error_die[] = 'Posting of comments on this ' . $type . ' is disabled';
		return false;
	}
	if(empty($message)) {
		$error[] = 'Please enter a message';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "INSERT INTO comments (comment_type, type_id, message, author, author_id, ip, post_time) VALUES ('$type', '$id', '$message', '" . $user['user'] . "', '".$user['id']."', '" . call('visitor_ip') . "', '" . time() . "' ) ");
		if($sql)
			return true;
	}
}
?>