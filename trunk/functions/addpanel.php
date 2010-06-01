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
function addpanel($name, $content, $rank, $online, $side, $file, $all, $type) {
	global $user, $error, $error_die;   
	if(empty($rank)) {
		$error[] = 'You must state who this panel is visible to';
		return false;
	} else {
		foreach($rank as $key => $value) {
			$newrank .= '|'.$value.'|';
		}
		$rank = $newrank;
	}
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($content) && empty($file)) {
		$error[] = 'You must specify content or a file for the panel';
		return false;
	}
	if(empty($type))
		$type = 'html';
	elseif($type != 'html' && $type != 'php') {
		$error[] = 'Invalid panel content type';
		return false;
	}
	if(empty($name) && empty($file)) {
		$error[] = 'You must specify a name for the panel';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "SELECT * FROM panels WHERE side = '$side' ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array', $sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);
		$query = call('sql_query', "INSERT INTO panels (panelname, panelcontent, rank, side, online, file, all_pages, item_order, type) VALUES ('$name', '$content', '$rank', '$side', '$online', '$file', '$all', '$order', '$type')");
		if($query)
			return true;
	}
}
?>