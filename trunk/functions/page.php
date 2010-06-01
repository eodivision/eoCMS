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