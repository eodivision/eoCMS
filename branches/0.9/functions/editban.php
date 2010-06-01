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
function editban($ip, $reason, $type, $id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($ip)) {
		$error[] = 'You must specify an ip address to ban';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM bans WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This ban no longer exists';
		return false;
	}
	if($type == 'ip' && !preg_match('^([1]?\d{1,2}|2[0-4]{1}\d{1}|25[0-5]{1})(\.([1]?\d{1,2}|2[0-4]{1}\d{1}|25[0-5]{1})){3}$^', $ip)) {
		$error[] = 'Invalid IP';
		return false;				   
	}
	$visitor_range = substr(call('visitor_ip'), 0, strlen(call('visitor_ip')) - strlen(strrchr(call('visitor_ip'), ".")));
	$visitor_range = substr($visitor_range, 0, strlen($visitor_range) - strlen(strrchr($visitor_range, ".")));
	if((call('visitor_ip') == $ip) || $visitor_range == $ip) {
		$error[] = 'You can not ban your own IP';
		return false;
	}
	if(!errors()) {
		if($type == 'ip')
			$query = call('sql_query', "UPDATE bans SET ip = '$ip', reason = '$reason', created_by = '".$user['id']."', time_created = '".time()."' WHERE id = '$id'");
		elseif($type == 'range')
			$query = call('sql_query', "UPDATE bans SET ip_range = '$ip', reason = '$reason', created_by = '".$user['id']."', time_created = '".time()."' WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>