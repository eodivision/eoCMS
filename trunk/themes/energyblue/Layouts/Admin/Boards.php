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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

  //set Forums as the title
  $title = $BOARDS_LANG["title"];
  //do all the javascript stuff needed like validate forms
  $head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.populate.pack.js"></script>
  <script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.cluetip.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#perms tr").click(function(event) {
    if (event.target.type !== "checkbox") {
    $(":checkbox", this).trigger("click");
    }
  });
 $("#check").click(function()  {
      $("input[@id=delete]").each(function()    {
      this.checked = true;
      });
      });
          $("#uncheck").click(function()  {
      $("input[id=delete]").each(function()    {
      this.checked = false;
      });
      });
  $("#boards").validate();
  $("#categories").validate();
});
$(function()
{
	$(".visible").click(function(event) { event.preventDefault(); $(".visiblebox").slideToggle(); });
	$(".boards tr").mouseover(function() { $(this).removeClass(\'inline\').addClass(\'inline\'); });
	$(".boards tr").mouseout(function() { $(this).removeClass(\'inline\'); });
	$(".perms").cluetip({ activation: "click", sticky: true, attribute: "rel", dropShaddow: false, dropShadowSteps: 0, showTitle: false, cluetipClass: " tooltip" });
});
</script>';
$body ='<div id="tooltip"></div>';
  //check if theres a type set, if not then we show the list of boards and new board/new category forms
  if (!isset($_GET['type']) || $_GET['type'] == null) {
      //if the new board is posted
      if (isset($_POST['boards'])) {
          //add the new board
		  $_POST['sticky'] = (!isset($_POST['sticky']) ? false : $_POST['sticky']);
		  $_POST['lock'] = (!isset($_POST['lock']) ? false : $_POST['lock']);
          $check = call('addboard', $_POST['board_name'], $_POST['board_description'], $_POST['post_group'], $_POST['visible'], $_POST['category'], $_POST['sticky'], $_POST['lock']);
          if ($check == true && !errors()) {
              $_SESSION['update'] = $BOARDS_LANG["body_content"];
          }
      }
      //if new category is posted
      elseif (isset($_POST['cats'])) {
          //add it
          $check = call('addforumcat', $_POST['cat_name']);
          if ($check == true && !errors()) {
              //if no errors then show an update message
              $_SESSION['update'] = $BOARDS_LANG["cat_added"];
          }
      } else {
          $check = false;
      }
      //new category form
      $body .= '<form action="" method="post" id="categories"><div class="admin-panel">'. theme('title', $BOARDS_LANG["new_category"]) . theme('start_content') . '<table class="admin-table">
  <tr>
  	<td>'.$BOARDS_LANG["category_name"].':</td>
  </tr>
  <tr>
    <td><input type="text" name="cat_name" class="required" value="';
      if ($check == false && isset($_POST['cat_name']))
          $body .= $_POST['cat_name'];
      $body .= '" /></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" name="cats" value="'.$BOARDS_LANG["btn_add_category"].'" /><input name="Reset" type="reset" value="'.$BOARDS_LANG["btn_reset"].'" /></td>
  </tr>
</table>'. theme('end_content') .'</div></form><br />';
      //complicated query and whiles to get the forum categories and its boards
      $query = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order ASC");
      $cats = array();
      if (call('sql_num_rows', $query) != 0) {
          while ($p = call('sql_fetch_array', $query)) {
              $count = call('sql_num_rows', call('sql_query', "SELECT id FROM forum_boards WHERE cat = '" . $p['id'] . "'"));
              $cats[$p['id']] = array('cat_id' => $p['id'], 'cat_name' => $p['cat_name'], 'board_count' => $count, 'cat_count' => call('sql_num_rows', $query),'boards' => array());
          }
          $sql = call('sql_query', "SELECT * FROM forum_boards ORDER BY item_order");
          while ($r = call('sql_fetch_assoc', $sql)) {
              $cats[$r['cat']]['boards'][$r['id']] = array('id' => $r['id'], 'board_name' => $r['board_name'], 'board_description' => $r['board_description']);
          }
          $body .= '<form action="" method="post" id="boards"><div class="admin-panel">'.theme('title', $BOARDS_LANG["new_board"]).theme('start_content').'<table class="admin-table">
  <tr>
    <td>'.$BOARDS_LANG["board_name"].':</td>
  </tr>
  <tr>
    <td><input type="text" name="board_name" class="required" value="';
          if ($check == false && isset($_POST['board_name'])) {
              $body .= $_POST['board_name'];
          }
          $body .= '" /></td>
  </tr>
  <tr>
    <td>'.$BOARDS_LANG["board_description"].':</td>
  </tr>
  <tr>
    <td colspan="2"><textarea name="board_description" cols="36" rows="2" class="required">';
          if ($check == false && isset($_POST['board_description'])) {
              $body .= $_POST['board_description'];
          }
          $body .= '</textarea></td>
  </tr>
  <tr>
    <td>'.$BOARDS_LANG["board_category"].': <select name="category">';
          foreach ($cats as $cat) {
              $body .= '<option value="' . $cat['cat_id'] . '">' . $cat['cat_name'] . '</option>';
          }
          $body .= '</select></td>
  </tr>
  <tr>
  	<td class="admin-subtitlebg"><a href="javascript:void;" title="'.$BOARDS_LANG["t_click_to_expand"].'" class="visible">'.$BOARDS_LANG["board_permissions"].'</a></td>
  </tr>
  <tr class="visiblebox">
    <td>'.$BOARDS_LANG["create_topics"].'/'.$BOARDS_LANG["post_replies"].':</td>
  </tr>
  <tr class="visiblebox">
    <td>';
          $sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
          while ($m = call('sql_fetch_array', $sql2)) {
              $body .= '<input type="checkbox" value="' . $m['membergroup_id'] . '" name="post_group[]" id="topics_' . $m['membergroup_id'] . '"';
              if ($m['membergroup_id'] != 1 || $m['membergroup_id'] > 5) {
                  $body .= ' checked="checked"';
              }
              $body .= ' /><label for="topics_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
          }
          $body .= '</td>
  </tr>
  <tr class="visiblebox">
    <td>'.$BOARDS_LANG["forum_access"].':</td>
  </tr>
  <tr class="visiblebox">
    <td>';
          $sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
          while ($m = call('sql_fetch_array', $sql3)) {
              $body .= '<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="visible_' . $m['membergroup_id'] . '"';
              if ($m['membergroup_id'] != 1 || $m['membergroup_id'] > 5) {
                  $body .= ' checked="checked"';
              }
              $body .= ' /><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
          }
          $body .= '</td>
  </tr>
  <tr>
  	<td class="admin-subtitlebg"><a href="javascript:void;" title="'.$BOARDS_LANG["t_click_to_expand"].'" class="visible">'.$BOARDS_LANG["posting_rules"].'</a></td>
  </tr>
  <tr class="visiblebox">
    <td>'.$BOARDS_LANG["topic_creation"].': <br />'.$BOARDS_LANG["sticky_topic"].' <input type="checkbox" name="sticky" /><br />'.$BOARDS_LANG["lock_topic"].' <input type="checkbox" name="lock" /></td>
	</tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="'.$BOARDS_LANG["btn_add_board"].'" name="boards" /><input name="Reset" type="reset" value="'.$BOARDS_LANG["btn_reset"].'" /></td>
  </tr>
</table>'.theme('end_content').'</div></form><br /><div class="admin-panel2">'.theme('title', $BOARDS_LANG["current_boards"]).theme('start_content').'<table align="center" class="admin-table2 boards">';
$catcount = call('sql_num_rows', $query);
          if ($catcount == 0) {
              $body .= '<tr><td>No Boards</td></tr>';
          } else {
              $j = 1;
              foreach ($cats as $cat) {
                  $i = 1;
                  $body .= '<tr class="admin-subtitlebg" id="cat_'.$cat['cat_id'].'"><td>' . $cat['cat_name'] . '</td>';
			$body.='<td></td><td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=editcat&amp;id=' . $cat['cat_id'] . '&amp;' . $authid . '" title="'.$BOARDS_LANG["t_edit"].'"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="'.$BOARDS_LANG["t_edit"].'" /></a></td>
			<td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=deletecat&amp;id=' . $cat['cat_id'] . '&amp;' . $authid . '" title="'.$BOARDS_LANG["t_delete"].'" class="delete"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="'.$BOARDS_LANG["t_delete"].'" /></a></td>';
			if ($j == 1) {
                          $body .= '<td><a title="'.$BOARDS_LANG["t_move_down"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=cat&amp;cat=' . $cat['cat_id'] . '&amp;order=down&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderdown.png" alt="'.$BOARDS_LANG["t_move_down"].'" /></a></td>
						  <td></td>';
                      } elseif ($j < $cat['cat_count']) {
                          $body .= '<td><a title="'.$BOARDS_LANG["t_move_up"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=cat&amp;cat=' . $cat['cat_id'] . '&amp;order=up&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderup.png" alt="'.$BOARDS_LANG["t_move_up"].'" /></a></td>
						  <td><a title="'.$BOARDS_LANG["t_move_down"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=cat&amp;cat=' . $cat['cat_id'] . '&amp;order=down&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderdown.png" alt="'.$BOARDS_LANG["t_move_down"].'" /></a></td>';
                      } else {
                              $body .= '<td><a title="'.$BOARDS_LANG["t_move_up"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=cat&amp;cat=' . $cat['cat_id'] . '&amp;order=up&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderup.png" alt="'.$BOARDS_LANG["t_move_up"].'" /></a></td>
							  <td></td>';
                      }
			$body.='</tr>';
                  foreach ($cat['boards'] as $p) {
                      $body .= '<tr id="'.$p['id'].'">
			<td width="80%"><a href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id=' . $p['id'] . '&amp;' . $authid . '" target="_blank">' . $p['board_name'] . '</a><br />
			<span class="small-text">' . $p['board_description'] . '</span></td>';
                      $body .= '<td><img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/Permissions_Admin.png" width="16" height="16" class="perms" rel="index.php?act=ajax&m=boardperms&id='.$p['id'].'" style="cursor: pointer;" /></td>
					  <td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=edit&amp;id=' . $p['id'] . '&amp;' . $authid . '" title="'.$BOARDS_LANG["t_edit"].'"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="'.$BOARDS_LANG["t_edit"].'" /></a></td>
					  <td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=delete&amp;id=' . $p['id'] . '&amp;' . $authid . '" title="'.$BOARDS_LANG["t_delete"].'" class="delete"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="'.$BOARDS_LANG["t_delete"].'" /></a></td>';
                      if ($i == 1 && $cat['board_count'] > 1) {
                          $body .= '<td><a title="'.$BOARDS_LANG["t_move_down"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=board&amp;cat=' . $cat['cat_id'] . '&amp;order=down&amp;id=' . $p['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderdown.png" alt="'.$BOARDS_LANG["t_move_down"].'" /></a></td>';
                      } elseif ($i < $cat['board_count']) {
                          $body .= '<td><a title="'.$BOARDS_LANG["t_move_up"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=board&amp;cat=' . $cat['cat_id'] . '&amp;order=up&amp;id=' . $p['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderup.png" alt="'.$BOARDS_LANG["t_move_up"].'" /></a></td>
						  <td><a title="'.$BOARDS_LANG["t_move_down"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=board&amp;cat=' . $cat['cat_id'] . '&amp;order=down&amp;id=' . $p['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderdown.png" alt="'.$BOARDS_LANG["t_move_down"].'" /></a></td>';
                      } else {
                          if ($cat['board_count'] > 1) {
                              $body .= '<td><a title="'.$BOARDS_LANG["t_move_up"].'" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=order&amp;ordertype=board&amp;cat=' . $cat['cat_id'] . '&amp;order=up&amp;id=' . $p['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderup.png" alt="'.$BOARDS_LANG["t_move_up"].'" /></a></td>';
                          }
                      }
			$body.='</tr>';
                     $i++;
                  }
              $j++;
              }
          }
          $body .= '</table>'.theme('end_content').'</div>';
      }
  }
  if (isset($_GET['type']) && $_GET['type'] == 'edit') {
      if (isset($_POST['board_name'])) {
          $check = call('editboard', $_POST['board_name'], $_POST['board_description'], $_POST['post_group'], $_POST['visible'], $_GET['id'], $_POST['moderators'], $_POST['category'], $_POST['sticky'], $_POST['lock']);
      } elseif (isset($_POST['delete'])) {
          $check = call('removemoderators', $_POST['delete'], $_GET['id']);
      } else {
          $check = false;
      }
      if ($check == true && !errors()) {
          $_SESSION['update'] = $BOARDS_LANG["body_content_update"];
          session_write_close();
          call('redirect', 'index.php?act=admin&opt=forum&'.$authid);
      }
      $cats = call('sql_query', "SELECT * FROM forum_categories");
      $sql = call('sql_query', "SELECT * FROM forum_boards WHERE id = '" . $_GET['id'] . "'");
      $r = call('sql_fetch_assoc', $sql);
      $body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;'.$authid.'">Forum</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['board_name'].'</a>')).'<form action="" method="post" id="boards"><div class="admin-panel">'.theme('title', $BOARDS_LANG["edit_board"]).theme('start_content').'<table class="admin-table">
  <tr>
    <td colspan="2">'.$BOARDS_LANG["board_name"].':</td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="board_name" class="required" value="';
      if (errors()) {
          $body .= $_POST['board_name'];
      } else {
          $body .= $r['board_name'];
      }
      $body .= '" /></td>
  </tr>
  <tr>
    <td colspan="2">'.$BOARDS_LANG["board_description"].':</td>
  </tr>
  <tr>
    <td colspan="2"><textarea name="board_description" class="required" cols="36" rows="2">';
      if (errors()) {
          $body .= $_POST['board_description'];
      } else {
          $body .= $r['board_description'];
      }
      $body .= '</textarea></td>
  </tr>
    <tr>
    <td colspan="2">'.$BOARDS_LANG["board_category"].': <select name="category">';
      while ($cat = call('sql_fetch_array', $cats)) {
          $body .= '<option value="' . $cat['id'] . '"' . ($cat['id'] == $r['cat'] ? ' selected="selected"' : '') . '>' . $cat['cat_name'] . '</option>';
      }
      $body .= '</select></td>
  </tr>
  <tr>
  	<td class="admin-subtitlebg" colspan="2"><a href="javascript:void;" title="'.$BOARDS_LANG["t_click_to_expand"].'" class="visible">'.$BOARDS_LANG["board_permissions"].'</a></td>
  </tr>
  <tr class="visiblebox">
    <td>'.$BOARDS_LANG["create_topics"].'/'.$BOARDS_LANG["post_replies"].'</td>
  </tr>
  <tr class="visiblebox">
    <td>';
      $sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
      while ($m = call('sql_fetch_array', $sql2)) {
          $body .= '<input type="checkbox" value="' . $m['membergroup_id'] . '" name="post_group[]" id="topics_' . $m['membergroup_id'] . '"';
          if (call('visiblecheck', $m['membergroup_id'], $r['post_group'])) {
              $body .= 'checked="checked"';
          }
          $body .= '/><label for="topics_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
      }
      $body .= '</td>
  </tr>
  <tr class="visiblebox">
    <td>'.$BOARDS_LANG["forum_access"].'</td>
  </tr>
  <tr class="visiblebox">
    <td>';
      $sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
      while ($m = call('sql_fetch_array', $sql3)) {
          $body .= '<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="visible_' . $m['membergroup_id'] . '"';
          if (call('visiblecheck', $m['membergroup_id'], $r['visible'])) {
              $body .= 'checked="checked"';
          }
          $body .= '/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
      }
      $body .= '</td>
  </tr>
  <tr>
  	<td class="admin-subtitlebg" colspan="2"><a href="javascript:void;" title="'.$BOARDS_LANG["t_click_to_expand"].'" class="visible">'.$BOARDS_LANG["posting_rules"].'</a></td>
  </tr>
  <tr class="visiblebox">
    <td>'.$BOARDS_LANG["topic_creation"].': <br />'.$BOARDS_LANG["sticky_topic"].' <input type="checkbox" name="sticky" '.($r['creation_sticky']=='1' ? 'checked="checked"' : '').' /><br />'.$BOARDS_LANG["lock_topic"].' <input type="checkbox" name="lock" '.($r['creation_lock']=='1' ? 'checked="checked"' : '').' /></td>
	</tr>
  <tr>
  	<td>'.$BOARDS_LANG["add_moderators"].':</td>
	<td><input type="text" name="moderators" id="moderators" /> <a href="javascript:;" onclick="window.open(\'' . $settings['site_url'] . '/index.php?act=finduser&amp;area=moderators\',\'\',\'width=400,height=300\')" class="help"></a></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="'.$BOARDS_LANG["btn_update_board"].'" name="Submit" /></td>
  </tr>
</table>'.theme('end_content').'</div></form><br /><form action="" method="post" id="remove"><div class="admin-panel">'.theme('title', $BOARDS_LANG["current_moderators"]).theme('start_content').'<table class="admin-table1">';
      $mem = call('sql_query', "SELECT users.id, user, forum_moderators.id AS mod_id FROM users LEFT JOIN forum_moderators ON user = user_id WHERE board_id = '" . $_GET['id'] . "'");
      if (call('sql_num_rows', $mem) == 0) {
          $body .= '<tr><td>No moderators for this board exist</td></tr>';
      } else {
          $body .= '<tr class="subtitlebg" align="center">
  <td>'.$BOARDS_LANG["username"].'</td>
  </tr>';
          while ($s = call('sql_fetch_array', $mem)) {
              $body .= '<tr><td><input type="checkbox" name="delete[]" value="' . $s['mod_id'] . '" id="delete" /> ' . $s['user'] . '</td></tr>';
          }
          $body .= '<tr><td><a href="javascript:void(0);" id="check">'.$BOARDS_LANG["select_all"].'</a> <a href="javascript:void(0);" id="uncheck">'.$BOARDS_LANG["unselect_all"].'</a>
</td></tr>
<tr><td><input type="submit" value="'.$BOARDS_LANG["btn_remove_selected"].'" /></td></tr>';
      }
      $body .= '</table>'.theme('end_content').'
  </div></form>';
  }
  if (isset($_GET['type']) && $_GET['type'] == 'delete') {
      $check = call('deleteboard', $_GET['id']);
      if ($check == true && !errors()) {
          $_SESSION['update'] = $BOARDS_LANG["body_content_delete"];
		  if(!isset($_GET['ajax'])) {
			  session_write_close();
			  call('redirect', 'index.php?act=admin&opt=forum&'.$authid);
		  } else
			echo json_encode(array('message' => $_SESSION['update'], 'id' => $_GET['id']));
      }
  }
  if (isset($_GET['type']) && $_GET['type'] == 'editcat') {
      if ($_POST) {
          $check = call('editforumcat', $_POST['cat_name'], $_GET['id']);
      } else {
          $check = false;
      }
      if ($check == true && !errors()) {
          $_SESSION['update'] = $BOARDS_LANG["body_cat_update"];
          session_write_close();
          call('redirect', 'index.php?act=admin&opt=forum&'.$authid);
      }
      $r = call('sql_fetch_array', call('sql_query', "SELECT * FROM forum_categories WHERE id = '" . $_GET['id'] . "'"));
      $body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;'.$authid.'">Forum</a>', 'Category', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=editcat&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['cat_name'].'</a>')).'<form action="" method="post" id="categories"><div class="admin-panel">'.theme('title', 'Edit Category').theme('start_content').'<table class="admin-table">
  <tr>
    <td>'.$BOARDS_LANG["category_name"].':</td>
  </tr>
  <tr>
    <td><input type="text" name="cat_name" class="required" value="';
      if ($check == false && isset($_POST['cat_name'])) {
          $body .= $_POST['cat_name'];
      } else {
          $body .= $r['cat_name'];
      }
      $body .= '" /></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" name="cats" value="'.$BOARDS_LANG["btn_update_category"].'" /><input name="Reset" type="reset" value="'.$BOARDS_LANG["btn_reset"] .'" /></td>
  </tr>
</table>'.theme('end_content').'</div></form><br />';
  }
  if (isset($_GET['type']) && $_GET['type'] == 'deletecat') {
      $check = call('deleteforumcat', $_GET['id']);
      if ($check == true && !errors()) {
          $_SESSION['update'] = $BOARDS_LANG["body_cat_delete"];
		  if(!isset($_GET['ajax'])) {
          		session_write_close();
          		call('redirect', 'index.php?act=admin&opt=forum&'.$authid);
		  } else
			echo json_encode(array('message' => $_SESSION['update'], 'id' => 'cat_'.$_GET['id']));
      }
  }
  if (isset($_GET['type']) && $_GET['type'] == 'order') {
      if (isset($_GET['ordertype']) && $_GET['ordertype'] == 'board') {
          $sql = call('sql_query', "SELECT * FROM forum_boards WHERE cat = '" . $_GET['cat'] . "'");
          while ($p = call('sql_fetch_array', $sql)) {
              if ($_GET['order'] == 'down') {
                  $result = call('sql_query', "UPDATE forum_boards SET item_order = item_order-1 WHERE id = '" . $p['id'] . "'");
				  $result = call('sql_query', "UPDATE forum_boards SET item_order = item_order+1 WHERE id = '" . $_GET['id'] . "'");
              } elseif ($_GET['order'] == 'up') {
                  $result = call('sql_query', "UPDATE forum_boards SET item_order = item_order+1 WHERE id = '" . $p['id'] . "'");
				  $result = call('sql_query', "UPDATE forum_boards SET item_order = item_order-1 WHERE id = '" . $_GET['id'] . "'");
              }
          }
      }
	  if(isset($_GET['ordertype']) && $_GET['ordertype'] == 'cat') {
	      $sql = call('sql_query', "SELECT * FROM forum_categories");
          while ($p = call('sql_fetch_array', $sql)) {
              if ($_GET['order'] == 'down') {
                  $result = call('sql_query', "UPDATE forum_categories SET item_order = item_order-1 WHERE id = '" . $p['id'] . "'");
				   $result = call('sql_query', "UPDATE forum_categories SET item_order = item_order+1 WHERE id = '" . $_GET['cat'] . "'");
              } elseif ($_GET['order'] == 'up') {
                  $result = call('sql_query', "UPDATE forum_categories SET item_order = item_order+1 WHERE id = '" . $p['id'] . "'");
				  $result = call('sql_query', "UPDATE forum_categories SET item_order = item_order-1 WHERE id = '" . $_GET['cat'] . "'");
              }
          }
	  }
      if ($result) {
          call('redirect', 'index.php?act=admin&opt=forum&'.$authid);
      }
  }
  if(isset($_GET['type']) && $_GET['type'] == 'permissions') {
  //array with all the permission variables
  $perms = array('sticky_any_topic', 'sticky_own_topic', 'move_any_topic', 'move_own_topic', 'lock_any_topic', 'lock_own_topic', 'delete_any_topic', 'delete_own_topic', 'delete_any_posts', 'delete_own_posts', 'modify_any_posts', 'modify_own_posts', 'track_ip', 'multi-moderate');
  $sql = call('sql_query', "SELECT board_name FROM forum_boards WHERE id = '".$_GET['board']."'");
  $board = call('sql_fetch_array', $sql);
  $sql = call('sql_query', "SELECT name FROM membergroups WHERE membergroup_id = '".$_GET['mid']."'");
  $member = call('sql_fetch_array', $sql);
  $sql = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id = '".$_GET['mid']."'");
  while($p = call('sql_fetch_array', $sql)) {
  $cperms[$p['variable']] = $p['value'];
  }
  foreach($cperms as $key => $value) {
  	if(!in_array($key, $perms)) {
  		unset($cperms[$key]);
  	}
  }
  $sql = call('sql_query', "SELECT * FROM board_permissions");
	if(call('sql_num_rows', $sql) != 0) {
		while($p = call('sql_fetch_array', $sql)) {
			$bperms[$p['variable']] = $p['value'];
  		}
	} else {
		$bperms = array();
	}
  $cperms = array_merge($cperms, $bperms);
  $body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;'.$authid.'">Forum</a>', 'Permissions', $board['board_name'], '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;type=permissions&amp;mid='.$_GET['mid'].'&amp;board='.$_GET['board'].'&amp;'.$authid.'">'.$member['name'].'</a>')).'<form action="" method="post"><div class="admin-panel2">'.theme('title', 'Edit '.$board['board_name'].' Permissions for '.$member['name']).theme('start_content').'
  <table class="admin-table2" id="perms">';
  foreach($cperms as $variable => $value) { 
  $perm = ucwords($variable);
  $perm = str_replace('_', ' ', $perm);
  $body.='<tr onmouseover="$(this).removeClass().addClass(\'inline\');" onmouseout="$(this).removeClass();">
    <td>' . $perm . '</td><td><input type="checkbox" name="variable[' . $variable . ']" '; if($value==1) { $body.='checked="checked"'; } $body.='/></td>
  </tr>';
  }
  $body.='
  <tr>
  	<td><input type="submit" value="'.$BOARDS_LANG["btn_update_perms"].'" /></td>
  </tr>
  </table>'.theme('end_content').'
  </div>
  </form>';
    if($_POST) {
$check = call('updateboardperms', $_POST['variable'], $_GET['board'], $_GET['mid']);
}
else { $check = false; }
if($check==true && !errors()) {
	$_SESSION['update'] = $BOARDS_LANG["body_boardperm_update"];
	session_write_close();
	call('redirect', 'index.php?act=admin&opt=forum&'.$authid);
}
  }
?>