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
function editlink($name, $link, $predefined, $rank, $authid, $window, $width='', $height='', $id) {
	global $user, $settings, $error, $error_die;   
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
	$sql = call('sql_query', "SELECT * FROM navigation_menu WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This link no longer exists';
		return false;
	}
	if(!empty($predefined))
		$link = $settings['site_url'].'/index.php?act=page&id=' . $predefined; 
	$authid = (empty($authid) ? 0 : 1);
	if($window == 'popup' && (empty($height) || empty($width) || !is_numeric($height) || !is_numeric($width))) {
		$error[] = 'You must specify the width/height for the popup as a whole number';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "UPDATE navigation_menu SET name = '$name', link = '$link', rank = '$rank', authid = '$authid', window='$window', width='$width', height='$height' WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>