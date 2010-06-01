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
function indexforum() {
	global $user;
	if(!$user['guest']) {
		if($user['posts_topic'] != '0' && $user['posts_topic'] != '') {
			$settings['posts_topic'] = $user['posts_topic'];
		}
	}
	if(!$user['guest'])
		$read = "AND r.user_id = '".$user['id']."'";
	else
		$read = '';
	$sql = call('sql_query', "SELECT b.id, b.board_name, b.topics_count, b.posts_count, b.visible, b.last_msg, p.id, p.post_time, p.author_id, p.name_author, p.topic_id, t.replies, b.board_description, r.user_id, r.board_id, p.subject, b.cat, bp.variable, bp.value FROM forum_boards AS b LEFT OUTER JOIN forum_posts AS p ON p.id = b.last_msg LEFT OUTER JOIN forum_topics AS t ON b.id = t.board_id AND p.topic_id = t.topic_id LEFT OUTER JOIN board_read AS r ON r.board_id = b.id ".$read." LEFT OUTER JOIN board_permissions AS bp ON b.id = bp.board_id WHERE bp.variable = 'view' AND bp.value = '2' AND bp.membergroup_id = '".$user['membergroup_id']."' GROUP BY b.id ORDER BY item_order ASC");
	$fetch = array();
    $cats = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order ASC", 'cache');
	foreach($cats as $cat) {
		$fetch[$cat['id']] = array('cat_id' => $cat['id'], 'cat_name' => $cat['cat_name'], 'boards' => array());
	}
    while($row = call('sql_fetch_array',$sql)) {
		$fetch[$row[16]]['boards'][$row[0]] = array(
					 'author' =>(!empty($row[8]) && call('userprofilelink', $row[8]) != false ? call('userprofilelink', $row[8]) : (empty($row[9]) ? 'Guest' : $row[9])),
					 'author_id' => $row[8],
					 'board_id' => $row[0],
					 'board_description' => $row[12],
					 'board_name' => $row[1],
					 'last_msg' => $row[5],
					 'name_author' => $row[9],
					 'post_count' => $row[3],
					 'post_id' => $row[6],
					 'post_time' => $row[7],
					 'read_board_id' => $row[14],
					 'read_user_id' => $row[13],
					 'replies' => $row[11],
					 'subject' => $row[15],
					 'subject_shorten' => substr($row[15], 0, 25).'...',
					 'topic_id' => $row[10],
					 'topics_count' => $row[2],
					 'view' => $row[18]);
    }
	foreach($cats as $cat) {
		//see if there are any boards in a category
		if(!count($fetch[$cat['id']]['boards'])) {
		//looks like there arent any so lets remove the category from display
			unset($fetch[$cat['id']]);
		}
	}
	return $fetch;
}
?>