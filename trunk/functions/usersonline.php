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
function usersonline() {
	global $user;
	//set the amount of time the number of guests and users stored as online
	$timeout = time() - 600;
	//delete anyone that is greate than the $timeout
	$deleteuseronline = call('sql_query', "DELETE FROM user_online WHERE time_online<$timeout");
	if($user['guest']) {
		//visiting person is a guest, check to see if they are already in the table
		$check_table = call('sql_query', "SELECT ip FROM user_online WHERE ip = '" . call('visitor_ip') . "'");
	} else {
		//person is a user, check to see if they are in the table
		$check_table = call('sql_query', "SELECT user_id FROM user_online WHERE user_id = '".$user['id']."'");
	}
	//check the number of rows returned, if 0 insert them
	if(call('sql_num_rows', $check_table) == 0) {
		if ($user['guest']) {
			//insert user as a guest
			$insertuseronline = call('sql_query', "INSERT INTO user_online (user_id, time_online, ip) VALUES ('0', '".time()."', '" . call('visitor_ip') . "')");
		} else {
			//insert user
			$insertuseronline = call('sql_query', "INSERT INTO user_online (user_id, time_online, ip) VALUES ('".$user['id']."', '".time()."', '" . call('visitor_ip') . "')");
			//update the last active
			call('sql_query', "UPDATE users SET lastlogin = '".time()."', time_online = '".($user['time_online'] + 600)."' WHERE id = '".$user['id']."'");
		}
	}
}
?>