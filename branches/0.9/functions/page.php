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
function page($id) {
	global $error_die;
	if(!isset($id) || !is_numeric($id)) {
		$error_die[] = 'This page does not exist';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM pages WHERE id='" . $id . "'");
	if(call('sql_num_rows', $sql) == 0) {
		$error_die[] = 'This page does not exist';
		return false;
	} else {
		$fetch = call('sql_fetch_array',$sql);
		return $fetch;
	}
}
?>