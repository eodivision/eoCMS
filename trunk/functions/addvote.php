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
function addvote($id, $type, $option) {
	global $user, $error, $error_die;
	if(!$user['vote']) {
		$error[] = 'You do not have permission to vote on this page';
		return false;
	}
	//check the option
	$sql = call('sql_query', "SELECT * FROM polls WHERE type_id = '$id' AND poll_type = '$type'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This poll does not exist';
		return false;
	} else {
		$r = call('sql_fetch_array', $sql);
		$sql = call('sql_query', "SELECT * FROM poll_options WHERE option = '$option' AND poll_id = '".$r['id']."'");
		if(call('sql_num_rows', $sql) == 0) {
			$error[] = 'You chose an option for your vote';
			return false;
		}
	}
	$query = call('sql_query', "SELECT id FROM poll_votes WHERE poll_id = '".$r['id']."' AND (user_id = '".$user['id']."' OR ip = '".call('visitor_ip')."') LIMIT 1");
	if(call('sql_num_rows', $query) != 0) {
		$error[] = 'You have already voted!';
		return false;
	}
	if(!errors()) {
		$sql = call("sql_query", "INSERT INTO poll_votes (poll_id, option, ip, user_id) VALUES ('".$r['id']."', '$option', '".call('visitor_ip')."', '".$user['id']."')");
		if($sql)
			return true;
	}
}	
?>