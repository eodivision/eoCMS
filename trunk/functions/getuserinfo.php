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
			$fetch = call('sql_fetch_assoc', $sql);
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
		while ($userrow = call('sql_fetch_assoc', $permissionquery)) {
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