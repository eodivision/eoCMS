<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$theme['title'] = 'Pages';
$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="' . $settings['site_url'] . '/js/markitup/sets/html/set.js"></script>
<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
<script type="text/javascript" >
   $(document).ready(function() {
      $("#content").markItUp(myHTMLSettings);
	  $("#pages").validate();
   });
	$(function() {
		$(".pages tr").mouseover(function() { $(this).removeClass(\'inline\').addClass(\'inline\'); });
		$(".pages tr").mouseout(function() { $(this).removeClass(\'inline\'); });
	});
</script>';
if(!isset($_GET['type']) || $_GET['type'] == null) {
	if($_POST) {
		if(!(isset($_POST['comment'])))
			$_POST['comment'] = 0;
		if(!(isset($_POST['ratings'])))
			$_POST['ratings'] = 0;
		$check = call('addpage', addslashes($_POST['pagename']), htmlentities($_POST['content']), $_POST['comment'], $_POST['ratings']);
	}
	else
		$check = false;
	if($check==true && !errors())
		$_SESSION['update'] = 'The page has been added';
	$sql= call('sql_query', "SELECT * FROM pages");
	$pages = array();
	while($r = call('sql_fetch_assoc', $sql)) {
		$pages[$r['id']] = $r;
	}
	$theme['body'] ='<form action="" method="post"><div class="admin-panel2">'.theme('title', 'New Page').theme('start_content').'<table class="admin-table">
  <tr>
  	<td>Page Name:</td>
  </tr>
  <tr>
    <td><input type="text" name="pagename" class="required" value="'; if($check == false && isset($_POST['pagename'])) { $theme['body'].= $_POST['pagename']; } $theme['body'] .='" /></td>
  </tr>
  <tr>
  	<td>Page Content: (HTML)</td>
  </tr>
  <tr>
    <td colspan="2"><textarea rows="1" cols="50" name="content" id="content" class="required">'; if($check == false && isset($_POST['content'])) { $theme['body'].= htmlspecialchars_decode($_POST['content'], ENT_QUOTES); } $theme['body'] .='</textarea></td>
  </tr>
  <tr>
  	<td>Comments: <input name="comment" type="checkbox" '; if($check == false && isset($_POST['comment'])) { $theme['body'].= 'checked="checked"'; } $theme['body'].= ' /></td>
  </tr>
  <tr>
  	<td>Ratings: <input type="checkbox" name="ratings" '; if($check == false && isset($_POST['ratings'])) { $theme['body'].= 'checked="checked"'; } $theme['body'].= ' /></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Add Page" name="Submit" /><input name="Reset" type="reset" value="Reset" /></td>
  </tr>
</table>
'.theme('end_content').'</div></form><br />
<div class="admin-panel2">'.theme('title', 'Current Pages').theme('start_content').'<table class="admin-table2 pages">
<tr><th>ID</th><th>Name</th><th>Options</th></tr><tr><td>';
	foreach ($pages as $p) {
            $theme['body'].='<tr id="'.$p['id'].'">
			 <td width="2%">' . $p['id'] . '</td>
            <td width="88%"><a href="'.$settings['site_url'].'/index.php?act=page&id=' . $p['id'] . '" target="_blank">' . $p['pagename'] . '</a><br />
            <td width="10%"><a href="'.$settings['site_url'].'/index.php?act=admin&opt=pages&type=edit&id=' . $p['id'] . '&amp;'.$authid.'" title="Edit"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&opt=pages&type=delete&id=' . $p['id'] . '&amp;'.$authid.'" title="Delete" class="delete"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span></td>
          </tr>';
	}
	$theme['body'] .='</td></tr></table>'.theme('end_content').'</div>';
}
if(isset($_GET['type']) && $_GET['type'] == 'edit') {
	if($_POST)
		$check = call('editpage', stripslashes($_POST['pagename']), htmlentities($_POST['content']), $_GET['id'], $_POST['comment'], $_POST['rating']);
	else
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The page has been updated';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=pages&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM pages WHERE id = '" . $_GET['id'] . "'");
	$r = call('sql_fetch_assoc', $sql);
	$theme['body'] = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=pages&amp;'.$authid.'">Pages</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=pages&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['pagename'].'</a>')).'<form action="" method="post"><div class="admin-panel2">'.theme('title', 'Edit Page').theme('start_content').'<table class="admin-table">
  <tr>
  	<td>Page Name:</td>
  </tr>
  <tr>
    <td><input type="text" name="pagename" class="required" value="'; if(errors()) { $theme['body'].= $_POST['pagename']; } else { $theme['body'] .= stripslashes($r['pagename']); } $theme['body'] .='" /></td>
  </tr>
  <tr>
   	<td>Page Content:(HTML)</td>
  </tr>
  <tr>
    <td colspan="2"><textarea rows="1" cols="50" wrap="virtual" name="content" class="required" wrap="virtual" onclick="this.cols=50;this.rows=25" onblur="this.cols=50;this.rows=1" id="content">'; if(errors()) { $theme['body'].= $_POST['content']; } else { $theme['body'] .= htmlspecialchars_decode(stripslashes($r['content']), ENT_QUOTES); } $theme['body'] .='</textarea></td>
  </tr>
      <tr>
  	<td>Comments: <input name="comment" type="checkbox" '; if(errors() && isset($_POST['comment']) || $r['comments'] =='1') { $theme['body'].= 'checked="checked"'; } $theme['body'].= ' /></td>
  </tr>
        <tr>
  	<td>Ratings: <input name="rating" type="checkbox" '; if(errors() && isset($_POST['ratings']) || $r['ratings'] =='1') { $theme['body'].= 'checked="checked"'; } $theme['body'].= ' /></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Update Page" name="Submit" /></td>
  </tr>
</table>'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
	$check = call('deletepage', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The page has been deleted';
		if(!isset($_GET['ajax'])) {
			session_write_close();
			call('redirect', 'index.php?act=admin&opt=pages&'.$authid);
		} else
			echo json_encode(array('message' => $_SESSION['update'], 'id' => $_GET['id']));
	}
}
?>