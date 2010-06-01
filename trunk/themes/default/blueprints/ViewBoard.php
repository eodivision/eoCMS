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
$fetch = call('viewboard', $_GET['id'], (isset($_GET['page']) ? $_GET['page'] : ''));
$bcrumb = call('sql_query', "SELECT id, board_name FROM forum_boards WHERE id= '".$_GET['id']."'");
if(call('sql_num_rows', $bcrumb) == 0)
	$error_die[] = $VIEWBOARD_LANG["error_die"];
else {
	$row = call('sql_fetch_array', $bcrumb);
	$theme['head'] = call('seokeyword', $row['board_name']).call('seodescription', $row['board_name']).'
		<link rel="alternate" type="application/rss+xml" title="Latest Posts" href="'.$settings['site_url'].'/index.php?act=feeds&amp;type=board&amp;board='.$_GET['id'].'&amp;export=rss" />'.($user['moderator'] ? '
		<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.jeditable.pack.js"></script>
		<script type="text/javascript">
			<!--
				$(document).ready(function() {
					$("#select_all").click(function(){
						var checked_status = this.checked;
						$("input[name=\'topics[]\']").each(function(){
							this.checked = checked_status;
							if(this.checked)
								$("#topic_"+this.value).addClass("inline");
							else
								$("#topic_"+this.value).removeClass("inline");
						});
					});
					$("#checked").change(function() {
						$("#withselected").submit();
					});
				});
				function edit_title(id) {
					$("#topictitle_"+id).editable(
						"'.$settings['site_url'].'/index.php?act=ajax&m=edittopictitle&board_id='.$_GET['id'].'", { 
						style:   "inherit",
						type:    "text",
						loadurl: "'.$settings['site_url'].'/index.php?act=ajax&m=gettitle&board_id='.$_GET['id'].'",
						event:   "dblclick"
					});
				}
				function topicselect(id) {
					$("#topic_"+id).toggleClass("inline");
				}
		// -->
	</script>' : '').'
		<script type="text/javascript">
			<!--
				function jumpboard(boardid) {
					document.location.href=\''.$settings['site_url'].'/index.php?act=viewboard&id=\'+boardid;
				}
			// -->
		</script>';
	$theme['title'] = $row['board_name'];
	$pagination = call('pagination', $_GET['page'], $settings['topics_page'], 'SELECT COUNT(topic_id) AS numrows FROM forum_topics WHERE board_id = '.$_GET['id'].' AND sticky = "0"', '?act=viewboard&amp;id='.$_GET['id'].'&amp;page=', 3);
	$theme['body'] = '
						<table id="boardName" border="0" cellspacing="0" cellpadding="0" width="100%">
 							<tr class="subtitlebg">
   								<th id="boardLinks" nowrap="nowrap" scope="col" align="left">
									'.theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=forum" class="link link-text topic-links topic-forum-link">'.$VIEWBOARD_LANG["forum_home"].'</a>', '<a href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id='.$row['id'].'" class="link link-text topic-links topic-forum-link">'.$row['board_name'].'</a>')).'
								</th>
    							<td id="boardPaginate" align="right" class="pagination Board-links board-pagination board-pagination-top">
									'.$pagination.'
								</td>
  						</tr>
  					</table>';
	if($user['create_topics']) {
		$theme['body'].= '
					<div class="imagebutton board-tools">
						<a href="'.$settings['site_url'].'/index.php?act=newtopic&amp;board='.$_GET['id'].'" class="links links-text links-btn btn-new-topic">
							'.(str_replace(' ','&nbsp;',$VIEWBOARD_LANG["new_topic"])).'
						</a>
					</div>';
	}
	$theme['body'] .= ($user['moderator'] ? '
				<form method="post" action="" id="withselected" class="form mod-form board-form">' : '').'
					<table border="0" id="boardArea" class="board-area">
 						<tr id="boardHeadRow" class="titlebg board-head-row">
							<th id="boardColImage" class="forum-head board-head board-head-post-img" width="3%">
							</th>
							<th id="boardColSubject" width="45%" nowrap="nowrap" align="left" class="forum-head board-head board-head-post-subj">
								'.$VIEWBOARD_LANG["subject"].'
								</th>
							<th id="boardColReplys" width="10%" nowrap="nowrap" align="center" class="forum-head board-head board-head-post-replys">
								'.$VIEWBOARD_LANG["replies"].'
							</th>
							<th id="boardColViews" width="10%" nowrap="nowrap" align="center" class="forum-head board-head board-head-post-views">
								'.$VIEWBOARD_LANG["views"].
							'</th>
							<th id="boardColLastpost" width="20%" nowrap="nowrap" align="left" class="forum-head board-head board-head-post-last">
								'.$VIEWBOARD_LANG["last_post"].'
							</th>
							'.($user['moderator'] ? '
							<th id="boardColTick" width="3%" align="center">
								<input type="checkbox" id="select_all" />
							</th>' : '').'
  						</tr>';
	if($fetch != false) {
		foreach($fetch as $board) {
			//check if there is any replies
			$replies = ceil($board['replies'] / $settings['posts_topic']);
			$theme['body'] .= '
  						<tr class="'.($board['sticky'] == 1 ? 'sticky' : 'topic').'" id="topic_'.$board['topic_id'].'">
    						<td align="center">';
			if($board['locked']=='1')
				$theme['body'] .= '
								<img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/minilock.png" alt="'.$VIEWBOARD_LANG["t_locked_topic"].'" />';
			elseif($board['replies']>='15') {
				$theme['body'] .= '
								<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].'&amp;page='.$replies.'#'.$board['post_id'].'">
									<img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/hottopic2.png" title="'.$VIEWBOARD_LANG["t_hot_topic"].'" alt="'.$VIEWBOARD_LANG["t_hot_topic"].'" width="15" height="15" />
								</a>';
			} else {
				$theme['body'] .= '
								<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].'#'.$board['post_id'].'">
									<img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/lastpost.png" alt="'.$VIEWBOARD_LANG["t_last_post"].'" />
								</a>';
			}
			if($replies > 1) {
				$ctr = 1;
				$ctr2 = 1;
				$pages = '';
				while ($ctr2 <= $replies) {
					$pnum = ' <a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].'&amp;page='.$ctr.'">'.$ctr2.'</a> ';
					$pages .= $pnum;
					$ctr++;
					$ctr2++;
				}
			}
			$theme['body'] .= '
								</td>
								<td align="left" title="'.$board['message'].'"'.($user['moderator'] ? '  onclick="edit_title(\''.$board['topic_id'].'\')"' :'').'>
									<div id="topictitle_'.$board['topic_id'].'">
										<strong>
											<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].'">
												'.$board['topic_title'].'
											</a>
										</strong>'.($board['read_topic_id'] === false ? '
										<span class="new">NEW</span>' : '').($replies > 1 ? ' 
										<span class="small-text">
											'.$VIEWBOARD_LANG["pages"].': '.trim($pages).'
										</span>' : '').'
									</div>
									<div class="small-text">
										'.$board['topic_author'].'
									</div>';
			$theme['body'] .= '
									</td>
    								<td align="center">
										'.$board['replies'].'
									</td>
									<td align="center">
										'.$board['views'].'
									</td>
    								<td align="left">
										<span class="small-text">
											<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$board['topic_id'].( $replies> 1 ? '&amp;page='.$replies : '').'#'.$board['post_id'].'">
												'.call('dateformat', $board['post_time']).'
											</a> '.$VIEWBOARD_LANG["by"].' '.$board['author'].'
										</span>
									</td>'.($user['moderator'] ? '
									<td align="center">
									<input type="checkbox" name="topics[]" onclick="topicselect(\''.$board['topic_id'].'\');" value="'.$board['topic_id'].'" />
									</td>' : '').'
  								</tr>';
		}
	} else {
		$theme['body'] .= '
								<tr id="boardNoPosts" class="topic board-posts-none">
    								<td colspan="7">
										'.$VIEWBOARD_LANG["no_posts"].'
									</td>
  								</tr>';
	}
	$theme['body'] .= '
							</table>';
	if($user['create_topics']) {
		$theme['body'] .= '
							<table width="" cellspacing="0" cellpadding="0" id="toolsArea" class="board-tools-area">
  								<tr id="toolsRow" class="tools-row board-tools-row">
   									<td class="imagebutton board-tools" align="left">
										<a href="'.$settings['site_url'].'/index.php?act=newtopic&board='.$_GET['id'].'" class="links links-text links-btn btn-new-topic">
											'.(str_replace(' ','&nbsp;',$VIEWBOARD_LANG["new_topic"])).'
										</a>
									</td>';
		if($user['moderator']) { 
			$theme['body'].='
    								<td align="right" id="modtools-cell" class="modtools-cell board-modtools-cell">
										<select name="checked" id="checked" class="form-dd modtools-dd board-modtools">
											<option value="">
												'.$VIEWBOARD_LANG["o_with _selected"].'
											</option>'.($user['delete_topic'] == 3 ? '
											<option value="delete">
												'.$VIEWBOARD_LANG["o_delete"].'
											</option>' : '').($user['lock_topic'] == 3 ? '
											<option value="lock">
												'.$VIEWBOARD_LANG["o_lock"].'
											</option>
											<option value="unlock">
												'.$VIEWBOARD_LANG["o_unlock"].'
											</option>' : '').($user['sticky_any_topic'] == 3 ? '
											<option value="sticky">
												'.$VIEWBOARD_LANG["o_sticky"].'
											</option>
											<option value="unsticky">
												'.$VIEWBOARD_LANG["o_unsticky"].'
											</option>' : '').'
										</select>
									</td>';
		}
		$theme['body'].='
  								</tr>
							</table>';
	}
	$theme['body'] .= ($user['moderator'] ? '
						</form>' : '').'
						<table width="100%" cellspacing="0" cellpadding="0" id="jumpto-area" class="jumpto-area board-jumpto-area">
  							<tr id="JumptoRow" class="jumpto-row board-jumpto-row">
    							<td align="left" id="jumpto-cell" class="jumpto-cell board-jumpto-cell">
									Jump to: 
									<select onchange="jumpboard(this.options[this.selectedIndex].value);" class="form-dd jumpto-dropdown board-jumpto-dropdown">';
	$jump = call('indexforum');
	foreach($jump as $cat) {
		$theme['body'].='
										<optgroup label="'.$cat['cat_name'].'">';
		foreach($cat['boards'] as $to) {
			$theme['body'].='
											<option value="'.$to['board_id'].'" '.($to['board_id'] == $_GET['id'] ? 'selected="selected"' : '').'>
												'.$to['board_name'].'
											</option>';
		}
		$theme['body'].='
										</optgroup>';
	}
	$theme['body'].='
     								</select>
								</td>
    							<td align="right" class="pagination Board-links board-pagination board-pagination-bottom">
									'.$pagination.'
								</td>
  							</tr>
						</table>';
}
?>