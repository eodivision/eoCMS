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
function delete_topics($topicsarray, $postcount = true) {
	global $user, $error, $error_die;
	if(empty($topics)) {
		$error_die[] = 'The id of the topics to be deleted was not entered or not valid';
		return false;
	}
	$topicsarray = (is_numeric($topicsarray) ? array($topicsarray) : $topicsarray);
	$topics = implode(', ', $topicsarray);
	$checktopic = call('sql_query', "SELECT board_id FROM forum_topics WHERE topic_id IN ($topics)");
	if(call('sql_num_rows', $checktopic) == 0) {
		$error_die[] = 'This topic does not exist';
		return false;
	}
	$fetchtopicdata = call('sql_fetch_array', $checktopic);
	$boardid = $fetchtopicdata['board_id'];
	if(($fetchtopicdata['topic_author'] != $user['user'] && !$user['delete_own_topic']) || (!$user['delete_any_topic'])) {
		$error_die[] = 'You do not have permission to delete this post';
		return false;
	}
	if(!errors()) {
		if($postcount) { //decrease the users' post counts
			$sql = call('sql_query', "UPDATE users SET posts = posts - ( SELECT COUNT( * ) AS posts FROM forum_posts AS p LEFT JOIN forum_boards AS b ON p.board_id = b.id WHERE p.topic_id IN ($topics) AND users.id = p.author_id GROUP BY p.author_id)"); // unsure if it works in SQLite, needs testing
		}
		// update each of the boards topics/post count
		$sql = call('sql_query', "UPDATE forum_boards SET last_msg = ( SELECT COUNT( * ) AS posts FROM forum_posts AS p LEFT JOIN forum_boards AS b ON p.board_id = b.id WHERE p.topic_id IN ($topics) AND users.id = p.author_id GROUP BY p.author_id), posts_count = (), topics_count = ()");
		$deleteposts = call('sql_query', "DELETE FROM forum_posts WHERE topic_id IN ($topics)");
		$deletetopics = call('sql_query', "DELETE FROM forum_topics WHERE topic_id IN ($topics)'");
			$result6 = call('sql_query', "UPDATE forum_boards SET last_msg='0', posts_count=0, topics_count=0 WHERE id='$boardid'");
		return true;  
	}	
}
?>