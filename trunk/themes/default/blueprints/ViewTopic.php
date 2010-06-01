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

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$bcrumb = call('sql_query', "SELECT r.board_id AS id, h.board_name as name, r.topic_title AS topic, r.topic_id AS tid, h.visible AS visible, r.topic_ip AS topic_ip, h.post_group AS post_group, r.locked AS locked FROM forum_boards AS h LEFT JOIN forum_topics AS r ON r.board_id=h.id WHERE r.topic_id='" . $_GET['id'] . "'");
$row = call('sql_fetch_array', $bcrumb);
# overwrite user permissions with these board permissions
call('getforumpermission', $row['id']);
/*if(!call('visiblecheck', $user['membergroup_id'], $row['visible'])) {
	$error_die[] = $VIEWTOPIC_LANG["error_die"];
}*/
$fetch = call('viewtopic', $_GET['id']);
if($fetch!=false) {
	$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/quickedit.js"></script>
<script type="text/javascript">
<!--
function jumpboard(boardid) {
	document.location.href=\''.$settings['site_url'].'/index.php?act=viewboard&id=\'+boardid;
}';
if(isset($user['multi-moderate']) && $user['multi-moderate']) {
$theme['head'].='
	function postselect(id) {
	var postcount = $("input[name=\'posts[]\']:checked").length;
	$("#postid_"+id).toggleClass("inline");
	$("#go").val("'.$VIEWTOPIC_LANG["btn_delete_go"].' ("+postcount+")");
	if(postcount != 0)
	$("#go").removeAttr("disabled");
	else
	$("#go").attr("disabled", true);
}
$(document).ready(function(){
	if($("input[name=\'posts[]\']:checked").length == 0)
	$("#go").attr("disabled", true);
});';
} $theme['head'] .='
// -->
</script>';
	if(!$user['guest'] && isset($_GET['id'])) {
//Check to see if the topic is read
		$sql = call('sql_query', "SELECT * FROM topic_read WHERE user_id = '" . $user['id'] . "' AND topic_id = '" . $_GET['id'] . "'");
		if(call('sql_num_rows', $sql) == 0) {
			$insert = call('sql_query', "INSERT INTO topic_read (user_id, topic_id) VALUES ('" . $user['id'] . "', '" . $_GET['id'] . "')");
		}
	}
	$pagination = call('pagination', isset($_GET['page']), $settings['posts_topic'], 'SELECT COUNT(id) AS numrows FROM forum_posts WHERE topic_id = ' . $_GET['id'], '?act=viewtopic&amp;id=' . $_GET['id'] . '&amp;page=', 3);
	$memberssql = call('sql_query', "SELECT * FROM membergroups", 'cache');
	$theme['head'] .= @call('seokeyword', $row['topic']);
	$theme['body'] = '
<table id="boardName" border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr class="subtitlebg">
    <th id="boardLinks" nowrap="nowrap" scope="col" align="left">'.theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=forum" class="link link-text topic-links topic-forum-link">'.$VIEWTOPIC_LANG["forum_home"].'</a>', '<a href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id='.$row['id'].'" class="link link-text topic-links topic-forum-link">'.$row['name'].'</a>', '<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$row['tid'].'" class="link link-text topic-links topic-forum-link">'.$row['topic'].'</a>')).'</th>
    <td id="boardPaginate" align="right" class="pagination Board-links board-pagination board-pagination-top">' . $pagination . '</td>
  </tr>
  </table>'
  .theme('title', '<a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$row['tid'].'" class="link link-text topic-links topic-forum-link">'.$row['topic'].'</a>').theme('start_content');
	if(isset($user['multi-moderate']) && $user['multi-moderate']) { 
		$theme['body'].='<form method="post" action="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=quick&amp;topic='.$_GET['id'].'&amp;'.$authid.'">'; 
	}
	$i = 0;
	foreach($fetch as $r) {
		$i++;
		$theme['title'] = $r['topic_title'];
		foreach($memberssql as $members) {
			if($members['membergroup_id'] == $r['membergroup']) {
				$member['name'] = $members['name'];
				$member['image'] = $members['image'];
			}
		}
               $theme['body'] .= '<table class="posts-area'.(($i % 2 == 0) ? '1': '2').'">
  <tr id="postid_' . $r['post_id'] . '" class="posts-row" valign="top">
    <td id="postColAuthor' . $r['post_id'] . '" valign="top" class="post-id-area"><a name="' . $r['post_id'] . '" class="link link-text post-id-link"></a>
	  <div class="post-author-name" id="author-'.$r['author_id'].'-post-'.$r['post_id'].'">';
		if (!empty($r['author_id']) && call('userprofilelink', $r['author_id']) == false) {
			$theme['body'] .= $r['name_author'];
		} elseif(!empty($r['author_id'])) {
			$theme['body'] .= call('userprofilelink', $r['author_id']);
		} else {
			$theme['body'] .= $VIEWTOPIC_LANG["guest"];
		}
		$theme['body'] .= '</div>
	  <div class="small-text post-membergroup-name">' .($r['author_id'] != '0' ? $member['name'] : '').'</div>
	  <div class="small-text post-membergroup-image">'.($r['author_id'] != '0' ? '<img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/' . $member['image'] . '" alt="'.$member['name'].'" class="post-member-img" />':'').'</div>
	  <div class="post-online-area">' . ($r['author_id'] != '0' ? call('search_online', $r['author_id']) : '') . '</div>
	  <div class="post-avatar-area">';
		if(!empty($r['avatar'])){
			$theme['body'] .= '<img src="' . $r['avatar'] . '" alt="avatar" class="avatar post-avatar-img" />';
		}
		$theme['body'] .= '<div class="post-posts-area">'.($r['author_id'] != '0' ? 'Posts: ' . $r['posts'] : '').'</div>
	  <div class="post-contact-area">';
		if(!empty($r['icq'])) { 
			$theme['body'].='<a href="http://www.icq.com/whitepages/about_me.php?uin=' . $r['icq'] . '" target="_blank" class="link link-img post-contact-icq"><img src="http://status.icq.com/online.gif?img=5&amp;icq=' . $r['icq'] . '" alt="' . $r['icq'] . '" width="18" height="18" border="0" class="post-contact-img post-contact-icq-img" /></a>'; 
		}
		if(!empty($r['aim'])) { 
			$theme['body'].='<a href="aim:goim?screenname=' . urlencode(strtr($r['aim'], array(' ' => '%20'))) . '&amp;message=Hello" target="_blank" class="link link-img post-contact-aim"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_aim.gif" border="0" class="post-contact-img post-contact-aim-img" /></a>'; 
		}
		if(!empty($r['yim'])) { 
			$theme['body'].='<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . urlencode($r['yim']) . '" target="_blank" class="link link-img post-contact-yim"><img src="http://opi.yahoo.com/online?u=' . urlencode($r['yim']) . '&amp;m=g&amp;t=0" alt="' . $r['yim'] . '" border="0" class="post-contact-img post-contact-yim-img" /></a>'; 
		} if(!empty($r['msn'])) {
			$theme['body'].='<a href="http://members.msn.com/' . $r['msn'] . '" target="_blank" class="link link-img post-contact-msn"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_msn.gif" alt="' . $r['msn'] . '" border="0" class="post-contact-img post-contact-msn-img" /></a>';
		} 
		$theme['body'] .='</div>
      </td>
    <td id="postColMessage' . $r['post_id'] . '" width="100%" class="post-area-row"><div id="subject_' . $r['post_id'] . '" class="post-subject"><a href="'.$settings['site_url'].'/index.php?act=viewtopic&amp;id='.$_GET['id']. (isset($_GET['page']) && $_GET['page'] > '1' ? '&amp;page='.$_GET['page'] : '') .'#'.$r['post_id'].'" class="link link-text post-subject-link">' . $r['subject'] . '</a><br /><span class="small-text post-posted-on">'.$VIEWTOPIC_LANG["posted_on"].': ' . call('dateformat', $r['post_time']) . '</span></div><div class="post-edit-images">';
		if(((!$user['guest'] && $r['author_id'] == $user['id'] && $user['delete_own_posts']) || ($user['guest'] && $r['ip']== call('visitor_ip') && $r['author_id']=='0')) || (isset($user['delete_any_posts']) && $user['delete_any_posts']) && $row['locked']!='1') {
			$theme['body'] .= '<a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=deletepost&amp;id=' . $r['post_id'] . '&amp;topicid=' . $_GET['id'] . '&amp;'.$authid.'" onclick="return confirm(\''.$VIEWTOPIC_LANG["js_delete_post"].'\')"  title="'.$VIEWTOPIC_LANG["t_delete_post"].'" class="link link-img post-delete-link"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/deletepost.png" alt="'.$VIEWTOPIC_LANG["t_delete_post"].'" class="post-button-img post-button-delete-img" /></a>';
		}
		if(((isset($user['id']) && $r['author_id'] == $user['id'] && $user['modify_own_posts']) || ($user['guest'] && $r['ip']== call('visitor_ip') && $r['author_id']=='0')) || ($user['modify_any_posts']) && $row['locked']!='1') {
			$theme['body'].='<a href="'.$settings['site_url'].'/index.php?act=editpost&amp;id=' . $r['post_id'] . '&amp;'.$authid.'" title="'.$VIEWTOPIC_LANG["t_edit_post"].'" class="link link-img post-edit-link"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/editpost.png" alt="'.$VIEWTOPIC_LANG["t_edit_post"].'" class="post-button-img post-button-edit-img" /></a>';
		}
		if(call('visiblecheck', $user['membergroup_id'], $row['post_group']) && $row['locked']!='1') {
			$theme['body'].='<a href="'.$settings['site_url'].'/index.php?act=reply&amp;quote=' . $r['post_id'] . '&amp;topic=' . $_GET['id'] . '" title="'.$VIEWTOPIC_LANG["t_quote_post"].'" class="link link-img post-quote-link"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/quotepost.png" alt="'.$VIEWTOPIC_LANG["t_quote_post"].'" class="post-button-img post-button-quote-img" /></a>'; 
		}
		$theme['body'].='</div>
		<div style="clear: both;" />
		<hr class="seperator post-seperator post-seperator-upper" />
		<div id="viewmessage_' . $r['post_id'] . '" style="overflow:auto;" class="post-message-div">' . call('bbcode', $r['message'], $r['disable_smiley']) . '</div>';
		if(!empty($r['modified_time'])) {
			$theme['body'] .='<div class="small-text post-last-edit">Last Edit: '.call('dateformat', $r['modified_time']).' by '.$r['modified_author'].'</div>';
		}
		if(!empty($r['signature'])) {
			$theme['body'] .='<hr class="seperator post-seperator post-seperator-lower" /><div style="overflow: auto;" class="signature-div post-signature-div">'.call('bbcode', $r['signature']).'</div>';
		}
		$theme['body'] .= '</td>
	</tr>
	<tr>
	<td align="left">'.(isset($user['multi-moderate']) && $user['multi-moderate'] ? '<input type="checkbox" value="'.$r['post_id'].'" name="posts[]" onclick="postselect(\''.$r['post_id'].'\');" />' : '').'</td>
	<td align="right" class="post-quick">';
	if(((isset($user['id']) && $r['author_id'] == $user['id'] && $user['modify_own_posts']) || ($user['guest'] && $r['ip']== call('visitor_ip') && $r['author_id']=='0')) || ($user['modify_any_posts']) && $row['locked']!='1') {
		$theme['body'].='<a href="#'.$r['post_id'].'" onclick="QuickEdit(\'' . $r['post_id'] . '\')" class="link link-img quickedit-link" title="'.$VIEWTOPIC_LANG["t_quick_edit"].'"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/quickedit.png" alt="'.$VIEWTOPIC_LANG["t_quick_edit"].'" class="quickedit-img post-quickedit-img" style="float: right; cursor:pointer;" /></a>'; 
		}
	if(isset($user['track_ip']) && $user['track_ip']) {
		$theme['body'].= '<div style="float: right; padding-top: 7px;" class="trackip-div post-trackip-div"><a href="'.$settings['site_url'].'/index.php?act=trackip&amp;ip='.$r['ip'].'" title="'.$VIEWTOPIC_LANG["track_ip"].' '.$r['ip'].'" class="link link-text trackip-div post-trackip">'.$r['ip'].'</a></div>';
	}
	$theme['body'].='</td>
	</tr>
  </table>';
	}
	$theme['body'] .=call('modoptions', $_GET['id'], $row['id'], $row['topic_ip']) . '
</form><table width="100%" cellspacing="0" cellpadding="0" id="jumpto-area" class="jumpto-area topic-jumpto-area">
  <tr id="JumptoRow" class="jumpto-row topic-jumpto-row">
    <td align="left" id="jumpto-cell" class="jumpto-cell topic-jumpto-cell">Jump to: <select id="topicJumpto" onchange="jumpboard(this.options[this.selectedIndex].value);" class="form-dd jumpto-dropdown topic-jumpto-dropdown">';
	$jump = call('indexforum');
	foreach($jump as $cat) {
		$theme['body'].='
	<optgroup label="'.$cat['cat_name'].'">';
		foreach($cat['boards'] as $to) {
			$theme['body'].='
	  <option value="'.$to['board_id'].'" '. ($to['board_id'] == $_GET['id'] ? 'selected="selected"' : '').'>'.$to['board_name'].'</option>';
		}
		$theme['body'].='
	</optgroup>';
	}
	$theme['body'].='
      </select></td>
    <td align="right" class="pagination topic-links topic-pagination topic-pagination-bottom">' . $pagination . '</td>
  </tr>
</table>';
	if(!$user['guest'] && $user['quickreply'] && $row['locked']!='1') {
		$theme['body'] .='
<form action="'.$settings['site_url'].'/index.php?act=reply&topic=' . $_GET['id'] . '" method="POST" class="form form-quickreply">
<p align="center" class="quickreply-msg-area topic-quickreply-msg-area">'.$VIEWTOPIC_LANG["quick_reply"].'<br />
<textarea rows="5" cols="40" name="message" id="message" class="form-txt form-txtarea form-quickreply-txtarea topic-quickreply-txtarea"></textarea>
</p>' . call('form_token') . '
<p align="center" class="quickreply-submit-area topic-quickreply-submit-area"><input type="submit" value="'.$VIEWTOPIC_LANG["btn_post_reply"].'" class="form-btn form-quickreply-btn topic-quickreply-btn"/></p>';
		if(isset($user['multi-moderate']) && $user['multi-moderate'])
			$theme['body'].='</form>';
	}
} else {
	$error_die[] = $VIEWTOPIC_LANG["error_die_none"];
}
$theme['body'] .= theme('end_content');
?>