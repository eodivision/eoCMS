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
function movetopic($id, $boardid, $title, $message, $redirect, $token) {
	global $user, $settings, $error, $error_die;
	call('checktoken', $token);
	$sql = call('sql_query', "SELECT * FROM forum_topics WHERE topic_id = '$id'");
	$fetch = call('sql_fetch_array',$sql);
	$postcount = call('sql_num_rows', call('sql_query', "SELECT id FROM forum_posts WHERE topic_id='$id'"));
	if(($fetch['thread_author'] != $user['user'] && !$user['move_own_topic']) || (!$user['move_any_topic'])) {
		$error_die[] = 'You do not have permission to move this topic';
		return false;
	}
	if(empty($id)) {
		$error_die[] = 'No topic selected';
		return false;
	}
	if (empty($boardid)) {
		$error_die[] = 'No board selected';
		return false;
	}
	if (call('sql_num_rows', $sql) == 0) {
		$error_die[] = 'This topic does not exist';
		return false;
	}
	$sql2 = call('sql_query', "SELECT id, board_name FROM forum_boards WHERE id = '" . $boardid . "'");
	$board = call('sql_fetch_array',$sql2);
	if(call('sql_num_rows', $sql2) ==0) {
		$error_die[] = 'This board does not exist';
		return false;
	}
	if(!isset($redirect)) {
		if (empty($title)) {
			$error[] = 'Your topics title is empty';
			return false;
		}
		if (empty($message)) {
			$error[] = 'Your message is empty';
			return false;
		}
	}
	if(!errors()) {
		if(isset($redirect)) {
			$message = substr_replace('[BOARD]', "[url='index.php?act=viewboard&id=$boardid']" . $board['board_name'] . "[/url]", $message);
			$message = substr_replace('[TOPIC LINK]', 'This topic has moved to [url]' . $settings['site_url'] . '/index.php?act=viewtopic&id=' . $id . '[/url]', $message);
			$insertpost = call('sql_query', "INSERT INTO forum_topics (board_id, thread_title, thread_author, topic_ip, date_created, locked) VALUES ('" . $fetch['board_id'] . "', '$title', '" . $user['user'] . "', '" . call('visitor_ip') . "', '" . time() . "', '1')");
			$topicid = call('sql_insert_id');
			$inserttopic = call('sql_query', "INSERT INTO forum_posts (topic_id, board_id, post_time, author_id, name_author, subject, message, ip) VALUES ('$topicid', '" . $fetch['board_id'] . "', '" . time() . "', '".$user['id']."', '" . $user['user'] . "', '$title', '$message', '" . call('visitor_ip') . "')");
			$postid = call('sql_insert_id');
			$updatetopiclastpost = call('sql_query', "UPDATE forum_topics SET latest_reply='$postid' WHERE topic_id='$topicid'");
			$lasttopic = call('sql_fetch_array',call('sql_query', "SELECT id FROM forum_posts WHERE board_id='" . $fetch['board_id'] . "' AND id != '$postid' ORDER BY post_time DESC LIMIT 1"));
			$updateboardcount = call('sql_query', "UPDATE forum_boards SET ".(isset($redirect) ? "" : "topics_count=topics_count-1,")." posts_count=posts_count-" . (isset($redirect) ? ($postcount + 1) : $postcount) . ", last_msg ='$postid'  WHERE id='" . $fetch['board_id'] . "'");
		  		  $updateboardcount2 = call('sql_query', "UPDATE forum_boards SET topics_count=topics_count+1, posts_count=posts_count+" . $postcount . ", last_msg ='" . $lasttopic['id'] . "'  WHERE id='$boardid'");
			$movetopic = call('sql_query', "UPDATE forum_topics SET board_id = '$boardid' WHERE topic_id = '$id'");
			$movepost = call('sql_query', "UPDATE forum_posts SET board_id = '$boardid' WHERE topic_id = '$id'");
		} else {
			$lasttopic = call('sql_fetch_array',call('sql_query', "SELECT id FROM forum_posts WHERE board_id='" . $fetch['board_id'] . "' ORDER BY post_time DESC LIMIT 1"));
			$lastpost = call('sql_fetch_array',call('sql_query', "SELECT id FROM forum_posts WHERE topic_id = '$id' ORDER BY post_time DESC LIMIT 1"));
			$updateboardcount2 = call('sql_query', "UPDATE forum_boards SET topics_count=topics_count+1, posts_count=posts_count+" . $postcount . ", last_msg ='" . $lastpost['id'] . "'  WHERE id='" . $boardid . "'");
			$updateboardcount = call('sql_query', "UPDATE forum_boards SET topics_count=topics_count-1, posts_count=posts_count-" . $postcount . ", last_msg ='" . $lasttopic['id'] . "'  WHERE id='" . $fetch['board_id'] . "'");
			$movetopic = call('sql_query', "UPDATE forum_topics SET board_id = '$boardid' WHERE topic_id = '$id'");
		  $movepost = call('sql_query', "UPDATE forum_posts SET board_id = '$boardid' WHERE topic_id = '$id'");
		}
		if($movetopic && $movepost)
			return true; 
	}
}
?>