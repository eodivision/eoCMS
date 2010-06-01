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
function article_cat($id) {
	global $user, $error_die;
	if(!isset($id) || empty($id) || !is_numeric($id))
		$error_die[] = 'No category selected';
	$sql = call('sql_query', "SELECT c.visible, COUNT(p.cat), p.subject, p.summary, p.id FROM article_categories c LEFT JOIN articles p ON c.id=p.cat WHERE c.id = '".$id."' GROUP BY p.id");
	if (call('sql_num_rows', $sql) == 0)
		return false;
	$fetch = array();
	while($row = call('sql_fetch_array',$sql)) {
	if(call('visiblecheck', $user['membergroup_id'], $row[0])) {
		$fetch[] = array('articles'=>$row[1],
						 'subject'=>$row[2],
						 'summary'=>$row[3],
						 'id'=>$row[4]);
		}
	}
	return $fetch;
}
?>