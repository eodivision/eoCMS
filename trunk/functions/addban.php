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
function addban($ip, $reason, $type) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($ip)) {
		$error[] = 'You must specify an ip address to ban';
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
			$query = call('sql_query', "INSERT INTO bans (ip, reason, time_created, created_by) VALUES ('$ip', '$reason', '" . time() . "', '".$user['id']."')");
		elseif($type == 'range')
			$query = call('sql_query', "INSERT INTO bans (ip_range, reason, time_created, created_by) VALUES ('$ip', '$reason', '" . time() . "', '".$user['id']."')");
		if($query)
			return true;
	}
}
?>