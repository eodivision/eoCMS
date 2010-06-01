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
function newtopic($board, $title, $message, $token, $smiley) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	if(empty($board)) {
		$error_die[] = 'No board selected';
		return false;
	}
	if(empty($title)) {
		//Lazy bums now we have to redirect them back to the page!
		$error[] = 'Your topics title is empty';
		return false;
	}
	if(empty($message)) {
		//Great, why make a topic with a blank message? Talk about pointless
		$error[] = 'Your message is empty';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM forum_boards WHERE id = '$board'");
	$fetch = call('sql_fetch_array',$sql);
	if(call('sql_num_rows', $sql) == 0) {
		//uh oh, it looks like someones trying to post in a non-existant board
		$error_die[] = 'Error this board does not exist';
		return false;
	}
	if(!call('visiblecheck', $user['membergroup_id'], $fetch['post_group'])) {
		//Why do people insist on trying to do stuff they are not meant to!
		$error_die[] = 'You do not have permission to create a new topic in this board';
		return false;
	}
	if(!$user['admin_panel'])  {
		$c = call('sql_query', "SELECT date_created, thread_author from forum_topics WHERE topic_ip = '".call('visitor_ip')."'");
		while ($c2 = call('sql_fetch_array', $c)) {
			$difference = time() - $c2['date_created'];
			if ($difference < 100) {
				//Spammers!
				$error[] = $c2['thread_author'].', You have already posted, please wait for a few minutes and try again later';
				return false;
			}
		}
	}
	if(!errors()) {
		$smiley = (empty($smiley) || !$smiley ? 0 : 1);
		$deleteboardread = call('sql_query', "DELETE FROM board_read WHERE board_id = '$board'");
		$creationtable = '';
		$creation = '';
		if($fetch['creation_sticky'] == '1') {
			$creationtable .= ', sticky';
			$creation .= ', \'1\'';
		} 
		if($fetch['creation_lock'] == '1') {
			$creationtable.=', locked';
			$creation .= ', \'1\'';
		}
		$insertpost = call('sql_query', "INSERT INTO forum_topics (board_id, thread_title, thread_author, topic_ip, date_created".$creationtable.") VALUES ('$board', '$title', '" . $user['user'] . "', '" . call('visitor_ip') . "', '" . time() . "'".$creation.")");
		$topicid = call('sql_insert_id');
		$read = call('sql_query', "INSERT INTO topic_read (topic_id, user_id) VALUES('$topicid', '".$user['id']."')");
		$updateuserspost = call('sql_query', "UPDATE users SET posts=posts+1  WHERE id='" . $user['id'] . "'");
		$inserttopic = call('sql_query', "INSERT INTO forum_posts (topic_id, board_id, post_time, author_id, name_author, subject, message,  ip, disable_smiley) VALUES ('$topicid', '$board', '" . time() . "', '".$user['id']."', '" . $user['user'] . "', '$title', '$message', '" . call('visitor_ip') . "', '$smiley')");
		$postid = call('sql_insert_id');
		$updateboardcount = call('sql_query', "UPDATE forum_boards SET topics_count=topics_count+1, posts_count=posts_count+1, last_msg ='$postid'  WHERE id='$board'");
		$updatetopiclastpost = call('sql_query', "UPDATE forum_topics SET latest_reply='$postid' WHERE topic_id='$topicid'");
		if($inserttopic)
			return true;
	}
}
?>