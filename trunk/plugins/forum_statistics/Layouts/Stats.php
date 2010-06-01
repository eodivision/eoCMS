<?php
# forum statistics
$body .= '<div id="forumStats">'.theme('title', $INDEXFORUM_LANG["theme_title_stats"]) . theme('start_content');

# get the date when the admin signed up!
$sql = call('sql_query', "SELECT regdate FROM users ORDER BY id ASC LIMIT 1", 'cache');
$age = $sql[0][0];

# get the total number of members
$sql = call('sql_query', "SELECT COUNT(id) AS total FROM users", 'cache');
$total_users = $sql[0][0];

# get the total number of topics and posts 
$sql = call('sql_query', "SELECT SUM(topics_count) AS topics, SUM(posts_count) AS posts FROM forum_boards", 'cache');
$total_posts = $sql[0][1];
$total_topics = $sql[0][0];
$postsperday = round($total_posts/((time() - $age)/(3600*24)));
$topicsperday = round($total_topics/((time() - $age)/(3600*24)));

# get the latest member
$sql = call('sql_query', "SELECT id FROM users ORDER BY id DESC LIMIT 1", 'cache');
$latest_member = call('userprofilelink', $sql[0][0]);

# top poster
$sql = call('sql_query', "SELECT id, posts FROM users ORDER BY posts DESC LIMIT 1", 'cache');
$top_poster = call('userprofilelink', $sql[0][0]);
$top_poster_posts = $sql[0][1];
$body.= $total_posts.' Posts in '.$total_topics.' Topics by '.$total_users.' Members. Latest Member: '.$latest_member.'<br />
'.$topicsperday.' Topics and '.$postsperday.' Posts per day<br />
<div class="subtitlebg">Top Poster: '.$top_poster.' ('.$top_poster_posts.' Posts)</div>
<br />
Users Online: ';

# display whos online
$users_online = call('sql_query', "SELECT user_id FROM user_online WHERE user_id != '0'");

# count how many is online so we know when to stop putting a ,
$online = call('sql_query', "SELECT COUNT(user_id) AS numrows FROM user_online WHERE user_id!='0'", 'cache');
$online = $online[0][0];
$i = 0;
while($p = call('sql_fetch_array', $users_online)) {
	$i++;
	$body .= call('userprofilelink', $p['user_id'], 'colour');
	if($i!=$online)
		$body.=', ';
}

# get the number of guests online!
$guests = call('sql_query', "SELECT COUNT(user_id) AS numrows FROM user_online WHERE user_id = '0'", 'cache');
$guests = $guests[0][0];

# custom grammar for guests
$body .= (($guests>0 && $i>0) ? ', and ' : '') . ' ' . ($guests>0 ? ($guests==1 ? $guests . ' Guest' : $guests . ' Guests'):'');
$body .= theme('end_content').'</div>';
?>