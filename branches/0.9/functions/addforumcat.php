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
function addforumcat($name) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array',$sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);
		$query = call('sql_query', "INSERT INTO forum_categories (cat_name, item_order) VALUES ('$name', '$order')");
		if($query)
			return true;
	}
}
?>