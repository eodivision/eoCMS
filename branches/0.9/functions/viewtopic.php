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
function viewtopic($topicid) {
	global $settings, $user, $error_die;
	if (isset($topicid) && is_numeric($topicid)) {
		if(!$user['guest']) {
			if($user['posts_topic'] != '0' && $user['posts_topic'] != '')
				$settings['posts_topic'] = $user['posts_topic'];
		}
		# how many rows to show per page
		$rowsPerPage = $settings['posts_topic'];
		# by default we show first page
		$pageNum = 1;
		# if $_GET['page'] defined, use it as page number
		if(isset($_GET['page']) && is_numeric($_GET['page']))
			$pageNum = $_GET['page'];
		# counting the offset
		$offset = ($pageNum - 1) * $rowsPerPage;
		$sql = call('sql_query', "SELECT u.avatar, u.posts, u.membergroup, u.signature, u.icq, u.aim, u.msn, u.yim, t.board_id, t.thread_title, t.thread_author, t.topic_ip, t.date_created, t.locked, p.id, p.post_time, p.author_id, p.name_author, p.subject, p.message, p.ip, p.disable_smiley, p.modified_time, p.modified_name, p.modified_nameid FROM forum_posts AS p LEFT OUTER JOIN forum_topics AS t ON p.topic_id=t.topic_id LEFT OUTER JOIN users AS u ON p.author_id=u.id WHERE t.topic_id = '$topicid' ORDER BY p.id ASC LIMIT $offset, $rowsPerPage");
		if(call('sql_num_rows', $sql) != 0) {
			$fetch = array();
			while ($row = call('sql_fetch_array',$sql)) {
				$fetch[] = array(
					'avatar'=>$row[0],
					'posts'=>$row[1],
					'membergroup'=>$row[2],
					'signature'=>$row[3],
					'icq'=>$row[4],
					'aim'=>$row[5],
					'msn'=>$row[6],
					'yim'=>$row[7],
					'board_id'=>$row[8],
					'thread_title'=>$row[9],
					'thread_author'=>$row[10],
					'topic_ip'=>$row[11],
					'date_created'=>$row[12],
					'locked'=>$row[13],
					'post_id'=>$row[14],
					'post_time'=>$row[15],
					'author_id'=>$row[16],
					'name_author'=>$row[17],
					'subject'=>$row[18],
					'message'=>$row[19],
					'ip'=>$row[20],
					'disable_smiley'=>$row[21],
					'modified_time'=>$row[22],
					'modified_author' =>(!empty($row[24]) && call('userprofilelink', $row[24]) != false ? call('userprofilelink', $row[24]) : $row[23])
				);
			}
# update the number of views in a topic
			$query = call('sql_query', "UPDATE forum_topics SET views = views+1 WHERE topic_id ='$topicid'");
			return($fetch);
		} else {
			$error_die[] = 'Error this topic does not exist';
			return false;
		}
	}
}
?>