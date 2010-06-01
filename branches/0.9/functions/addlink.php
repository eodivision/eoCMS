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
function addlink($name, $link, $rank, $predefined, $authid, $window, $width='', $height='') {
	global $user, $error, $error_die, $settings;   
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
	if(empty($name)) {
		$error[] = 'You must specify a name';
		return false;
	}
	if(empty($link) && empty($predefined)) {
		$error[] = 'You must specify a url or a predefined page';
		return false;
	}
	if($window == 'popup' && (empty($height) || empty($width) || !is_numeric($height) || !is_numeric($width))) {
		$error[] = 'You must specify the width/height for the popup as a whole number';
		return false;
	}
	if(!errors()) {
		if(!empty($predefined))
			$link = $settings['site_url'].'/index.php?act=page&id=' . $predefined;
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