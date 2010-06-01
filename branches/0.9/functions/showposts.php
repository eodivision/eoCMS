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
function showposts($id) {
global $settings, $user;
	  	  if(!$user['guest']) {
if($user['posts_topic'] != '0' && $user['posts_topic'] != '') {
$settings['posts_topic'] = $user['posts_topic'];
}
}
// how many rows to show per page
$rowsPerPage = $settings['posts_topic'];
// by default we show first page
$pageNum = 1;
// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
    $pageNum = $_GET['page'];
}
// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;
          $sql = call('sql_query', "SELECT t.board_id, p.id, p.post_time, p.subject, p.message, p.disable_smiley, b.visible, b.board_name, t.replies, t.topic_id FROM forum_posts AS p LEFT OUTER JOIN forum_topics AS t ON p.topic_id=t.topic_id LEFT OUTER JOIN forum_boards AS b ON t.board_id = b.id WHERE p.author_id = '$id' ORDER BY p.post_time DESC LIMIT $offset, $rowsPerPage");
		  $num = call('sql_num_rows', $sql);
		  if($num != 0) {
          $fetch = array();
          while ($row = call('sql_fetch_array',$sql)) {
		  if(call('visiblecheck', $user['membergroup_id'], $row[6])) {
              $fetch[] = array('board_id'=>$row[0],
							   'post_id'=>$row[1],
							   'post_time'=>$row[2],
							   'subject'=>$row[3],
							   'message'=>$row[4],
							   'disable_smiley'=>$row[5],
							   'board_name'=>$row[7],
							   'replies'=>ceil($row[8] / $settings['posts_topic']),
							   'topic_id'=>$row[9]);
							   }
          }
		  $fetch['num'] = $num;
          return($fetch);
		  }
}
?>