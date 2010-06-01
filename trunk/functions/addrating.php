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
function addrating($id, $type, $rating) {
	global $user, $error, $error_die;
	if(!$user['post_rating']) {
		$error[] = 'You do not have permission to rate this page';
		return false;
	}
	if(!is_numeric($rating) || $rating > 5) {
		$error[] = 'You chose an invalid number for your rating';
		return false;
	}
	$query = call('sql_query', "SELECT id FROM ratings WHERE type='$type' AND type_id = '$id' AND (user_id = '".$user['id']."' OR ip = '".call('visitor_ip')."') LIMIT 1");
	if(call('sql_num_rows', $query) != 0) {
		$error[] = 'You have already rated this page';
		return false;
	}
	if(!errors()) {
		$sql = call("sql_query","INSERT INTO ratings (type, type_id, rating, ip, user_id) VALUES('$type', '$id', '$rating', '".call('visitor_ip')."', '".$user['id']."')");
		if($sql)
			return true;
	}
}	
?>