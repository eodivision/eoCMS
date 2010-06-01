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
function locktopic($topicid) {
	global $user, $eror_die;
	$check = call('sql_query', "SELECT * from forum_topics WHERE topic_id = '$topicid'");
	$fetch = call('sql_fetch_array',$check);
	if(($fetch['thread_author'] != $user['user'] && !$user['lock_own_topic']) || (!$user['lock_any_topic'])) {
		$error_die[] = 'You do not have permission to lock this topic';
		return false;
	}
	if(empty($topicid)) {
		$error_die[] = 'The id of the topic to be locked was not entered';
		return false;
	}
	if(call('sql_num_rows', $check) != 0) {
		$sql = call('sql_query', "UPDATE forum_topics SET locked = '1' WHERE topic_id = '$topicid'");
		return true;
	} else { 
		$error_die[] = 'This topic does not exist!';
		return false; 
	}
}
?>