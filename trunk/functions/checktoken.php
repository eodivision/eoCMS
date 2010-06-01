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
function checktoken($token) {
	global $error;
	$sql = call('sql_query', "SELECT time_expired FROM csrf_forms WHERE token = '".$token."'");
	if(call('sql_num_rows', $sql) == 0)
		$error[] = 'The token sent with the form is incorrect please re-submit the form';
	else {
		list($expire) = call('sql_fetch_array', $sql);
		if(time() > $expire)
			$error[] = 'Your session has timed out, please re-submit the form';
	}
}
?>
