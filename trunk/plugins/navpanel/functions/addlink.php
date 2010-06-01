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
function addlink($name, $link, $rank, $predefined, $authid, $window, $width='', $height='') {
	global $user, $error, $error_die;
	foreach($rank as $key => $value) {
		$newrank .= '|'.$value.'|';
	}
	$rank = $newrank;
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name';
		return false;
	}
	if(empty($link) && empty($predefined)) {
		$error[] = 'You must specify a url or a predefined page';
		return false;
	}
	if(!errors()) {
		if(!empty($predefined))
			$link = 'index.php?act=page&id=' . $predefined;
		$sql = call('sql_query', "SELECT * FROM navigation_menu ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array',$sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);
		$authid = (empty($authid) ? 0 : 1);
		$query = call('sql_query', "INSERT INTO navigation_menu (name, link, rank, item_order, authid, window, height, width) VALUES ('$name', '$link', '$rank', '$order', '$authid', '$window', 'height', '$width')");
		if($query)
			return true;
	}
}
?>