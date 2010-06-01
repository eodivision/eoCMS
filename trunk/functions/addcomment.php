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