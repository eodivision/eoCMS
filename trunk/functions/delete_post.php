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
			call('redirect', 'index.php?act=viewboard&id='.$fetch['board_id']);
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