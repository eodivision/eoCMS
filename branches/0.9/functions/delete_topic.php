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
function delete_topic($topicid, $boardid) {
	global $user, $error, $error_die;
	if(empty($topicid) || !is_numeric($topicid)) {
		$error_die[] = 'The id of the topic to be deleted was not entered or not valid';
		return false;
	}
	if(empty($boardid) || !is_numeric($boardid)) {
		$error_die[] = 'The id of the board was not entered or not valid';
		return false;
	}
	$checktopic = call('sql_query', "SELECT * FROM forum_topics WHERE topic_id = '$topicid'");
	$fetchtopicdata = call('sql_fetch_array',$checktopic);
	if(call('sql_num_rows', $checktopic) == 0) {
		$error_die[] = 'This topic does not exist';
		return false;
	}
	if(($fetchtopicdata['thread_author'] != $user['user'] && !$user['delete_own_topic']) || (!$user['delete_any_topic'])) {
		$error_die[] = 'You do not have permission to delete this post';
		return false;
	}
	if(!errors()) {
		$result = call('sql_query', "SELECT name_author, COUNT(id) as num_posts FROM forum_posts WHERE topic_id='$topicid' GROUP BY name_author");
		if (call('sql_num_rows', $result) !=0) {
			while ($postdata = call('sql_fetch_assoc', $result)) {
				$result2 = call('sql_query', "UPDATE users SET posts=posts-".$postdata['num_posts']." WHERE user='".$postdata['name_author']."'");
			}
		}		
		$topicdataquery = call('sql_query', "SELECT topic_id, latest_reply FROM forum_topics WHERE topic_id = '$topicid'");
		$topicdata = call('sql_fetch_array',$topicdataquery);
		$topics_count = call('sql_query', "SELECT * FROM forum_topics WHERE board_id ='$boardid'");
		$result7 = call('sql_query', "DELETE FROM forum_posts WHERE topic_id='$topicid'");
		$del_posts = call('sql_affected_rows');
		$result3 = call('sql_query', "DELETE FROM forum_topics WHERE topic_id='$topicid'");
		if (call('sql_num_rows', $topics_count) > 0) {
			$result4 = call('sql_query', "SELECT * FROM forum_boards WHERE id='$boardid' AND last_msg='".$topicdata['latest_reply']."'");
			if (call('sql_num_rows', $result4) != 0) {
				$result5 = call('sql_query', "SELECT id, board_id, post_time FROM forum_posts WHERE board_id='$boardid' ORDER BY post_time DESC LIMIT 1");
				$pdata = call('sql_fetch_assoc', $result5);
				$result6 = call('sql_query', "UPDATE forum_boards SET last_msg='".$pdata['id']."', posts_count=posts_count-".$del_posts.", topics_count=topics_count-1 WHERE id='$boardid'");
			}
		} else
			$result6 = call('sql_query', "UPDATE forum_boards SET last_msg='0', posts_count=0, topics_count=0 WHERE id='$boardid'");
		return true;  
	}	
}
?>