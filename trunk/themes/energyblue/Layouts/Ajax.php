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
if(!(defined("IN_ECMS")) || !isset($_REQUEST['m'])) { die("Hacking Attempt..."); }
switch ($_REQUEST['m']) {
	case 'quote';
		$postid = $_POST['postid'];
		if($_POST['type']=='post') {
			$sql = call('sql_query', "SELECT * FROM forum_posts WHERE id = '$postid'");
			$fetch = call('sql_fetch_array', $sql);
			$sql = call('sql_query', "SELECT replies FROM forum_topics WHERE topic_id = '".$fetch['topic_id']."'");
			$r = call('sql_fetch_array', $sql);
			$replies = ceil($r['replies'] / $settings['topics_page']);
			$quotereturn = stripslashes($_POST['formstuff']) . '[quote name=' . $fetch['name_author'] . ' link=id=' . $fetch['topic_id'] . '&page=' . $replies . '#' . $fetch['id'] . ' date=' . $fetch['post_time'] . ']' . html_entity_decode(htmlspecialchars_decode($fetch['message'], ENT_QUOTES), ENT_QUOTES) . '[/quote]';
	  	} elseif($_POST['type']=='pm') {
			$sql = call('sql_query', "SELECT * FROM pm WHERE id = '$postid' AND to_send = '".$user['id']."'");
			if(call('sql_num_rows', $sql)!=0) {
				$fetch = call('sql_fetch_array',$sql);
				$userfetch = call('sql_fetch_array', call('sql_query', "SELECT user FROM users WHERE id = '".$fetch['sender']."'"));
				$quotereturn = stripslashes($_POST['formstuff']) . '[quote name=' . $userfetch['user'] . ' link=pm date=' . $fetch['time_sent'] . ']' . html_entity_decode(htmlspecialchars_decode($fetch['message'], ENT_QUOTES), ENT_QUOTES) . '[/quote]';
			} else
				$quotereturn = '';
		}
      echo $quotereturn;
	break;
	case 'previewpost';
		  $ajaxinfostuff = $_POST['data'];
		  $searchstr = array(chr(145), chr(146), chr(147), chr(148), chr(151));
		  $replacestr = array('&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;', '&mdash;');
		  $ajaxinfostuff = str_replace($searchstr, $replacestr, $ajaxinfostuff);
		  $ajaxinfostuff = stripslashes($ajaxinfostuff);
		  echo '<link rel="stylesheet" href="' . $settings['site_url'] . '/themes/' . $settings['site_theme'] . '/style.css" type="text/css" /><div class="markItUpPreviewFrame">' . call('bbcode', $ajaxinfostuff) . '</div>';
	break;
	case 'showmessage';
		$draft = ',draft';
		if(strstr($_POST['id'], $draft) == $draft){
			$_POST['id'] = str_replace($draft, '', $_POST['id']);
			$draft = true;
		} else
			$draft = false;
		$pm = call('getpm', $_POST['id']);
		if ($pm !=false) {
			echo theme('title', $AJAX_LANG["viewing_message"].': ' . $pm['title']).theme('start_content').'<table class="posts-area1" width="100%">';
          $members = call('sql_fetch_array',call('sql_query', "SELECT name, image FROM membergroups WHERE membergroup_id = '" . $pm['membergroup'] . "'"));
		   echo'<tr id="postid_' . $pm['id'] . '" class="posts-row" valign="top">
    <td id="postColAuthor' . $pm['id'] . '" valign="top" class="post-id-area"><a name="' . $pm['id'] . '" class="link link-text post-id-link"></a>
	  <div class="post-author-name" id="author-'.$pm['sender'].'-post-'.$pm['id'].'">
	  	'.call('userprofilelink', $pm['sender']).'
	  </div>
	  <div class="small-text post-membergroup-name">'.$members['name'].'</div>
	  <div class="small-text post-membergroup-image"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/' . $members['image'] . '" alt="'.$members['name'].'" class="post-member-img" /></div>
	  <div class="post-online-area">'.call('search_online', $pm['sender']).'</div>
	  <div class="post-avatar-area">';
		if(!empty($pm['avatar'])){
			echo '<img src="' . $pm['avatar'] . '" alt="avatar" class="avatar post-avatar-img" />';
		}
		echo '<div class="post-posts-area">Posts: '.$pm['posts'].'</div>
	  <div class="post-contact-area">';
		if(!empty($pm['icq'])) { 
			echo '<a href="http://www.icq.com/whitepages/about_me.php?uin=' . $pm['icq'] . '" target="_blank" class="link link-img post-contact-icq"><img src="http://status.icq.com/online.gif?img=5&amp;icq=' . $pm['icq'] . '" alt="' . $pm['icq'] . '" width="18" height="18" border="0" class="post-contact-img post-contact-icq-img" /></a>'; 
		}
		if(!empty($pm['aim'])) { 
			echo '<a href="aim:goim?screenname=' . urlencode(strtr($pm['aim'], array(' ' => '%20'))) . '&amp;message=Hello" target="_blank" class="link link-img post-contact-aim"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_aim.gif" border="0" class="post-contact-img post-contact-aim-img" /></a>'; 
		}
		if(!empty($pm['yim'])) { 
			echo '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . urlencode($pm['yim']) . '" target="_blank" class="link link-img post-contact-yim"><img src="http://opi.yahoo.com/online?u=' . urlencode($pm['yim']) . '&amp;m=g&amp;t=0" alt="' . $pm['yim'] . '" border="0" class="post-contact-img post-contact-yim-img" /></a>'; 
		} if(!empty($pm['msn'])) {
			echo '<a href="http://members.msn.com/' . $pm['msn'] . '" target="_blank" class="link link-img post-contact-msn"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_msn.gif" alt="' . $pm['msn'] . '" border="0" class="post-contact-img post-contact-msn-img" /></a>';
		} 
		echo '</div>
      </td>
    <td id="postColMessage' . $pm['id'] . '" width="100%" class="post-area-row"><a href="'.$settings['site_url'].'/index.php?act=sendpm' . ($draft ? '&id=' . $pm['id'] . '&opt=edit' : '&userid=' . $pm['sender'] . '&pm=' . $pm['id']) . '">'.($draft ? $AJAX_LANG['edit'] : $AJAX_LANG["reply"]).'</a><div id="subject_' . $pm['id'] . '" class="post-subject"><span class="small-text post-posted-on">'.($draft ? $AJAX_LANG["saved_on"] : $AJAX_LANG["posted_on"]).': ' . call('dateformat', $pm['time_sent']) . '</span>
	</div>
		<div style="clear: both;" />
		<hr class="seperator post-seperator post-seperator-upper" />
		<div id="viewmessage_' . $pm['id'] . '" style="overflow:auto;" class="post-message-div">' . call('bbcode', $pm['message']) . '</div>';
		if(!empty($pm['signature'])) {
			echo '<hr class="seperator post-seperator post-seperator-lower" /><div style="overflow: auto;" class="signature-div post-signature-div">'.call('bbcode', $pm['signature']).'</div>';
		}
		echo '</td>
	</tr>
  </table>'.theme('end_content');
      }
	  else {
	  echo $pm;
	  }
      break;
      case'quickedit';
      $sql = call('sql_query', "SELECT * FROM forum_posts WHERE id = '" . $_POST['postid'] . "'");
      $fetch = call('sql_fetch_array',$sql);
     if(((isset($user['id']) && $fetch['author_id'] == $user['id'] && $user['modify_own_posts']) || ($user['guest'] && $fetch['ip']== call('visitor_ip'))) || ($user['modify_any_posts'])) {
          if (isset($_POST['type']) == 'cancel') {
              echo json_encode(array("subject" => stripslashes($fetch['subject']), "message" => call('bbcode', stripslashes($fetch['message']))));
          } else {
              echo json_encode(array("subject" => stripslashes($fetch['subject']), "message" => stripslashes($fetch['message'])));
          }
      } else {
	  	  echo 'You do not have permission to edit this';
          die();
	  }
      break;
      case'saveedit';
      $sql = call('sql_query', "SELECT * FROM forum_posts WHERE id = '" . $_POST['postid'] . "'");
      $fetch = call('sql_fetch_array',$sql);
     if(((isset($user['id']) && $fetch['author_id'] == $user['id'] && $user['modify_own_posts']) || ($user['guest'] && $fetch['ip']== call('visitor_ip'))) || ($user['modify_any_posts'])) {
          if (empty($_POST['subject'])) {
              $_POST['subject'] = 'RE:' . $fetch['subject'];
          }
          if (empty($_POST['message'])) {
              $_POST['message'] = $fetch['message'];
          }
          $firstpost = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '" . $fetch['topic_id'] . "' ORDER BY post_time ASC LIMIT 1");
          $fetchfirst = call('sql_fetch_array',$firstpost);
          if ($fetchfirst['id'] == $_POST['postid']) {
              $updatetitle = call('sql_query', "UPDATE forum_topics SET topic_title = '" . $_POST['subject'] . "' WHERE topic_id = '" . $fetch['topic_id'] . "'");
          }
		  //set the topic as new
		  $deleteread = call('sql_query', "DELETE FROM topic_read WHERE topic_id = '".$fetch['topic_id']."' AND user_id!='".$user['id']."'");
          $updatepost = call('sql_query', "UPDATE forum_posts SET subject = '".$_POST['subject']."', message = '".$_POST['message']."', modified_time = '".time()."', modified_name = '".$user['user']."', modified_nameid = '".$user['id']."' WHERE id = '" . $_POST['postid'] . "'");
          if ($updatepost) {
              $sql2 = call('sql_query', "SELECT * FROM forum_posts WHERE id = '" . $_POST['postid'] . "'");
              $fetch2 = call('sql_fetch_array',$sql2);
              
              echo json_encode(array("subject" => $fetch2['subject'], "message" => call('bbcode', $fetch2['message'])));
          } else {
              echo '<strong>'.$AJAX_LANG["not_updated"].'</strong>';
          }
      }
	  else {
	            echo $AJAX_LANG["no_edit"];
          die();
	  }
      break;
      case'order';
      if ($user['admin_panel']) {
          switch ($_POST['type']) {
              case'links';
              $fields = explode('&amp;', $_REQUEST['order']);
              $order = 0;
              foreach ($fields as $field) {
                  $order++;
                  $field_key_value = explode('=', $field);
                  $level = urldecode($field_key_value[0]);
                  $id = urldecode($field_key_value[1]);
				  echo $order.'=>'.$id;
                  $result = call('sql_query', "UPDATE menu SET item_order=". $order . " WHERE id=" . $id);
                  if ($result) {
                      echo true;
                  } else {
                      echo false;
                  }
              }
              break;
			  case'articlecats';
              $fields = explode('&amp;', $_REQUEST['order']);
              $order = 0;
              foreach ($fields as $field) {
                  $order++;
                  $field_key_value = explode('=', $field);
                  $level = urldecode($field_key_value[0]);
                  $id = urldecode($field_key_value[1]);
                  $result = call('sql_query', "UPDATE article_categories SET item_order=" . $order . " WHERE id=" . $id);
                  if ($result) {
                      echo true;
                  } else {
                      echo false;
                  }
              }
              
              break;
          }
      }
      break;
	  case'permedit';
	  if($user['admin_panel']) {
	  $sql = call('sql_query', "UPDATE membergroups SET name = '" . stripslashes($_POST['value']) . "' WHERE membergroup_id = '" . $_POST['id'] . "'");
	  if($sql) {
	  echo $_POST['value'];
	  }
	  else {
	  echo $AJAX_LANG["mem_not_updated"];
	  }
	  }
	  break;
	  case'editcomment';
	  $sql = call('sql_query', "SELECT author FROM comments WHERE id='" . $_POST['id'] . "'");
	  if(call('sql_num_rows', $sql) == 0) {
	  echo $AJAX_LANG["no_comment"];
	  }
	  else {
	  $fetch = call('sql_fetch_array',$sql);
	    if(($fetch['author'] != $user['user'] && !$user['modify_own_comment']) || (!$user['modify_any_comment'])) {
  echo $AJAX_LANG["no_permedit"];
  }
  else {
  $sql2 = call('sql_query', "UPDATE comments SET message = '" . $_POST['value'] . "' WHERE id='" . $_POST['id'] . "'");
  if($sql2) {
        
  echo call('bbcode', stripslashes($_POST['value']));
  }
  }
	  }
	  break;
case'getcomment';
 $sql = call('sql_query', "SELECT author, message FROM comments WHERE id='" . $_REQUEST['id'] . "'");
	  if(call('sql_num_rows', $sql) == 0) {
	  echo $AJAX_LANG["no_comment"];
	  }
	  else {
	  $fetch = call('sql_fetch_array',$sql);
	    if(($fetch['author'] != $user['user'] && !$user['modify_own_comment']) || (!$user['modify_any_comment'])) {
  echo $AJAX_LANG["no_permedit"];
  }
  else {
  echo stripslashes(html_entity_decode($fetch['message'], ENT_QUOTES));
  }
  
	  }
break;
case'gettitle';
  if(!$user['guest']) {
  //check if the user is a registered moderator
$mod = call('sql_num_rows', call('sql_query', "SELECT * FROM forum_moderators WHERE user_id = '" . $user['id'] . "' AND board_id='" . $_GET['board_id'] . "'"));
if($mod != 0) {
//looks like they are for this board so give them moderator permissions!
$user['sticky_any_topic'] =true; 
$user['sticky_own_topic'] =true; 
$user['move_any_topic'] =true; 
$user['move_own_topic'] =true; 
$user['lock_any_topic'] =true; 
$user['lock_own_topic'] =true; 
$user['delete_any_topic'] =true;
$user['delete_own_topic'] =true;
$user['delete_any_posts'] =true; 
$user['delete_own_posts'] =true;
$user['modify_any_posts'] =true;
$user['modify_own_posts'] =true;
$moderator = true;
}
elseif($user['multi-moderate']) {
$moderator = true;
}
else {
//if not set the variable to false
$moderator = false;
}
}
if($moderator==true) {
$_GET['id'] = explode('topictitle_', $_GET['id']);
$sql = call('sql_query', "SELECT topic_title FROM forum_topics WHERE topic_id = '".$_GET['id'][1]."'");
if($sql) {
$fetch=call('sql_fetch_array', $sql);
echo stripslashes(html_entity_decode($fetch['topic_title'], ENT_QUOTES));
}
}
break;
case'edittopictitle';
  if(!$user['guest']) {
  //check if the user is a registered moderator
$mod = call('sql_num_rows', call('sql_query', "SELECT * FROM forum_moderators WHERE user_id = '" . $user['id'] . "' AND board_id='" . $_GET['board_id'] . "'"));
if($mod != 0) {
//looks like they are for this board so give them moderator permissions!
$user['sticky_any_topic'] = true; 
$user['sticky_own_topic'] = true; 
$user['move_any_topic'] = true; 
$user['move_own_topic'] = true; 
$user['lock_any_topic'] = true; 
$user['lock_own_topic'] = true; 
$user['delete_any_topic'] = true;
$user['delete_own_topic'] = true;
$user['delete_any_posts'] = true; 
$user['delete_own_posts'] = true;
$user['modify_any_posts'] = true;
$user['modify_own_posts'] = true;
$moderator = true;
}
elseif($user['multi-moderate']) {
$moderator = true;
}
else {
//if not set the variable to false
$moderator = false;
}
}
if($moderator==true) {
$_POST['id'] = explode('topictitle_', $_POST['id']);
$topicdata = call('sql_query', "SELECT locked FROM forum_topics WHERE topic_id = '".$_POST['id'][1]."'");
$topicdata = call('sql_fetch_array', $topicdata);
$sql = call('sql_query', "UPDATE forum_topics SET topic_title = '".$_POST['value']."' WHERE topic_id = '".$_POST['id'][1]."'");
if($sql) {
echo '<strong><a href="'.$settings['site_url'].'/index.php?act=viewtopic&id='.$_POST['id'][1].'">'.stripslashes(html_entity_decode($_POST['value'], ENT_QUOTES)).'</a></strong>'.($topicdata['locked'] == '1' ? ' <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/minilock.png" />' : '');
}
}
break;
case'plugin';
$pluginsql = call('sql_query', "SELECT folder FROM plugins WHERE active = '1' AND id = '" . $_GET['id'] . "'");
  if(call('sql_num_rows', $pluginsql) != 0) {
while($pluginfetch = call('sql_fetch_array',$pluginsql)) {
if(file_exists('plugins/' . $pluginfetch['folder'] . '/ajax.php')) {
include('plugins/' . $pluginfetch['folder'] . '/ajax.php');
}
}
}
break;
case'boardperms';
$sql = call('sql_query', "SELECT * FROM membergroups");
	while($r = call('sql_fetch_array', $sql)) {
	echo '<a href="'.$settings['site_url'].'/index.php?act=admin&opt=forum&'.$authid.'&type=permissions&mid='.$r['membergroup_id'].'&board='.$_GET['id'].'" title="Edit Permissions">'.$r['name'].'</a><br />';
	}
break;
case 'usernamecheck';
	$sql = call('sql_query', "SELECT user FROM users WHERE user = '".$_REQUEST['username']."'");
	if(call('sql_num_rows', $sql) == 0)
		echo 'true';
	else
		echo 'false';
break;
}
?>