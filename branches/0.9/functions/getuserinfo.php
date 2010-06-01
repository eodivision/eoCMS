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
function getuserinfo() {
	//set login to false by default
	$login = false;
	//make user guest by default
	$user['guest'] = 1;
	//get the domain name
	$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	if(isset($_COOKIE[COOKIE_NAME])) {
		$sql = call('sql_query', "SELECT * FROM users WHERE ssid = '".$_COOKIE[COOKIE_NAME]."' LIMIT 1");
		if(call('sql_num_rows', $sql) != 0) {
			$fetch = call('sql_fetch_array', $sql);
			//make sure the browser matches
			if($fetch['agent'] == $_SERVER['HTTP_USER_AGENT']) {
				$login = true;
			} else { //doesnt match, meaning huge security exploit with the user or someone else trying to hack
				//destroy the cookie so they cant try again
        		setcookie(COOKIE_NAME, '', time()-(60*60*24*100), '', $domain);
			}
		}
	} else
		$login = false;	
	if($login === true) {
		$user = $fetch;
		$user['membergroup_id'] = $user['membergroup'];
		$permissionquery = call('sql_query', "SELECT permissions.variable AS variable, permissions.value AS value FROM membergroups LEFT JOIN users ON membergroups.membergroup_id=membergroup LEFT JOIN permissions ON permissions.membergroup_id=membergroups.membergroup_id WHERE users.id = '".$user['id']."' AND permissions.membergroup_id = membergroup");
		while ($userrow = call('sql_fetch_array',$permissionquery)) {
			$user[$userrow['variable']] = $userrow['value'];
		}
		$user['guest'] = 0;
	} else {
		$userquery = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id = '1'", 'cache');
		foreach($userquery as $userrow) {
			$user[$userrow['variable']] = $userrow['value'];
		}
		$user['membergroup_id'] = 1;
	}
	return $user;
}
?>