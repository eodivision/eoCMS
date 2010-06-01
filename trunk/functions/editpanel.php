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
function editpanel($name, $content, $rank, $online, $side, $file, $all, $type, $id) {
	global $user, $error, $error_die;   
	if(empty($rank)) {
		$error[] = 'You must state who this board is visible to';
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
		$error[] = 'You must specify content or a file';
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name for the panel';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM panels WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This panel no longer exists';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "UPDATE panels SET panelname = '$name', panelcontent = '$content', rank = '$rank', side = '$side', online = '$online', file = '$file', all_pages = '$all', type = '$type' WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>