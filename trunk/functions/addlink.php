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
function addlink($name, $link, $rank, $authid, $window, $width = 0 , $height = 0) {
	global $user, $error, $error_die;   
	if(empty($rank)) {
		$error[] = 'You must state who this board is visible to';
		return false;
	} else {
		$newrank = '';
		foreach($rank as $key => $value) {
			$newrank .= '|'.$value.'|';
		}
		$rank = $newrank;
	}
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name';
		return false;
	}
	if(empty($link)) {
		$error[] = 'You must specify a url';
		return false;
	}
	if($window == 'popup' && (empty($height) || empty($width) || !is_numeric($height) || !is_numeric($width))) {
		$error[] = 'You must specify the width/height for the popup as a whole number';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "SELECT * FROM menu ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array',$sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);
		$authid = (empty($authid) ? 0 : 1);
		$query = call('sql_query', "INSERT INTO menu (name, link, rank, item_order, authid, window, height, width) VALUES ('$name', '$link', '$rank', '$order', '$authid', '$window', '$height', '$width')");
		if($query)
			return true;
	}
}
?>