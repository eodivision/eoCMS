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