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
function unstickytopic($topicid) {
	global $user, $error, $error_die;
	if(empty($topicid)) {
		$error_die[] = 'The id of the topic to be made a sticky was not entered';
		return false;
	}
	$checktopic = call('sql_query', "SELECT topic_author FROM forum_topics WHERE topic_id = '$topicid'");
	$fetch = call('sql_fetch_array',$checktopic);
	if(call('sql_num_rows', $checktopic) == 0) {
		$error_die[] = 'This topic does not exist';
		return false;
	}
    if(($fetch['topic_author'] == $user['user'] && $user['sticky_own_topic']) || $user['sticky_any_topic']) {
    	if(!errors()) {
			$query = call('sql_query', "UPDATE forum_topics SET sticky = '0' WHERE topic_id = '$topicid'");
			if($query)
				return true;
		} else {
			$error_die[] = 'You do not have permission to perform this action';
			return false;
		}
	}
}
?>