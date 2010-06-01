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
function trackip($ip) {
	global $settings;
	// how many rows to show per page
	$rowsPerPage = $settings['topics_page'];
	// by default we show first page
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	    $pageNum = $_GET['page'];
	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
	$fetch = array();
	if(!errors()) {
		$sql = call('sql_query', "SELECT id, topic_id, author_id, post_time, subject, ip FROM forum_posts WHERE ip LIKE '%$ip%' ORDER BY post_time DESC LIMIT $offset, $rowsPerPage");
		if(call('sql_num_rows', $sql) !=0) {
			while($r = call('sql_fetch_array', $sql)) {
				$fetch[] = array('post_id'=>$r['id'],
					  'topic_id'=>$r['topic_id'],
					  'author'=>call('userprofilelink', $r['author_id']),
					  'post_time'=>call('dateformat', $r['post_time']),
					 'subject'=>'<a href="'.$settings['site_url'].'/index.php?act=viewtopic&id='.$r['topic_id'].'&page='.ceil($r['id'] / $settings['posts_topic']).'#'.$r['id'].'" target="_blank">'.$r['subject'].'</a>',
					  'ip'=>$r['ip'],
					  'type'=>'post');
			}
		} else
			$fetch[] = array('ip'=>'The search returned zero results', 'type'=>'post');
		$sql = call('sql_query', "SELECT id, ip FROM users WHERE ip LIKE '%$ip%'");
		if(call('sql_num_rows', $sql) !=0) {
			while($r = call('sql_fetch_array', $sql)) {
				$fetch[] = array('user'=>call('userprofilelink', $r['id']),
					 'ip'=>$r['ip'],
					 'type'=>'user');
			}
		} else
			$fetch[] = array('ip'=>'The search returned zero results', 'type'=>'user');
	}
	return $fetch;
}
?>