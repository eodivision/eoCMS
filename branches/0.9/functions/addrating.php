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