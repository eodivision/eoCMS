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
function viewboard($boardgetid, $page) {
	global $settings, $user;
	if(isset($boardgetid) && is_numeric($boardgetid)) {
		if(!$user['guest']) {
			if ($user['topics_page']!='0' && $user['topics_page']!='') {
				$settings['topics_page'] = $user['topics_page'];
			}
			if ($user['posts_topic']!='0' && $user['posts_topic']!='') {
				$settings['posts_topic'] = $user['posts_topic'];
			}
		}
		// how many rows to show per page
		$rowsPerPage = $settings['topics_page'];
		// by default we show first page
		if(!isset($page) || !is_numeric($page)) {
			$page = 1;
			$_GET['page'] = 1;
		}
		// counting the offset
		$offset = ($page - 1) * $rowsPerPage;
		// grab the permissions for this board!
		call('getforumpermission', $_GET['id']);
		//Sticky topics
		$sticky = call('sql_query', "SELECT b.id, b.board_name, t.replies, b.post_group, b.visible, t.topic_id, t.topic_title, t.topic_author, t.date_created, t.locked, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, t.views, t.sticky, p.message FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply LEFT OUTER JOIN board_permissions bp ON bp.board_id = b.id WHERE b.id = '$boardgetid' AND p.id=t.latest_reply AND t.sticky = '1' AND bp.variable = 'view' AND bp.value = '2' GROUP BY t.topic_id ORDER BY p.post_time DESC", 'cache');
		//Non-sticky topics
		$nonsticky = call('sql_query', "SELECT b.id, b.board_name, t.replies, b.post_group, b.visible, t.topic_id, t.topic_title, t.topic_author, t.date_created, t.locked, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, t.views, t.sticky, p.message FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply LEFT OUTER JOIN board_permissions bp ON bp.board_id = b.id WHERE b.id = '$boardgetid' AND p.id=t.latest_reply AND t.sticky = '0' AND bp.variable = 'view' AND bp.value = '2' GROUP BY t.topic_id ORDER BY p.post_time DESC LIMIT $offset, $rowsPerPage", 'cache');
		if (call('sql_num_rows', $nonsticky) == 0 && call('sql_num_rows', $sticky) == 0)
			return false;
		else {
			$fetch = array();
			// merge the sticky and non-sticky together
			$merged = array_merge($sticky, $nonsticky);
			unset($sticky, $nonsticky);
			// Grab the topic id's that we need for message hover
			$topics = implode(',', $merged);
			// get the message of every first post for the topics
			$message = call('sql_query', "SELECT p.topic_id, p.message FROM forum_posts AS p LEFT JOIN forum_topics AS t ON p.topic_id = t.topic_id WHERE p.board_id = '".$boardgetid."' AND t.topic_id IN($topics) GROUP BY p.topic_id ORDER BY post_time", 'cache');
			foreach($message as $topic) {
				$messages[$topic[0]] = htmlentities(substr(call('strip_html_bbcode', $topic[1]), 0, 150).'...', ENT_QUOTES);
			}
			unset($message);
			// See if they have read the topics that are in the list yet
			$read = array();
			if(!$user['guest']) {
				$reads = call('sql_query', "SELECT * FROM topic_read WHERE topic_id IN($topics) AND user_id = '".$user['id']."'");
				while($r = call('sql_fetch_array', $reads)) {
					$read[$r['topic_id']] = true;
				}
			}
			foreach($merged as $row) {
				$fetch[] = array(
								'board_id'=> $row[0],
								'board_name'=> $row[1],
								//incrament by one because the first post in the topic is not included with this number and so messes up pagination
								'replies'=> ($row[2] != 0 ? $row[2] + 1 : $row[2]),
								'post_group'=> $row[3],
								'visible'=> $row[4],
								'topic_id'=> $row[5],
								'topic_title'=> $row[6],
								'topic_author'=> (!empty($row[7]) ? call('userprofilelink', $row[7]) : 'Guest'), 
								'date_created'=> $row[8],
								'locked'=> $row[9],
								'latest_reply'=> $row[10],
								'post_time'=> $row[11],
								'author'=>(!empty($row[13]) && call('userprofilelink', $row[13]) != false ? call('userprofilelink', $row[13]) : (empty($row[12]) ? 'Guest' : $row[12])),
								'post_id'=> $row[14],
								'read_topic_id'=> (isset($read[$row[5]]) ? true: false),
								'views'=> $row[15],
								'sticky'=> $row[16],
								'message'=> $messages[$row[5]]);
			}
			if(!$user['guest'] && isset($_GET['id'])) {
				// Check to see if the board is read
				$sql = call('sql_query', "SELECT * FROM board_read WHERE user_id = '".$user['id']."' AND board_id = '$boardgetid'");
				if(call('sql_num_rows', $sql) == 0)
					call('sql_query', "INSERT INTO board_read (user_id, board_id) VALUES ('".$user['id']."', '$boardgetid')");
			}
			// check to see if anything was posted with the multi-topic moderation
			if($user['moderator'] && isset($_POST['topics'])) {
				if($_POST['checked']=='delete') {
					foreach($_POST['topics'] as $delete) {
						$moderation = call('delete_topic', $delete, $_GET['id']);
					}
				}
				if($_POST['checked']=='lock') {
					foreach($_POST['topics'] as $lock) {
						$moderation = call('locktopic', $lock);
					}
				}
				if($_POST['checked']=='unlock') {
					foreach($_POST['topics'] as $unlock) {
						$moderation = call('unlocktopic', $unlock);
					}
				}
				if($_POST['checked']=='sticky') {
					foreach($_POST['topics'] as $sticky) {
						$moderation = call('stickytopic', $sticky);
					}
				}
				if($_POST['checked']=='unsticky') {
					foreach($_POST['topics'] as $unsticky) {
						$moderation = call('unstickytopic', $unsticky);
					}
				}
				call('redirect', $settings['site_url'].'/index.php?act=viewboard&id='.$boardgetid);
			}
			return $fetch;
		}
	}  
}
?>