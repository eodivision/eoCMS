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

if(!isset($_GET['authid']) || AUTHID != $_GET['authid']) {
	call('redirect', 'index.php');
}
switch ($_GET['opt']) {
	default:
		if(!isset($_GET['opt'])) {
			$error_die[] = $MODERATORCP_LANG["error_die"];
		}
		break;
	case 'deletepost':
		$check = call('delete_post', $_GET['id']);
		if($check==true) {
			call('redirect', 'index.php?act=viewtopic&id='.$_GET['topicid']);
		}
		break;
	case 'deletetopic':
		$check = call('delete_topic', $_GET['id'], $_GET['board_id']);
		if($check==true) {
			call('redirect', 'index.php?act=viewboard&id='.$_GET['board_id']);
		} else {
			$error_die[] = $MODERATORCP_LANG["error_die_deleted"];
		}
		break;
	case 'locktopic':
		$check = call('locktopic', $_GET['id']);
		if($check==true) {
			call('redirect', 'index.php?act=viewtopic&id='.$_GET['id']);
		}
		break;
	case 'unlocktopic':
		$check = call('unlocktopic', $_GET['id']);
		if($check==true) {
			call('redirect', 'index.php?act=viewtopic&id='.$_GET['id']);
		}
		break;
	case 'sticky':
		$check = call('stickytopic', $_GET['id']);
		if($check==true) {
			call('redirect', 'index.php?act=viewtopic&id='.$_GET['id']);
		}
		break;
	case 'unsticky':
		$check = call('unstickytopic', $_GET['id']);
		if($check==true) {
			call('redirect', 'index.php?act=viewtopic&id='.$_GET['id']);
		}
		break;
	case 'move':
		if($_POST) {
			$check = call('movetopic', $_GET['id'], $_POST['board'], $_POST['title'], $_POST['message'], $_POST['redirect'], $_POST['token']);
			if($check==true) {
				call('redirect', 'index.php?act=viewboard&id='.$_GET['board']);
			} else {
				$check = false;
			}
		}
		$title = $MODERATORCP_LANG["title"];
		$head = '<script type="text/javascript">
$(document).ready(function(){
	$("#redirect").click(function(){
		if ($("#redirect").is(":checked")){
			$("#redirect-topic").show("fast");
			$("#redirect-topic2").show("fast");
		} else {
			$("#redirect-topic").hide("fast");
			$("#redirect-topic2").hide("fast");
		}
      });
});
</script>';
		$fetch = call('sql_fetch_array',call('sql_query', "SELECT topic_title FROM forum_topics WHERE topic_id='" . $_GET['id'] . "'"));
		$body = theme('title', $MODERATORCP_LANG["theme_title"]) . theme('start_content') . '
<form action="" method="post" id="editpost"><table>
  <tr>
    <td>'.$MODERATORCP_LANG["move_to"].': </td>
    <td><select name="board">';
	$boards = call('indexforum');
	foreach($boards as $cat) {
		$body.='
	<optgroup label="'.$cat['cat_name'].'">';
		foreach($cat['boards'] as $to) {
			$body.='
	  <option value="'.$to['board_id'].'">'.$to['board_name'].'</option>';
		}
		$body.='
	</optgroup>';
	}
		$body.='
      </select></td>
  </tr>
  <tr>
    <td>'.$MODERATORCP_LANG["redirection"].'</td>
    <td><input type="checkbox" name="redirect" id="redirect" checked="checked" /></td>
  </tr>
  <tr id="redirect-topic">
    <td>'.$MODERATORCP_LANG["subject"].':</td>
    <td><input type="text" name="title" maxlength="80" value="';
		if(errors()) {
			$body .= $_POST['subject'];
		} else {
			$body .= $MODERATORCP_LANG["moved"].': ' .$fetch['topic_title'];
		}
		$body .='" /></td>
  </tr>
  <tr id="redirect-topic2">
    <td>'.$MODERATORCP_LANG["message"].':</td>
    <td><textarea rows="12" cols="40" name="message" id="message">';
		if(errors()) {
			$body .= $_POST['message'];
		} else {
			$body .= $MODERATORCP_LANG["topic_moved"]." [BOARD] \n\n [TOPIC LINK]";
		}
		$body .='</textarea></td>
  </tr>
  <tr>
    <td>' . call('form_token') . '</td>
  </tr>
</table>
<p align="center"><input type="submit" value="'.$MODERATORCP_LANG["btn_move_topic"].'" /></p>
</form>' . theme('end_content');
		break;
	case 'deletecomment':
		$check = call('deletecomment', $_GET['id']);
		if($check==true) {
			call('redirect', 'index.php?act=news&readmore='.$_GET['readmore']);
		}
		break;
	case 'quick':
		if($user['delete_any_topic']) {
			if(isset($_POST['opt']) && $_POST['opt']=='spam') {
				if(!isset($_POST['posts']))
					call('redirect', 'index.php?act=viewtopic&id='.$_GET['topic']);
				$sql = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '".$_GET['topic']."'");
				$members = array();
				$ip = array();
				while($p = call('sql_fetch_assoc', $sql)) {
					if(is_array($_POST['posts']) && in_array($p['id'], $_POST['posts'])) {
						$ip[] = $p['ip'];
						$members[] = array($p['author_id'] => $p['name_author']);
						$board_id = $p['board_id'];
					} elseif($p['id']==$_POST['posts']) {
						$ip[] = $p['ip'];
						$members[] = array($p['author_id'] => $p['name_author']);
						$board_id = $p['board_id'];
					}
				}
				$members = array_unique($members);
				$ip = array_unique($ip);
				$affected = count($members);
				$countposts = count($_POST['posts']);
				$ipcount = count($ip);
				if(isset($_POST['submit'])) {
					if($_POST['banuseraction']=='1') {
						foreach($ip as $ips) {
							$check = call('addban', $ips, $_POST['reason'], 'ip');
						}
						foreach($members as $id => $name) {
							if(isset($_POST['deleteothers']) && $_POST['deleteothers']=='1') {
								$sql =  call('sql_query', "SELECT * FROM forum_posts WHERE author_id = '$id';");
								while($p = call('sql_fetch_array', $sql)) {
									$check = call('delete_post', $p['id']);
								}
							}
							if($_POST['banuseraction']=='1') {
								$query = call('sql_query', "UPDATE users SET allow_login = '0' WHERE id = '$id';");
							}
						}
					}
					$posts = explode('-', $_POST['posts']);
					if(is_array($posts)) {
						foreach($posts as $id) {
							$check = call('delete_post', $id);
						}
					} else {
						$check = call('delete_post', $_POST['posts']);
					}
					call('redirect', 'index.php?act=viewboard&id='.$_POST['board']);
				} else {
					$title = $MODERATORCP_LANG["title_spam"];
					$body = theme('title', $MODERATORCP_LANG["theme_title_spam"]).theme('start_content').'
	'.$MODERATORCP_LANG["deleting"].' '.count($_POST['posts']).' '.$MODERATORCP_LANG["posts"].' <br />
<form action="" method="post">
<input type="checkbox" name="deleteothers" id="deleteothers" value="1" />
<label for="deleteothers">'.$MODERATORCP_LANG["delete_other"].'</label><br />
'.$MODERATORCP_LANG["like_do"].' '.$affected.' '.($affected > 1 ? $MODERATORCP_LANG["affected_users"] : $MODERATORCP_LANG["affected_user"]).'?<br />
<input type="radio" name="banuseraction" id="banuseraction_none" checked="checked" value="0" />
<label for="banuseraction_none">'.$MODERATORCP_LANG["nothing"].'</label>
<br />
<input type="radio" name="banuseraction" id="banuseraction_ban" value="1" /> '.$MODERATORCP_LANG["reason"].': <input type="text" name="reason" />
<label for="banuseraction_ban">'.$MODERATORCP_LANG["ban_users"].'</label>
<table width="100%">
<tr>
  <td>'.$MODERATORCP_LANG["spam_by"].':';
					foreach($members[0] as $id => $name) {
						$body.='<br />
	'.call('userprofilelink', $id);
					}
					$body .= '</td>
  <td>IP addresses used:';
					foreach($ip as $ips) {
						$body.='<br />
	<a href="'.$settings['site_url'].'/index.php?act=trackip&amp;ip=' . $ips . '" target="_blank" title="'.$MODERATORCP_LANG["track_ip"].' ' . $ips . '">' . $ips . '</a>';
					}
					$body.='</td>
  </tr>
</table>
<input type="hidden" name="opt" value="spam" /><input type="hidden" name="posts" value="';
					$i = 1;
					if(is_array($_POST['posts'])) {
						foreach($_POST['posts'] as $post) {
							if($i<$countposts) {
								$body .= $post . '-';
								$i++;
							} else {
								$body .=$post;
							}
						}
					} else {
						$body .= $_POST['posts'];
					}
					$body.='" /><input type="hidden" name="board" value="' . $board_id . '" /><input type="submit" value="'.$MODERATORCP_LANG["btn_delete_spam"].'" name="submit" />
</form>';
					$body.=theme('end_content');
				}
			}
		} else {
			$error_die[] = $MODERATORCP_LANG["error_die"];
		}
		break;
}
?>