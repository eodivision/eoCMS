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
# overwrite user permissions with these board permissions
call('getforumpermission', $_GET['id']);
$fetch = call('viewboard', $_GET['id']);
if (!$user['guest']) {
	if ($user['topics_page']!='0' && $user['topics_page']!='') {
		$settings['topics_page'] = $user['topics_page'];
	}
	if ($user['posts_topic']!='0' && $user['posts_topic']!='') {
		$settings['posts_topic'] = $user['posts_topic'];
	}
}
if(!$user['guest']) {
# check if the user is a registered moderator
$mod = call('sql_num_rows', call('sql_query', "SELECT * FROM forum_moderators WHERE user_id = '" . $user['id'] . "' AND board_id='" . $_GET['id'] . "'"));
	if($mod != 0) {
# looks like they are for this board so give them moderator permissions!
		$user['sticky_any_topic'] =true; 
		$user['sticky_own_topic'] =true; 
		$user['move_any_topic']   =true; 
		$user['move_own_topic']   =true; 
		$user['lock_any_topic']   =true; 
		$user['lock_own_topic']   =true; 
		$user['delete_any_topic'] =true;
		$user['delete_own_topic'] =true;
		$user['delete_any_posts'] =true; 
		$user['delete_own_posts'] =true;
		$user['modify_any_posts'] =true;
		$user['modify_own_posts'] =true;
		$moderator = true;
	}elseif(isset($user['multi-moderate']) && $user['multi-moderate']) {
		$moderator = true;
	} else {
# if not set the variable to false
		$moderator = false;
	}
} else {
	$moderator = false;	
}
# check to see if anything was posted with the multi-topic moderation
if($moderator==true && isset($_POST['topics'])) {
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
	call('redirect', $settings['site_url'].'/index.php?act=viewboard&id='.$_GET['id']);
}
$bcrumb = call('sql_query', "SELECT id, board_name, post_group, visible FROM forum_boards WHERE id='" . $_GET['id'] . "'");
if(call('sql_num_rows', $bcrumb) == 0)
	$error_die[] = $VIEWBOARD_LANG["error_die"];
$row = call('sql_fetch_array',$bcrumb);
if(!call('visiblecheck', $user['membergroup_id'], $row['visible']))
	$error_die[] = $VIEWBOARD_LANG["error_die"];
if ($bcrumb) {
	$head = call('seokeyword', $row['board_name']) . call('seodescription', $row['board_name']) . '<link rel="alternate" type="application/rss+xml" title="Latest Posts" href="' . $settings['site_url'] . '/index.php?act=feeds&amp;type=board&amp;board=' . $_GET['id'] . '&amp;export=rss" />' . ($moderator==true?'<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.jeditable.pack.js"></script>
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
	$("#topictitle_"+id).editable("'.$settings['site_url'].'/index.php?act=ajax&m=edittopictitle&board_id='.$_GET['id'].'", { 
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
</script>
' : '');
	$head .='<script type="text/javascript">
<!--
function jumpboard(boardid) {
	document.location.href=\''.$settings['site_url'].'/index.php?act=viewboard&id=\'+boardid;
}
// -->
</script>
';
	if (!$user['guest'] && isset($_GET['id'])) {
# Check to see if the board is read
		$sql = call('sql_query', "SELECT * FROM board_read WHERE user_id = '" . $user['id'] . "' AND board_id = '" . $_GET['id'] . "'");
		if (call('sql_num_rows', $sql) == 0)
			$insert = call('sql_query', "INSERT INTO board_read (user_id, board_id) VALUES ('" . $user['id'] . "', '" . $_GET['id'] . "')");
	}
}
$title = $row['board_name'];
if(!isset($_GET['page'])) {
	$_GET['page'] = 1;
}
$pagination = call('pagination', $_GET['page'], $settings['topics_page'], 'SELECT COUNT(topic_id) AS numrows FROM forum_topics WHERE board_id = ' . $_GET['id'] . ' AND sticky = "0"', '?act=viewboard&amp;id=' . $_GET['id'] . '&amp;page=', 3);
$body = '
<table id="boardName" border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr class="subtitlebg">
    <th id="boardLinks" nowrap="nowrap" scope="col" align="left">'.theme('breadcrumb', array('<a href="' . $settings['site_url'] . '/index.php?act=forum" class="link link-text topic-links topic-forum-link">'.$VIEWBOARD_LANG["forum_home"].'</a>', '<a href="' . $settings['site_url'] . '/index.php?act=viewboard&amp;id=' . $row['id'] . '" class="link link-text topic-links topic-forum-link">' . $row['board_name'] . '</a>')).'</th>
    <td id="boardPaginate" align="right" class="pagination Board-links board-pagination board-pagination-top">' . $pagination . '</td>
  </tr>
  </table>';
if (call('visiblecheck', $user['membergroup_id'], $row['post_group'])) {
	$body.='
    <div class="imagebutton board-tools"><a href="'.$settings['site_url'].'/index.php?act=newtopic&amp;board=' . $_GET['id'] . '" class="links links-text links-btn btn-new-topic">'.(str_replace(' ','&nbsp;',$VIEWBOARD_LANG["new_topic"])).'</a></div>';
}
$body.= ($moderator==true ? '<form method="post" action="" id="withselected" class="form mod-form board-form">' : '').'
<table border="0" id="boardArea" class="board-area">
  <tr id="boardHeadRow" class="titlebg board-head-row">
    <th id="boardColImage" class="forum-head board-head board-head-post-img" width="3%"></th>
    <th id="boardColSubject" width="45%" nowrap="nowrap" align="left" class="forum-head board-head board-head-post-subj">'.$VIEWBOARD_LANG["subject"].'</th>
    <th id="boardColReplys" width="10%" nowrap="nowrap" align="center" class="forum-head board-head board-head-post-replys">'.$VIEWBOARD_LANG["replies"].'</th>
    <th id="boardColViews" width="10%" nowrap="nowrap" align="center" class="forum-head board-head board-head-post-views">'.$VIEWBOARD_LANG["views"].'</th>
    <th id="boardColLastpost" width="20%" nowrap="nowrap" align="left" class="forum-head board-head board-head-post-last">'.$VIEWBOARD_LANG["last_post"].'</th>
	'.($moderator==true ? '<th id="boardColTick" width="3%" align="center"><input type="checkbox" id="select_all" /></th>' : '').'
  </tr>';
if ($fetch!=false) {
	foreach($fetch as $r) {
		//check if there is any replies
		if($r['replies'] != 0)
			$replies = $r['replies'] + 1; //incrament by one because the first post in the topic is not included with this number and so messes up pagination
		else
			$replies = $r['replies'];
		$replies = ceil($replies / $settings['posts_topic']);
		$body .= '
  <tr class="'.($r['sticky'] == 1 ? 'sticky' : 'topic').'" id="topic_' . $r['topic_id'] . '">
    <td align="center">';
	if ($r['locked']=='1')
			$body .= ' <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/minilock.png" alt="'.$VIEWBOARD_LANG["t_locked_topic"].'" />';
	elseif ($r['replies']>='15') {
			$body .= '<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $r['topic_id'] . '&amp;page='.$replies.'#' . $r['post_id'] . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/hottopic2.png" title="'.$VIEWBOARD_LANG["t_hot_topic"].'" alt="'.$VIEWBOARD_LANG["t_hot_topic"].'" width="15" height="15"/></a>';
		} else {
			$body .= '<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $r['topic_id'] . '#' . $r['post_id'] . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/lastpost.png" alt="'.$VIEWBOARD_LANG["t_last_post"].'" /></a>';
		}
		if ($replies>1) {
			$ctr = 1;
			$ctr2 = 1;
			$pages = "";
			while ($ctr2 <= $replies) {
				$pnum = ' <a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $r['topic_id'] . '&amp;page=' . $ctr . '">' . $ctr2 . '</a> ';
				$pages .= $pnum;
				$ctr++;
				$ctr2++;
			}
		}
		$body .= '</td>
    <td align="left" title="'.$r['message'].'"'.($moderator==true ? '  onclick="edit_title(\''.$r['topic_id'].'\')"' :'').'><div id="topictitle_'.$r['topic_id'].'"><strong><a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id=' . $r['topic_id'] . '">' . $r['topic_title'] . '</a></strong>'.($r['read_topic_id'] == null ? ' <span class="new">NEW</span>' : '').($replies>1 ? ' <span class="small-text">'.$VIEWBOARD_LANG["pages"].': ' . trim($pages) . '</span>' : '').'</div>
	<div class="small-text">' . (!empty($r['topic_author']) ? call('userprofilelink', $r['topic_author']) : $VIEWBOARD_LANG["guest"]) . '</div>';
		$body .= '</td>
    <td align="center">' . $r['replies'] . '</td>
    <td align="center">'.$r['views'].'</td>
    <td align="left"><span class="small-text"><a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$r['topic_id'].($replies>1 ? '&amp;page='.$replies : '').'#'.$r['post_id'].'">'.call('dateformat', $r['post_time']).'</a> '.$VIEWBOARD_LANG["by"].' '.$r['author'].'</span></td>
	'.($moderator==true ? '
    <td align="center"><input type="checkbox" name="topics[]" onclick="topicselect(\''.$r['topic_id'].'\');" value="' . $r['topic_id'] . '" /></td>' : '').'
  </tr>';
	}
} elseif ($fetch==false) {
	$body .= '
  <tr id="boardNoPosts" class="topic board-posts-none">
    <td colspan="7">'.$VIEWBOARD_LANG["no_posts"].'</td>
  </tr>';
}
$body .= '
</table>';
if (call('visiblecheck',$user['membergroup_id'],  $row['post_group'])) {
	$body .= '
<table width="100%" cellspacing="0" cellpadding="0" id="toolsArea" class="board-tools-area">
  <tr id="toolsRow" class="tools-row board-tools-row">
    <td class="imagebutton board-tools" align="left"><a href="'.$settings['site_url'].'/index.php?act=newtopic&board=' . $_GET['id'] . '" class="links links-text links-btn btn-new-topic">'.(str_replace(' ','&nbsp;',$VIEWBOARD_LANG["new_topic"])).'</a></td>';
	if($moderator==true) { $body.='
    <td align="right" id="modtools-cell" class="modtools-cell board-modtools-cell"><select name="checked" id="checked" class="form-dd modtools-dd board-modtools">
	<option value="">'.$VIEWBOARD_LANG["o_with _selected"].'</option>';
	if($user['delete_any_topic']) { $body.='<option value="delete">'.$VIEWBOARD_LANG["o_delete"].'</option>'; }
	if($user['lock_any_topic']) { $body.='<option value="lock">'.$VIEWBOARD_LANG["o_lock"].'</option>'; }
	if($user['lock_any_topic']) { $body.='<option value="unlock">'.$VIEWBOARD_LANG["o_unlock"].'</option>'; }
	if($user['sticky_any_topic']) { $body.='<option value="sticky">'.$VIEWBOARD_LANG["o_sticky"].'</option>'; }
	if($user['sticky_any_topic']) { $body.='<option value="unsticky">'.$VIEWBOARD_LANG["o_unsticky"].'</option>'; }
      $body.='</select></td>';
	  }
  $body.='</tr>
</table>';
}
$body .= ''.($moderator==true ? '</form>' : '').'
<table width="100%" cellspacing="0" cellpadding="0" id="jumpto-area" class="jumpto-area board-jumpto-area" id="topicJumpto">
  <tr id="JumptoRow" class="jumpto-row board-jumpto-row">
    <td align="left" id="jumpto-cell" class="jumpto-cell board-jumpto-cell">Jump to: <select onchange="jumpboard(this.options[this.selectedIndex].value);" class="form-dd jumpto-dropdown board-jumpto-dropdown">';
$jump = call('indexforum');
foreach($jump as $cat) {
	$body.='
	<optgroup label="'.$cat['cat_name'].'">';
	foreach($cat['boards'] as $to) {
		$body.='
		<option value="'.$to['board_id'].'" '. ($to['board_id']==$_GET['id'] ? 'selected="selected"' : '').'>'.$to['board_name'].'</option>';
	}
	$body.='
	</optgroup>';
}
$body.='
      </select></td>
    <td align="right" class="pagination Board-links board-pagination board-pagination-bottom">' . $pagination . '</td>
  </tr>
</table>';
?>