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
function unstickytopic($topicid) {
	global $user, $error, $error_die;
	if(empty($topicid)) {
		$error_die[] = 'The id of the topic to be made a sticky was not entered';
		return false;
	}
	$checktopic = call('sql_query', "SELECT thread_author FROM forum_topics WHERE topic_id = '$topicid'");
	$fetch = call('sql_fetch_array',$checktopic);
	if(call('sql_num_rows', $checktopic) == 0) {
		$error_die[] = 'This topic does not exist';
		return false;
	}
    if(($fetch['thread_author'] == $user['user'] && $user['sticky_own_topic']) || $user['sticky_any_topic']) {
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