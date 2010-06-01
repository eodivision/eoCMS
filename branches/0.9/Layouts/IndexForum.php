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
if (!(defined("IN_ECMS"))) die("Hacking Attempt...");

if (!$user['guest']) {
	if ($user['posts_topic'] != '0' && $user['posts_topic'] != '') {
		$settings['posts_topic'] = $user['posts_topic'];
	}
}
$fetch = call('indexforum');
$head = '<link rel="alternate" type="application/rss+xml" title="Latest Posts" href="' . $settings['site_url'] . '/index.php?act=feeds&amp;type=board&amp;export=rss" />';
$title = $INDEXFORUM_LANG["title"];
$body = theme('title', $INDEXFORUM_LANG["forum"]).theme('start_content').'
<table id="forumArea" border="0" cellspacing="0" cellpadding="0" align="center" class="forum-board-area" width="100%">
  <tr id="forumHeadRow">
    <th id="forumColForum" width="" nowrap="nowrap" align="left" class="forum-head forum-head-name">&nbsp;'.$INDEXFORUM_LANG["forum"].'&nbsp;</th>
	<th width="55%"></th>
    <th id="forumColPosts" width="15%" nowrap="nowrap" align="left" class="forum-head forum-head-post-count">&nbsp;'.$INDEXFORUM_LANG["posts"].'&nbsp;</th>
    <th id="forumColLast" width="30%" nowrap="nowrap" align="left" class="forum-head forum-head-post-info">&nbsp;'.$INDEXFORUM_LANG["last_post"].'&nbsp;</th>
  </tr>';
if(!$fetch==false) {
	$d = 0;
# for each Forum Category
	foreach($fetch as $cat) {
		$body .= '
  <tr id="forumCatRow' . $d . '" class="forum-cat-row">
    <td id="forumCatCell' . $d . '"  class="subtitlebg forum-cat-cell" colspan="4">'.$cat['cat_name'].'</td>
  </tr>';
		$n = 0;
# for each Forum Board in a Category
		foreach($cat['boards'] as $r) {
			if(!empty($r['last_msg'])) {
				$replies = @ceil($r['replies'] / $settings['posts_topic']);
				if ($replies>1) {
					$post = '
	<span class="small-text forum-board-post-area forum-board-reply"><strong>Last post</strong> by ';
					if (call('userprofilelink', $r['author_id']) == false) {
						$post .= $r['name_author'];
					} else {
						$post .= call('userprofilelink', $r['author_id']);
					}
              				$post .= '<br />
	 in <a class="forum-board-topic-link" href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $r['topic_id'] . '&amp;page='.$replies.'#' . $r['post_id'] . '">' . $r['subject'] . '</a><br />
	 on ' . call('dateformat', $r['post_time']) . '</span>';
				} else {
					$post = '
	<span class="small-text forum-board-post-area forum-board-replies"><strong>Last post</strong> by ';
					if (!empty($r['author_id']) && call('userprofilelink', $r['author_id']) == false) {
						$post .= $r['name_author'];
					} elseif(!empty($r['author_id'])) {
						$post .= call('userprofilelink', $r['author_id']);
					} else {
						$post .= 'Guest';
					}
					$post .= '<br />
	 in <a class="forum-board-topic-link" href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $r['topic_id'] . '#' . $r['post_id'] . '" title="'.$r['subject'].'">' . $r['subject_shorten'] . '</a><br />
	 on ' . call('dateformat', $r['post_time']) . '</span>';
				}
			} else {
				$post = '<span class="forum-board-no-posts forum-board-replies">'.$INDEXFORUM_LANG["err_no_posts"].'</span>';
			}
			$body .='
  <tr id="forumCat' . $d . 'row' . $n . '" class="content forum-board-row">
    <td class="forum-board-image">';
			if(!$r['read_user_id'] && !$r['read_board_id']) {
				$body .='<img class="forum-board-image-posts" src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/NewPosts.png" alt="New Posts" title="New Posts" />';
			} else {
				$body .='<img class="forum-board-image-noposts" src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/NoNewPosts.png" alt="No New Posts" title="No New Posts" />';
			}
			 $body.='</td>
    <td class="forum-board-name"><a class="forum-board-name-link" href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id='.$r['board_id'].'"><b>'.$r['board_name'].'</b></a><br />
	'.$r['board_description'].'</td>
    <td class="forum-board-post-count">'.$r['post_count'].' Posts <br />
	'.$r['topics_count'].' Topics</td>
    <td class="forum-board-post-info">' . $post . '</td>
  </tr>';
			$n++;	
		}
		$d++;
	}
} else {
# no Forum Categories or Board defined
	$body.= '
  <tr id="forumCatNone" class="content">
    <td class="forum-board-no-image">&nbsp;</td>
    <td height="50" class="forum-board-no-name">'.$INDEXFORUM_LANG["err_no_boards"].'</td>
    <td height="50" class="forum-board-no-post-count">&nbsp;</td>
    <td height="50" class="forum-board-no-post-info">&nbsp;</td>
  </tr>';
}
$body .='
</table>'.theme('end_content');
?>