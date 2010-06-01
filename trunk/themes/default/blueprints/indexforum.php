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
if (!(defined("IN_ECMS"))) die("Hacking Attempt...");

$fetch = call('indexforum');
$theme['head'] = '<link rel="alternate" type="application/rss+xml" title="Latest Posts" href="'.$settings['site_url'].'/index.php?act=feeds&amp;type=board&amp;export=rss" />';
$theme['title'] = $INDEXFORUM_LANG["title"];
$theme['body'] = theme('title', $INDEXFORUM_LANG["forum"]).theme('start_content').'
										<table id="forumArea" border="0" cellspacing="0" cellpadding="0" align="center" class="forum-board-area" width="100%">
											<tr id="forumHeadRow">
   												<th id="forumColForum" width="" nowrap="nowrap" align="left" class="forum-head forum-head-name">
													&nbsp;'.$INDEXFORUM_LANG["forum"].'&nbsp;
												</th>
												<th width="55%">
												</th>
   												<th id="forumColPosts" width="15%" nowrap="nowrap" align="left" class="forum-head forum-head-post-count">
													&nbsp;'.$INDEXFORUM_LANG["posts"].'&nbsp;
												</th>
   												<th id="forumColLast" width="30%" nowrap="nowrap" align="left" class="forum-head forum-head-post-info">
													&nbsp;'.$INDEXFORUM_LANG["last_post"].'&nbsp;
												</th>
  											</tr>';
if(!$fetch==false) {
	$d = 0;
# for each Forum Category
	foreach($fetch as $cat) {
		$theme['body'] .= '
  											<tr id="forumCatRow'.$d.'" class="forum-cat-row">
   												<td id="forumCatCell'.$d.'"  class="subtitlebg forum-cat-cell" colspan="4">
													'.$cat['cat_name'].'
												</td>
  											</tr>';
		$n = 0;
# for each Forum Board in a Category
		foreach($cat['boards'] as $board) {
			$theme['body'] .='
  											<tr id="forumCat'.$d.'row'.$n.'" class="content forum-board-row">
    											<td class="forum-board-image">';
			if(!$board['read_user_id'] && !$board['read_board_id']) {
				$theme['body'] .='
													<img class="forum-board-image-posts" src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/NewPosts.png" alt="New Posts" title="New Posts" />';
			} else {
				$theme['body'] .='
													<img class="forum-board-image-noposts" src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/NoNewPosts.png" alt="No New Posts" title="No New Posts" />';
			}
			$theme['body'].='
			 									</td>
    											<td class="forum-board-name">
													<a class="forum-board-name-link" href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id='.$board['board_id'].'">
														<b>
															'.$board['board_name'].'
														</b>
													</a>
													<br />
													'.$board['board_description'].'
												</td>
    											<td class="forum-board-post-count">
													'.$board['post_count'].' Posts <br />
													'.$board['topics_count'].' Topics
												</td>
    											<td class="forum-board-post-info">';
			if(!empty($board['last_msg'])) {
				$replies = @ceil($board['replies'] / $settings['posts_topic']);
				$theme['body'] .= '
													<span class="small-text forum-board-post-area forum-board-reply">
														<strong>
															Last post
														</strong> by '.$board['author'].'<br />
														in 
														<a class="forum-board-topic-link" href="';
				if($replies > 1)
					$theme['body'] .= $settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].'&amp;page='.$replies.'#'.$board['post_id'];
				else
					$theme['body'] .= $settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].'#'.$board['post_id'];
				$theme['body'] .= '" title="'.$board['subject'].'">
	 														'.$board['subject_shorten'].'
														</a>
														<br />
	 													on '.call('dateformat', $board['post_time']).'
													</span>';
			} else {
				$theme['body'] .= '
													<span class="forum-board-no-posts forum-board-replies">
														'.$INDEXFORUM_LANG["err_no_posts"].'
													</span>';
			}
			$theme['body'] .= '
											</td>
  										</tr>';
			$n++;	
		}
		$d++;
	}
} else {
# no Forum Categories or Board defined
	$theme['body'].= '
  									<tr id="forumCatNone" class="content">
    									<td class="forum-board-no-image">
											&nbsp;
										</td>
    									<td height="50" class="forum-board-no-name">
											'.$INDEXFORUM_LANG["err_no_boards"].'
										</td>
										<td height="50" class="forum-board-no-post-count">
											&nbsp;
										</td>
										<td height="50" class="forum-board-no-post-info">
											&nbsp;
										</td>
  									</tr>';
}
$theme['body'] .='
									</table>
								'.theme('end_content');
?>