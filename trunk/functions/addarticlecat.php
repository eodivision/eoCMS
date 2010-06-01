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
			$newvisible .= '|'.$value.'|';
		}
		$visible = $newvisible;
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