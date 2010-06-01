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
function viewboard($boardgetid) {
	global $settings, $user;
	if(isset($boardgetid) && is_numeric($boardgetid)) {
		if(!$user['guest']) {
			if($user['topics_page'] != '0' && $user['topics_page'] != '')
				$settings['topics_page'] = $user['topics_page'];
		}
		// how many rows to show per page
		$rowsPerPage = $settings['topics_page'];
		// by default we show first page
		$pageNum = 1;
		// if $_GET['page'] defined, use it as page number
		if(isset($_GET['page']) && is_numeric($_GET['page']))
			$pageNum = $_GET['page'];
		// counting the offset
		$offset = ($pageNum - 1) * $rowsPerPage;
		if(!$user['guest'])
			$read = "AND r.user_id = ".$user['id'];
		else
			$read = '';
		//Sticky topics
		$sticky = call('sql_query', "SELECT b.id, b.board_name, t.replies, b.post_group, b.visible, t.topic_id, t.thread_title, t.thread_author, t.date_created, t.locked, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, r.topic_id, r.user_id, t.views, t.sticky, p.message FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply LEFT OUTER JOIN topic_read AS r ON t.topic_id = r.topic_id " . $read . " WHERE b.id = '$boardgetid' AND p.id=t.latest_reply AND t.sticky = '1' GROUP BY t.topic_id ORDER BY p.post_time DESC");
		//Non-sticky topics
		$nonsticky = call('sql_query', "SELECT b.id, b.board_name, t.replies, b.post_group, b.visible, t.topic_id, t.thread_title, t.thread_author, t.date_created, t.locked, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, r.topic_id, r.user_id, t.views, t.sticky, p.message FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply LEFT OUTER JOIN topic_read AS r ON t.topic_id = r.topic_id " . $read . " WHERE b.id = '$boardgetid' AND p.id=t.latest_reply AND t.sticky = '0' GROUP BY t.topic_id ORDER BY p.post_time DESC LIMIT $offset, $rowsPerPage");
		if (call('sql_num_rows', $nonsticky) == 0 && call('sql_num_rows', $sticky) == 0)
			return false;
		else {
			$fetch = array();
			//get the message of every first post for the topics
			$message = call('sql_query', "SELECT p.topic_id, p.message FROM forum_posts AS p LEFT JOIN forum_topics AS t ON p.topic_id = t.topic_id WHERE p.board_id = '".$boardgetid."' AND t.sticky = '0' GROUP BY p.topic_id ORDER BY post_time DESC LIMIT $offset, $rowsPerPage");
			while($topic = call('sql_fetch_array', $message)) {
				$messages[$topic[0]] = htmlentities(substr(call('strip_html_bbcode', $topic[1]), 0, 150).'...', ENT_QUOTES);
			}
			//go through and get the sticky topics messages
			$message = call('sql_query', "SELECT p.topic_id, p.message FROM forum_posts AS p LEFT JOIN forum_topics AS t ON p.topic_id = t.topic_id WHERE p.board_id = '".$boardgetid."' AND t.sticky = '1' GROUP BY p.topic_id ORDER BY post_time DESC");
			while($topic = call('sql_fetch_array', $message)) {
				$messages[$topic[0]] = htmlentities(substr(call('strip_html_bbcode', $topic[1]), 0, 150).'...', ENT_QUOTES);
			}
			//add sticky topics
			while ($row = call('sql_fetch_array', $sticky)) {
				if(call('visiblecheck', $user['membergroup_id'], $row[4])) {
					$fetch[] = array('board_id'=>$row[0],
									'board_name'=>$row[1],
									'replies'=>$row[2],
									'post_group'=>$row[3],
									'visible'=>$row[4],
									'topic_id'=>$row[5],
									'thread_title'=>$row[6],
									'thread_author'=>$row[7],
									'date_created'=>$row[8],
									'locked'=>$row[9],
									'latest_reply'=>$row[10],
									'post_time'=>$row[11],
									'author'=>(!empty($row[13]) && call('userprofilelink', $row[13]) != false ? call('userprofilelink', $row[13]) : (empty($row[12]) ? 'Guest' : $row[12])),
									'post_id'=>$row[14],
									'read_topic_id'=>$row[15],
									'read_user_id'=>$row[16],
									'views'=>$row[17],
									'sticky'=>$row[18],
									'message'=>$messages[$row[5]]);
				}
			}
			//add the rest
			while ($row = call('sql_fetch_array', $nonsticky)) {
				if(call('visiblecheck', $user['membergroup_id'], $row[4])) {
					$fetch[] = array('board_id'=>$row[0],
									'board_name'=>$row[1],
									'replies'=>$row[2],
									'post_group'=>$row[3],
									'visible'=>$row[4],
									'topic_id'=>$row[5],
									'thread_title'=>$row[6],
									'thread_author'=>$row[7],
									'date_created'=>$row[8],
									'locked'=>$row[9],
									'latest_reply'=>$row[10],
									'post_time'=>$row[11],
									'author'=>(!empty($row[13]) && call('userprofilelink', $row[13]) != false ? call('userprofilelink', $row[13]) : (empty($row[12]) ? 'Guest' : $row[12])),
									'post_id'=>$row[14],
									'read_topic_id'=>$row[15],
									'read_user_id'=>$row[16],
									'views'=>$row[17],
									'sticky'=>$row[18],
									'message'=>(isset($messages[$row[5]]) ? $messages[$row[5]] : ''));
				}
			}
			return $fetch;
		}
	}  
}
?>