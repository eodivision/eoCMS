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
function addpanel($name, $content, $rank, $online, $side, $file, $all, $type) {
	global $user, $error, $error_die;   
	if(empty($rank)) {
		$error[] = 'You must state who this panel is visible to';
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