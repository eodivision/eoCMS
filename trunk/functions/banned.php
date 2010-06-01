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
function banned() {
	global $error_die;
	$banned = array();
	$sql = call('sql_query', "SELECT * FROM bans", 'cache');
	foreach($sql as $fetch) {
		$banned[] = array('ip' => $fetch['ip'],
					 	  'reason' => $fetch['reason'],
					  	  'ip_range' => $fetch['ip_range']);
	}
	$visitor_range = substr(call('visitor_ip'), 0, strlen(call('visitor_ip')) - strlen(strrchr(call('visitor_ip'), ".")));
	$visitor_range = substr($visitor_range, 0, strlen($visitor_range) - strlen(strrchr($visitor_range, ".")));
	foreach($banned as $ban) {
		if((call('visitor_ip') == $ban['ip']) || $visitor_range == $ban['ip_range'])
			$error_die[] = call('visitor_ip') . ' you have been banned from this site. <br />Reason: ' . $ban['reason'];
	}
}
?>