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
function unlocktopic($topicid) {
	global $user, $error_die;
	$check = call('sql_query', "SELECT * from forum_topics WHERE topic_id = '$topicid'");
	$fetch = call('sql_fetch_array',$check);
	if(($fetch['topic_author'] != $user['user'] && !$user['lock_own_topic']) || (!$user['lock_any_topic'])) {
		$error_die[] = 'You do not have permission to unlock this topic';
		return false;
	}
	if(empty($topicid)) {
  		$error_die[] = 'The id of the topic to be unlocked was not entered';
		return false;
	}
	if(call('sql_num_rows', $check) != 0) {
		$sql = call('sql_query', "UPDATE forum_topics SET locked = '0' WHERE topic_id = '$topicid'");
  		return true;
	} else { 
		$error_die[] = 'This topic does not exist!';
		return false;
	}
}
?>