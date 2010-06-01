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
$theme['title'] = 'Emoticons';
$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#add").validate();
	$("#image").change(function() {
		$("#preview").attr("src", "images/emoticons/" + $(this).val());
	});
	$(".emoticons tr").mouseover(function() { $(this).removeClass(\'inline\').addClass(\'inline\'); });
	$(".emoticons tr").mouseout(function() { $(this).removeClass(\'inline\'); });
});
</script>';
  if (!isset($_GET['type']) || $_GET['type'] == null) {
      if ($_POST) {
          $check = call('addemoticon', $_POST['code'], $_POST['image'], $_POST['text']);
      } else {
          $check = false;
      }
      if ($check == true && !errors()) {
          $_SESSION['update'] = 'The emoticon has been added';
      }
      $sql = call('sql_query', "SELECT * FROM emoticons");
      $theme['body'] = '<form action="" method="post" id="add"><div class="admin-panel">'.theme('title', 'Add Emoticon').theme('start_content').'<table class="admin-table">
  			<tr>
				<td>Emoticon Code: <input type="text" ' . (errors() ? 'value="' . $_POST['code'] . '"' : '') . ' name="code" class="required"/></td>
			</tr>
  			<tr>
				<td>Image: <select name="image" class="required" id="image">';
      $dir = 'images/emoticons/';
		if(is_dir($dir)) {
			if($dh = opendir($dir)) {
				while(($file = readdir($dh)) !== false) {
					if($file != "." && $file != ".." && $file != 'index.php' && $file != '.svn') {
						if(!(isset($_POST['image'])))
							$_POST['image'] = $file;}
						$theme['body'] .= '<option value="' . $file . '" ' . (errors() && $file == $_POST['image'] ? 'selected="selected"' : '') . '>' . $file . '</option>';
					}
				}
			}
			closedir($dh);
		}
      $theme['body'] .= '</select></td></tr>
	<tr>
	  	<td>Emoticon Preview: <img src="images/emoticons/' . $_POST['image'] . '" id="preview" alt="'. substr($_POST['image'], 0, strrpos($_POST['image'], '.')) .'"/></td>
	</tr>
	<tr>
		<td>Emoticon Text: <input type="text" name="text" class="required" ' . (errors() ? 'value="' . $_POST['text'] . '"' : '') . ' /></td>
	</tr>
	<tr>
		<td align="center"><input type="submit" value="Add Emoticon" name="Submit" /></td>
	</tr>
	</table>'.theme('end_content').'</div></form><br />
	<div class="admin-panel2">'.theme('title', 'Current Emoticons').theme('start_content').'<table class="admin-table emoticons">
	<tr class="admin-subtitlebg">
		<th>Code</th>
		<th>Image</th>
		<th>Text</th>
		<th>Options</th>
	</tr>';
      while ($p = call('sql_fetch_assoc', $sql)) {
          $theme['body'] .= '<tr><td>' . $p['code'] . '</td><td><img src="images/emoticons/' . $p['image'] . '" alt="Unavailable"/></td><td>' . $p['alt'] . '</td><td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=emoticons&amp;type=edit&amp;id=' . $p['id'] . '&amp;'.$authid.'" title="Edit this emoticon"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=emoticons&amp;type=delete&amp;id=' . $p['id'] . '&amp;'.$authid.'" title="Delete this emoticon" onclick="return confirm(\'Are you sure you want to delete this emoticon? All posts using this emoticon will be seen as plain text instead\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span></td></tr>';
      }
      $theme['body'] .= '</table>';
  if (isset($_GET['type']) && $_GET['type'] == 'edit') {
      if ($_POST) {
          $check = call('editemoticon', $_POST['code'], $_POST['image'], $_POST['text'], $_GET['id']);
      } else {
          $check = false;
      }
      if ($check == true && !errors()) {
          $_SESSION['update'] = 'The emoticon has been updated';
          session_write_close();
          call('redirect', 'index.php?act=admin&opt=emoticons&'.$authid);
      }
      $p = call('sql_fetch_array', call('sql_query', "SELECT * FROM emoticons WHERE id = '" . $_GET['id'] . "'"));
      $theme['body'] = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=emoticons&amp;'.$authid.'">Emoticons</a>', $p['alt'])).'<form action="" method="post" id="add"><div class="admin-panel">'.theme('title', 'Edit Emoticon').theme('start_content').'<table class="admin-table">
  <tr><td>Emoticon Code:</td><td><input type="text" name="code" value="' . (errors() ? $_POST['code'] : $p['code']) . '" class="required"/></td></tr>
  <tr><td>Image:</td><td><select name="image" class="required" id="image">';
      $dir = 'images/emoticons/';
      if (is_dir($dir)) {
          if ($dh = opendir($dir)) {
              while (($file = readdir($dh)) !== false) {
                  if ($file != "." && $file != ".." && $file != 'index.php') {
                      $theme['body'] .= '<option value="' . $file . '"' . (!errors() && $p['image'] == $file ? 'selected="selected"' : (errors() && $_POST['image'] == $file ? 'selected="selected"' : '')) . '>' . $file . '</option>';
                  }
              }
          }
          closedir($dh);
      }
      $theme['body'] .= '</select></td></tr><tr><td>Emoticon Preview:</td><td><img src="images/emoticons/' . (errors() ? $_POST['image'] : $p['image']) . '" id="preview" alt="none selected"/></td></tr>
  <tr><td>Emoticon Text:</td><td><input type="text" name="text" value="' . (errors() ? $_POST['text'] : $p['alt']) . '" class="required"/></td></tr><tr><td align="center" colspan="2"><input type="submit" value="Update Emoticon" name="Submit" /></td></tr></table>'.theme('end_content').'</div></form>';
  }
  if (isset($_GET['type']) && $_GET['type'] == 'delete') {
      $check = call('deleteemoticon', $_GET['id']);
      if ($check == true && !errors()) {
          $_SESSION['update'] = 'The emoticon has been deleted';
          session_write_close();
          call('redirect', 'index.php?act=admin&opt=emoticons&'.$authid);
      }
  }
?>