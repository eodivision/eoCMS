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

if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
$body .= '<div class="panel-header">'.theme('title', 'Latest Posts') . '</div>'.theme('start_content_panel') .'<table class="latest-topics">
  <tr class="subtitlebg">
    <th width="45%" nowrap="nowrap" scope="col" align="left">Topic</th>
    <th width="10%" nowrap="nowrap" scope="col" align="center">Replies</th>
    <th width="10%" nowrap="nowrap" scope="col" align="center">Views</th>
    <th width="35%" nowrap="nowrap" scope="col">Last Post</th>
  </tr>';
$sql = call('sql_query', "SELECT b.id, b.board_name, t.replies, b.visible, t.topic_id, t.topic_title, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, t.views FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply GROUP BY t.topic_id ORDER BY p.post_time DESC LIMIT 10");
if(call('sql_num_rows', $sql) != 0) {
	$fetch = array();
	while ($pow = call('sql_fetch_array',$sql)) {
		if(call('visiblecheck', $user['membergroup_id'],  $pow[3]) && !empty($pow[4])) {
			$fetch[] = array(
				'board_id'=>$pow[0],
				'board_name'=>$pow[1],
				'replies'=>$pow[2],
				'visible'=>$pow[3],
				'topic_id'=>$pow[4],
				'topic_title'=>$pow[5],
				'latest_reply'=>$pow[6],
				'post_time'=>$pow[7],
				'name_author'=>$pow[8],
				'author_id'=>$pow[9],
				'post_id'=>$pow[10],
				'views'=>$pow[11]);
		}
	}
	foreach($fetch as $p) {
		$replies = ceil($p['replies'] / $settings['posts_topic']);
		if ($replies > 1) {
			$post = '<span class="small-text"><a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;page='.$replies.'&amp;id=' . $p['topic_id'] . '#' . $p['post_id'] . '">' . call('dateformat', $p['post_time']) . '</a> by ';
			if (call('userprofilelink', $p['author_id']) == false) {
				$post .= $p['name_author'];
			} else {
				$post .= call('userprofilelink', $p['author_id']);
			}
			$post .= '</span>';
		} else {
			$post = '<span class="small-text"><a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $p['topic_id'] . '#' . $p['post_id'] . '">' . call('dateformat', $p['post_time']) . '</a> by ';
			if (!empty($p['author_id']) && call('userprofilelink', $p['author_id']) == false) {
				$post .= $p['name_author'];
			} elseif(!empty($p['author_id'])) {
				$post .= call('userprofilelink', $p['author_id']);
			} else {
				$post .= 'Guest';
			}
			$post .= '</span>';
		}
		$body .='<tr><td><a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $p['topic_id'] . '">' . $p['topic_title'] . '</a><br /><span class="small-text">Board: <a href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id='.$p['board_id'].'">'.$p['board_name'].'</a></span></td><td align="center">'.$p['replies'].'</td><td align="center">'.$p['views'].'</td><td>'.$post.'</td></tr>';
	}
} else {
	$body .= '<tr><td>No posts</td></tr>';
}
$body .='</table>' . theme('end_content');
?>