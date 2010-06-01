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
function indexforum() {
	global $user;
	if(!$user['guest'])
		$read = "AND r.user_id = '".$user['id']."'";
	else
	$read = '';
	$sql = call('sql_query', "SELECT b.id, b.board_name, b.topics_count, b.posts_count, b.visible, b.last_msg, p.id, p.post_time, p.author_id, p.name_author, p.topic_id, t.replies, b.board_description, r.user_id, r.board_id, p.subject, b.cat FROM forum_boards AS b LEFT OUTER JOIN forum_posts AS p ON p.id = b.last_msg LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id AND p.topic_id = t.topic_id LEFT OUTER JOIN board_read AS r ON r.board_id = b.id " . $read . " GROUP BY b.id ORDER BY item_order ASC");
	$fetch = array();
    $cats = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order ASC", 'cache');
	foreach($cats as $cat) {
		$fetch[$cat['id']] = array('cat_id'=>$cat['id'], 'cat_name'=>$cat['cat_name'], 'boards'=>array());
	}
    while ($row = call('sql_fetch_array',$sql)) {
		if(call('visiblecheck', $user['membergroup_id'], $row[4])) {
			$fetch[$row[16]]['boards'][$row[0]] = array('board_id'=>$row[0],
						 'board_name'=>$row[1],
						 'topics_count'=>$row[2],
						 'post_count'=>$row[3],
						 'visible'=>$row[4],
						 'last_msg'=>$row[5],
						 'post_id'=>$row[6],
						 'post_time'=>$row[7],
						 'author_id'=>$row[8],
						 'name_author'=>$row[9],
						 'topic_id'=>$row[10],
						 'replies'=>$row[11],
						 'board_description'=>$row[12],
						 'read_user_id'=>$row[13],
						 'read_board_id'=>$row[14],
						 'subject'=>$row[15],
						 'subject_shorten'=>substr($row[15], 0, 25).'...');
		}
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