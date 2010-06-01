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
function addarticlecat($name, $description, $visible) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name';
		return false;
	}
	if(empty($description)) {
		$error[] = 'You must specify a description';
		return false;
	}
	if(empty($visible))
	$error[] = 'You must state who this article category is visible to';
	else {
		foreach($visible as $key => $value) {
			$visible .= $value.',';
		}
		$visible = str_replace('Array', '', $visible);
		$count = strlen($visible);
		$visible = substr($visible, 0, $count - 1);
	}
	if(!errors()) {
		$sql = call('sql_query', "SELECT * FROM article_categories ORDER BY item_order DESC");
		$fetch = call('sql_fetch_array', $sql);
		$order = (call('sql_num_rows', $sql) == 0 ? 1 : $fetch['item_order'] + 1);	
		$query = call('sql_query', "INSERT INTO article_categories (name, description, item_order, visible) VALUES ('$name', '$description', '$order', '$visible')");
		if($query)
			return true;
	}
}
?>