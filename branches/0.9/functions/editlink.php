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
function editlink($name, $link, $predefined, $rank, $authid, $window, $width='', $height='', $id) {
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
	if(empty($name)) {
		$error[] = 'You must specify a name';
		return false;
	}
	if(empty($link) && empty($predefined)) {
		$error[] = 'You must specify a url or a predefined page';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM menu WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This link no longer exists';
		return false;
	}
	if(!empty($predefined))
		$link = 'index.php?act=page&id=' . $predefined; 
	$authid = (empty($authid) ? 0 : 1);
	if($window == 'popup' && (empty($height) || empty($width) || !is_numeric($height) || !is_numeric($width))) {
		$error[] = 'You must specify the width/height for the popup as a whole number';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "UPDATE menu SET name = '$name', link = '$link', rank = '$rank', authid = '$authid', window='$window', width='$width', height='$height' WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>