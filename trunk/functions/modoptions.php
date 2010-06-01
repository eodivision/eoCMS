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

function modoptions($topicid, $boardid, $topic_ip){
      global $user, $authid, $VIEWTOPIC_LANG, $settings;
      $modcontrols = '<table><tr>';
      $sql = call('sql_query', "SELECT * FROM forum_topics WHERE topic_id = '$topicid'");
      $fetch = call('sql_fetch_array', $sql);
      $board = call('sql_query', "SELECT * FROM forum_boards WHERE id = '$boardid'", 'cache');
      $board = $board[0];
      if($user['view'] == '2' && $fetch['locked'] != '1') {
          $modcontrols .= '<td class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=reply&amp;topic=' . $_GET['id'] . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_post_reply"])).'</a></td>';
      } else {
          $modcontrols .= '<td></td>';
      }
	  //if(($topic_ip == call('visitor_ip') || $user['user'] == $fetch['topic_author']) && 
      if(((!$user['guest'] && $user['user'] == $fetch['topic_author'] && $user['lock_topic'] == '2')) || ($user['guest'] && empty($fetch['topic_author']) && $topic_ip == call('visitor_ip') && $user['lock_own_topic']) || $user['lock_any_topic']) {
          if ($fetch['locked'] == '0') {
              $modcontrols .= '<td class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=locktopic&amp;id=' . $topicid . '&amp;' . $authid . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_lock_topic"])).'</a></td>';
          }
          if ($fetch['locked'] == '1') {
              $modcontrols .= '<td class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=unlocktopic&amp;id=' . $topicid . '&amp;' . $authid . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_unlock_topic"])).'</a></td>';
          }
      }
      if (((!$user['guest'] && $user['user'] == $fetch['topic_author'] && $user['delete_own_topic'])) || ($user['guest'] && empty($fetch['topic_author']) && $topic_ip == call('visitor_ip') && $user['delete_own_topic']) || $user['delete_any_topic']) {
          $modcontrols .= ' <td class="imagebutton"><a onclick="return confirm(\'Are you sure you want to delete this topic?\')" href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=deletetopic&amp;id=' . $topicid . '&amp;board_id=' . $boardid . '&amp;' . $authid . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_delete_topic"])).'</a></td>';
      }
      if (((!$user['guest'] && $user['user'] == $fetch['topic_author'] && $user['sticky_own_topic'])) || ($user['guest'] && empty($fetch['topic_author']) && $topic_ip == call('visitor_ip') && $user['sticky_own_topic']) || $user['sticky_any_topic']) {
          if ($fetch['sticky'] == '0') {
              $modcontrols .= ' <td class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=sticky&amp;id=' . $topicid . '&amp;' . $authid . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_sticky_topic"])).'</a></td>';
          }
          if ($fetch['sticky'] == '1') {
              $modcontrols .= ' <td class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=unsticky&amp;id=' . $topicid . '&amp;' . $authid . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_unsticky_topic"])).'</a></td>';
          }
      }
      if (((!$user['guest'] && $user['user'] == $fetch['topic_author'] && $user['move_own_topic'])) || ($user['guest'] && empty($fetch['topic_author']) && $topic_ip == call('visitor_ip') && $user['move_own_topic']) || $user['move_any_topic']) {
          $modcontrols .= ' <td class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=move&amp;id=' . $topicid . '&amp;board=' . $boardid . '&amp;' . $authid . '">'.(str_replace(' ','&nbsp;',$VIEWTOPIC_LANG["btn_move_topic"])).'</a></td>';
      }
      if (((!$user['guest'] && $user['user'] == $fetch['topic_author'] && $user['delete_own_topic'])) || ($user['guest'] && empty($fetch['topic_author']) && $topic_ip == call('visitor_ip') && $user['delete_own_topic']) || $user['delete_any_topic']) {
          $modcontrols .= '</tr>
<tr>
  <td colspan="4">
    <select name="opt">
    <option value="spam">'.$VIEWTOPIC_LANG["o_delete_spam"].'</option>
    </select>
    <input type="submit" id="go" value="'.$VIEWTOPIC_LANG["btn_delete_go"].' (0)" name="go" /><br />
  </td>
</tr>';
      } else {
          $modcontrols .= '</tr>';
      }
      $modcontrols .= '</table><br />';
      return $modcontrols;
  }
?>