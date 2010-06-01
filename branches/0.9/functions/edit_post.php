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
function edit_post($id, $subject, $message, $token) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	$sql = call('sql_query', "SELECT * FROM forum_posts WHERE id = '$id'");
	$fetch = call('sql_fetch_array',$sql);
	if(((isset($user['id']) && $fetch['author_id'] == $user['id'] && $user['modify_own_posts']) || ($user['guest'] && $fetch['ip'] == call('visitor_ip'))) || ($user['modify_any_posts'])) {
		if(empty($id)) {
			$error_die[] = 'No post selected';
			return false;
		}
		if(empty($message)) {
			$error[] = 'Your message is empty';
			return false;
		}
		if(call('sql_num_rows', $sql) == 0) {
			  $error_die[] = 'This post does not exist';
			  return false;
		}
		$sql2 = call('sql_query', "SELECT * FROM forum_topics WHERE topic_id = '" . $fetch['topic_id'] . "'");
		$fetch2 = call('sql_fetch_array', $sql2);
		if($fetch2['locked'] == '1') {
			$error_die[] = 'This topic is locked! You may not modify your post';
			return false;
		}
		$firstpost = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '" . $fetch['topic_id'] . "' ORDER BY post_time ASC LIMIT 1");
		$fetchfirst = call('sql_fetch_array', $firstpost);
		if(!errors()) {
			//set the topic as new
			$deleteread = call('sql_query', "DELETE FROM topic_read WHERE topic_id = '$topicid' AND user_id!='".$user['id']."'");
			if($fetchfirst['id'] == $id)
				$updatetitle = call('sql_query', "UPDATE forum_topics SET thread_title = '$subject' WHERE topic_id = '" . $fetch['topic_id'] . "'");
				$updatepost = call('sql_query', "UPDATE forum_posts SET subject = '$subject', message = '$message', modified_time = '".time()."', modified_name = '".$user['user']."', modified_nameid = '".$user['id']."' WHERE id = '$id'");
			if ($updatepost)
				return true;
		}
	} else {
		$error_die[] = 'You do not have permission to edit this post';
		return false;
	}
}
?>