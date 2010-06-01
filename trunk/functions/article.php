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
function article($id) {
	global $user, $error_die;
	if(!isset($id) || empty($id) || !is_numeric($id))
		$error_die[] = 'No article selected';
	$sql = call('sql_query', "SELECT c.visible, p.subject, p.summary, p.full_article, p.time_created, p.views, p.author_id, p.name_author, p.comments, p.ratings FROM articles p LEFT JOIN article_categories c ON c.id=p.cat WHERE p.id = '$id'");
	if (call('sql_num_rows', $sql) == 0) {
		$error_die[] = 'This article does not exist';
		return false;
	}
	$fetch = array();
	while ($row = call('sql_fetch_array',$sql)) {
		if(call('visiblecheck', $user['membergroup_id'], $row[0])) {
			$fetch[] = array('subject'=>$row[1],
							 'summary'=>$row[2],
							 'full_article'=>$row[3],
							 'time_created'=>call('dateformat', $row[4]),
							 'views'=>$row[5],
							 'author'=>(!empty($row[6]) && call('userprofilelink', $row[6]) != false ? call('userprofilelink', $row[6]) : $row[7]),
							 'comments'=>$row[8],
							 'ratings'=>$row[9]);
		}
	}
	$query = call('sql_query', "UPDATE articles SET views = views+1 WHERE id ='$id'");
	return $fetch;
}
?>