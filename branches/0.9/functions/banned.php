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