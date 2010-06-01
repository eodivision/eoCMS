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
function article_cats() {
	global $user;
    $sql = call('sql_query', "SELECT c.id, c.name, c.description, c.visible, COUNT(p.cat) FROM article_categories c LEFT JOIN articles p ON c.id=p.cat GROUP BY c.id ORDER BY c.item_order ASC");
	if (call('sql_num_rows', $sql) == 0)
		return false;
	$fetch = array();
	while ($row = call('sql_fetch_array',$sql)) {
		if(call('visiblecheck', $user['membergroup_id'], $row[3])) {
			$fetch[] = array('cat_id'=>$row[0],
							 'name'=>$row[1],
							 'description'=>$row[2],
							  'articles'=>$row[4]);
		}
	}
	return $fetch;
}
?>