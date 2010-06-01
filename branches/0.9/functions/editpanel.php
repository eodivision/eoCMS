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
function editpanel($name, $content, $rank, $online, $side, $file, $all, $type, $id) {
	global $user, $error, $error_die;   
	if(empty($rank)) {
		$error[] = 'You must state who this board is visible to';
		return false;
	} else {
		foreach($rank as $key => $value) {
			$rank .= $value.',';
		}
		$rank = str_replace('Array', '', $rank);
		$count = strlen($rank);
		$rank = substr($rank, 0, $count - 1);
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