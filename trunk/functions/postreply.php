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
function postreply($topicid, $subject, $message, $token, $smiley, $time) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	if(empty($topicid) || !is_numeric($topicid)) {
		$error_die[] = 'No topic selected';
		return false;
	}
	if(empty($message)) {
		$error[] = 'Your message is empty';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM forum_topics WHERE topic_id = '$topicid'");
	$fetch = call('sql_fetch_array',$sql);
	if (call('sql_num_rows', $sql) == 0) {
		$error_die[] = 'Topic does not exist';
		return false;
	}
	if($fetch['locked'] == '1') {
		$error_die[] = 'This topic is locked! You may not post a reply';
		return false;
	}
	if(empty($subject))
		$subject = 'Re: ' . $fetch['topic_title'] . '';
	$sql_2 = call('sql_query', "SELECT * FROM forum_boards WHERE id = '" . $fetch['board_id'] . "'");
	$fetch_2 = call('sql_fetch_array',$sql_2);
	if (call('sql_num_rows', $sql_2) == 0) {
		$error_die[] = 'Error this board does not exist';
		return false;
	}
	if($user['reply_topics'] != 3) {
		$error_die[] = 'You do not have permission to create a new reply in this board';
		return false;
	}
	//check to see if someone has posted already in this topic while this user was posting
	$timecheck = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '$topicid' AND post_time>$time");
	if(call('sql_num_rows', $timecheck) !=0) {
		$error[] = 'Someone has made a reply while you were posting. Please review the post';
		unset($_SESSION['post_time']);
		return false;
	}
	if(!errors()) {
		$smiley = (empty($smiley) || !smiley ? 0 : 1);
		$deleteread = call('sql_query', "DELETE FROM topic_read WHERE topic_id = '$topicid' AND user_id!='".$user['id']."'");
		$deleteboardread = call('sql_query', "DELETE FROM board_read WHERE board_id = '" . $fetch['board_id'] . "'");
		$insertreply = call('sql_query', "INSERT INTO forum_posts (topic_id, board_id, post_time, author_id, name_author, subject, message, ip, disable_smiley) VALUES ('$topicid', '" . $fetch['board_id'] . "', '" . time() . "', '".$user['id']."', '" . $user['user'] . "', '$subject', '$message', '" . call('visitor_ip') . "', '$smiley')");
		$replyid = call('sql_insert_id');
		$updateboardcount = call('sql_query', "UPDATE forum_boards SET posts_count=posts_count+1, last_msg='$replyid' WHERE id='" . $fetch['board_id'] . "'");
		$updatereplycount = call('sql_query', "UPDATE forum_topics SET replies=replies+1, latest_reply='$replyid' WHERE topic_id='$topicid'");
		$updateuserspost = call('sql_query', "UPDATE users SET posts=posts+1  WHERE id='".$user['id']."'");
		if($insertreply)
			return true;
	}  
}
?>