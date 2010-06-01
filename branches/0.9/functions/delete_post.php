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
function delete_post($postid) {
	global $user, $error, $error_die;
	if (empty($postid) || !is_numeric($postid)) {
		$error_die[] = 'The id of the post to be deleted was not entered or is not valid';
		return false;
	}
	$checkpost = call('sql_query', "SELECT * FROM forum_posts WHERE id = '$postid'");
	$fetch = call('sql_fetch_array', $checkpost);
	if (call('sql_num_rows', $checkpost) == 0) {
		$error_die[] = 'This post does not exist';
		return false;
	}
	if(($fetch['author_id'] != $user['id'] && !$user['delete_own_posts']) || (!$user['delete_any_posts'])) {
		$error_die[] = 'You do not have permission to delete this post';
		return false;
	}
	if(!errors()) {
		//check if the post is the only one in the topic
		$checktopic = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '" . $fetch['topic_id'] . "'");
		if (call('sql_num_rows', $checktopic) == 1) {
			$sql = call('delete_topic', $fetch['topic_id'], $fetch['board_id']); //looks like it is so lets delete it!
			//redirect them back to the board
			header("Location: index.php?act=viewboard&id=".$fetch['board_id']);
			exit;
		}
		else {
			$lastposttopic = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '" . $fetch['topic_id'] . "' ORDER BY post_time DESC LIMIT 1");
			$lastposttopicfetch = call('sql_fetch_array', $lastposttopic);
			$sql = call('sql_query', "DELETE FROM forum_posts WHERE id = '$postid'");
			$checktopic = call('sql_query', "SELECT * FROM forum_posts WHERE board_id = '" . $fetch['board_id'] . "' AND topic_id = '" . $fetch['topic_id'] . "' ORDER BY post_time DESC LIMIT 1");
			$fetchpostid = call('sql_fetch_array', $checktopic);
			$updateboard = call('sql_query', "UPDATE forum_boards SET posts_count=posts_count-1, last_msg='" . $fetchpostid['id'] . "' WHERE id='" . $fetch['board_id'] . "'");
			$updatetopic = call('sql_query', "UPDATE forum_topics SET replies=replies-1, latest_reply = '" . $fetchpostid['id'] . "' WHERE topic_id = '" . $fetch['topic_id'] . "'");
			$userspost = call('sql_query', "UPDATE users SET posts=posts-1 WHERE id = '" . $fetch['author_id'] . "'");
		}
		if ($sql)
			return true;
	}
}
?>