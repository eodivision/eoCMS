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

   Added Language - 02/06/09 - Paul Wratt
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$title = $THEME_LANG["title"];
$dir = 'themes/';
if(!isset($_GET['type']) || $_GET['type'] == null || $_GET['type'] == 'preview') {
	if (isset($_POST['update'])) {
		$check = call('updatesitetheme', $_POST['site_theme'], $_POST['theme_reset']);
		if ($check == true && !errors()) {
			$_SESSION['update'] = $THEME_LANG["added"];
			  session_write_close();
# redirect so there theme changes if they have overwrote everyones theme
			  call('redirect', 'index.php?act=admin&opt=themes&'.$authid);
		}
	}
	$head = '<script type="text/javascript">
$(document).ready(function() {
<!--						   
	$("a[id=site_preview]").attr("href", "index.php?act=admin&opt=themes&type=preview&theme="+$("select[name=site_theme] option:selected").text() +"&'.$authid.'");
	if($("#theme_reset").val() != "")
		$("a[id=site_preview_reset]").attr("href", "index.php?act=admin&opt=themes&type=preview&theme="+$("select[name=theme_reset] option:selected").text() +"&'.$authid.'");
	$("#site_theme").change(function() {
		$("a[id=site_preview]").attr("href", "index.php?act=admin&opt=themes&type=preview&theme="+this.options[this.selectedIndex].text+"&'.$authid.'");
	});
	$("#theme_reset").change(function() {
		if($("#theme_reset").val() != "")
			$("a[id=site_preview_reset]").attr("href", "index.php?act=admin&opt=themes&type=preview&theme="+this.options[this.selectedIndex].text+"&'.$authid.'");
		else
			$("a[id=site_preview_reset]").attr("href", "");
	});
// -->
});
</script>';
$body = '<form action="" method="post"><div class="admin-panel">'.theme('title', $THEME_LANG["theme_settings"]).theme('start_content').'<table class="admin-table">
  <tr>
	  <td>' . $THEME_LANG["overall_site_theme"] . ':</td>
	  <td><select name="site_theme" id="site_theme">';
	$themes = call('sql_query', "SELECT * FROM themes", 'cache');
	if(count($themes)==0) $themes[] = array('theme_name'=>$THEME_LANG["opt_no_site_theme"], 'folder'=>'');
	foreach($themes as $theme) {
		$body.='
		<option value="' . $theme['folder'] . '">' . $theme['theme_name'] . '</option>	';
	}
	$body.='
		</select> <a id="site_preview" href="">' . $THEME_LANG["preview"] . '</a></td>
	</tr>
	<tr>
	  <td>' . $THEME_LANG["reset_everyone"] . ':</td>
	  <td><select name="theme_reset" id="theme_reset">
		<option value="">' . $THEME_LANG["opt_keep_current"] . '</option>';
	$themes = call('sql_query', "SELECT * FROM themes", 'cache');
	foreach($themes as $theme) {
		$body.='
		<option value="' . $theme['folder'] . '">' . $theme['theme_name'] . '</option>	';
	}
	$body.='</select> <a id="site_preview_reset" href="">' . $THEME_LANG["preview"] . '</a></td>
	</tr>
	<tr>
	  <td  align="center" colspan="2"><a href="http://eocms.com" target="_blank">' . $THEME_LANG["check_for_new_themes"] . '<br />
		mods.eocms.com</a></td>
	</tr>
	<tr>
	  <td  align="center" colspan="2"><input type="submit" name="update" value=" ' . $THEME_LANG["btn_update"] . ' " /> <input type="reset" name="Reset" value=" ' . $THEME_LANG["btn_reset"] . ' " /></td>
	</tr>
</table>'.theme('end_content').'</div>
</form><br />
<div class="admin-panel2">'.theme('title', $THEME_LANG["available_themes"]).theme('start_content').'<table class="admin-table2">';
	$sql2 = call('sql_query', "SELECT folder FROM themes");
	if(call('sql_num_rows', $sql2)!=0) {
		$i=0;
		while($fetchfolder = call('sql_fetch_array',$sql2)) {
			$folderarray[$i] = $fetchfolder['folder'];
			$i++;
		}
	} else {
		$folderarray = array();
	}
	$folders = array();
	$default_folder = false;
	if(is_dir($dir)){
		if($dh=opendir($dir)){
			while(($file=readdir($dh))!==false){
				if(filetype($dir.$file)=="dir" && strstr($file,".")!=$file && strstr($file,"_")!=$file && !in_array($file, $folderarray)){
					if($file=='default')
						$default_folder = true;
					else
						$folders[] = $file;
				}
			}
		}
		closedir($dh);
	}
	natcasesort($folders);
	if($default_folder) array_unshift($folders, 'default');
	$themes = 0;
	foreach($folders as $folder) {
		if(file_exists($dir.$folder.'/theme-info.php')) {
			include($dir.$folder.'/theme-info.php');
			if(isset($theme)) {
				$themes++;
				$body.='
		<tr>
		  <td rowspan="2" width="140" height="140" ><img src="' . $dir . $folder . '/images/' . $theme['preview'] . '" alt="' . $theme['name'] . '" /></td>
		  <td valign="top"><div class="subtitlebg">'.$theme['name'].'</div>
			' . $theme['description'] . '<br />
			<br />
			' . $THEME_LANG["author"] . ': '.(isset($theme['site']) && !empty($theme['site']) ? '<a href="' . $theme['site'] . '" target="_blank">' . $theme['author'] . '</a>' : $theme['author']).' ' . (!empty($theme['email']) ? call('hide_email', $theme['email'], '', 'author-contact-link') : '') . '</td>
		</tr>
		<tr>
		  <td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;type=preview&amp;theme=' . $folder . '&amp;'.$authid.'">' . $THEME_LANG["preview"] . '</a>&nbsp;|&nbsp;<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;type=install&amp;folder=' . $folder . '&amp;'.$authid.'">' . $THEME_LANG["install"] . '</a></td>
		</tr>';
			}
		}
		//unset theme-info variables to prevent confliction and showing old themes not compatible
		unset($theme);
	}
	if($themes==0) {
		$body .= '
	<tr>
	  <td>No themes to install</td>
	</tr>
	<tr>
	  <td valign="top"><div class="subtitlebg">'.$THEME_LANG["check_for_new_themes"].'</div>
		<br />
		<a href="http://eocms.com/" target="_blank">eoCMS.com</a></td>
	</tr>';
	}
	$body.='
</table>'.theme('end_content').'</div>
<br />
<div class="admin-panel2">'.theme('title', $THEME_LANG["current_themes"]).theme('start_content').'<table class="admin-table2">';
	$themes = 0;
	$sql2 = call('sql_query', "SELECT * FROM themes");
	while($r = call('sql_fetch_array', $sql2)) {
		include($dir . $r['folder'] . '/theme-info.php');
		$themes++;
		$body .= '
	<tr>
	  <td rowspan="2" width="140" height="140" ><img src="' . $dir . $r['folder'] . '/images/' . $r['theme_preview'] . '" alt="' . $r['theme_name'] . '" /></td>
	  <td valign="top"><div class="subtitlebg">' . $r['theme_name'] . '</div>
		' . $theme['description'] . '<br />
		<br />
		' . $THEME_LANG["author"] . ': <a href="' . $r['author_site'] . '" target="_blank">' . $r['theme_author'] . '</a> ' . (!empty($r['author_email']) ? call('hide_email', $r['author_email'], '', 'author-contact-link') : '') . '</td>
	</tr>
	<tr>
	  <td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;type=edit&amp;themeid=' . $r['theme_id'] . '&amp;'.$authid.'">' . $THEME_LANG["edit_settings"] . '</a>&nbsp;|&nbsp;<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;type=preview&amp;theme=' . $r['folder'] . '&amp;'.$authid.'">' . $THEME_LANG["preview"] . '</a>' . ($i>1 ? '&nbsp;|&nbsp;<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;type=delete&amp;themeid=' . $r['theme_id'] . '&amp;'.$authid.'">' . $THEME_LANG["delete"] . '</a>' : '') . '</td>
	</tr>';
	}
	if($themes==0) {
		include($dir . 'default/theme-info.php');
		$body .= '
	<tr>
	  <td colspan="2">' . $THEME_LANG["no_themes_installed"] . '</td>
	</tr>
	  <td rowspan="2" width="140" height="140" ><img src="' . $dir . 'default/images/' . $theme_preview . '" alt="' . $theme_name . '" /></td>
	  <td valign="top"><div class="subtitlebg">' . $theme_name . '</div>
		<br />
		' . $theme_description . '<br />
		<br />
		' . $THEME_LANG["author"] . ': <a href="' . $theme_site . '" target="_blank">' . $theme_author . '</a> ' . (!empty($theme_email) ? call('hide_email', $theme_email, '', 'author-contact-link') : '') . '</td>
	</tr>
	<tr>
	  <td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;type=install&amp;folder=default&amp;'.$authid.'">' . $THEME_LANG["set_as_default"] . '</a></td>
	</tr>';
	}
	$body.='
</table>'.theme('end_content').'</div>';
}
if(isset($_GET['type']) && $_GET['type']=='install') {
	include($dir . $_GET['folder'] . '/theme-info.php');
	if($_POST) {
		$check = call('installtheme', $_GET['folder'], $_POST['visible']);
		if($check==true && !errors()) {
			$_SESSION['update'] = '"' . $theme['name'] . '" ' . $THEME_LANG["has_been_insalled"];
			session_write_close();
			call('redirect', 'index.php?act=admin&opt=themes&'.$authid);
		}
	}
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;'.$authid.'">Themes</a>', 'Install', $theme['name'] )).'<form action="" method="post"><div class="admin-panel">'.theme('title', $THEME_LANG["install_theme"].' "'.$theme['name'].'"').theme('start_content').'<table class="admin-table">
	<tr>
	  <td colspan="2">' . $THEME_LANG["allow_membergroups"] . ':</td>
	</tr>';
	$sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while ($m = call('sql_fetch_array', $sql2)) {
		if($m['membergroup_id']!=1) {
			$body .= '<tr><td>
		<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="themes_' . $m['membergroup_id'] . '"';
			if ($m['membergroup_id']!=1 || $m['membergroup_id']>5) {
				$body .= ' checked="checked"';
			}
			$body .= '/></td><td><label for="themes_' . $m['membergroup_id'] . '">' . $m['name'] . '</label></td></tr>';
		}
	}
	$body .= '</td>
	</tr>
	<tr>
	  <td  align="center" colspan="2"><input type="submit" name="install" value=" ' . $THEME_LANG["btn_install"] . ' " /> <input type="reset" name="Reset" value=" ' . $THEME_LANG["btn_reset"] . ' " /></td>
	</tr>
</table>'.theme('end_content').'</div>
</form>';
}
if(isset($_GET['type']) && $_GET['type']=='edit') {
	$sql = call('sql_query', "SELECT * FROM themes WHERE theme_id = '" . $_GET['themeid'] . "'");
	$p = call('sql_fetch_array', $sql);
	$sqlsettings = call('sql_query', "SELECT * FROM theme_settings WHERE theme_id = '" . $_GET['themeid'] . "'");
	$s = array();
	while($i = call('sql_fetch_array', $sqlsettings)) {
		$s[$i['variable']] = $i['value'];	
	}
	include($dir . $p['folder'] . '/theme-info.php');
	if($_POST) {
		$check = call('updatethemes', htmlentities($_POST['footer']), $_POST['logo'], $_POST['left'], $_POST['right'], $_POST['upper'], $_POST['lower'], $_POST['visible'], $_GET['themeid']);
	} else {
		$check = false;
	}
	if($check==true && !errors()) {
		$_SESSION['update'] = $THEME_LANG["updated"];
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=themes&'.$authid);
	}
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;'.$authid.'">Themes</a>', $theme['name'] )).'<form action="" method="post"><div class="admin-panel">'.theme('title', '"' . $theme['name'] . '" ' . $THEME_LANG["edit_theme_settings"]).theme('start_content').'<table class="admin-table2">
  <tr>
	  <td colspan="2">' . $THEME_LANG["allow_membergroups"] . ':<br/>';
	$sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while ($m = call('sql_fetch_array', $sql2)) {
		if($m['membergroup_id'] != 1) {
			$body .= '
		<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="topics_' . $m['membergroup_id'] . '"';
			if (call('visiblecheck', $m['membergroup_id'], $p['theme_visibility'])) {
				$body .= 'checked="checked"';
			}
			$body .= '/><label for="topics_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
		}
	}
	$body .= '</td>
	</tr>
	<tr>
	  <td colspan="2">' . $THEME_LANG["footer"] . ':</td>
	</tr>
	<tr>
	  <td colspan="2"><textarea rows="5" cols="50" name="footer">' . (errors() ? htmlspecialchars_decode($_POST['footer'], ENT_QUOTES) : htmlspecialchars_decode($s['footer'], ENT_QUOTES)) . '</textarea></td>
	 </tr>
	  <td>' . $THEME_LANG["logo_url"] . ':</td>
	  <td><input type="text" name="logo" value="' . (errors() ? $_POST['logo'] : $s['logo']) . '" /></td>
	</tr>
	<tr>
	  <td class="admin-subtitlebg" colspan="2" align="center">' . $THEME_LANG["panel_exclusions"] . '</td>
	</tr>
	<tr>
	  <td colspan="2">' . $THEME_LANG["example"] . ': forum, viewtopic</td>
	</tr>
	<tr>
	  <td>' . $THEME_LANG["exclude_left"] . ':</td>
	  <td><textarea rows="5" cols="30" name="left">' . ((errors() || $check==true) ? $_POST['left'] : $s['exclude_left']) . '</textarea></td>
	</tr>
	<tr>
	  <td>' . $THEME_LANG["exclude_right"] . ':</td>
	  <td><textarea rows="5" cols="30" name="right">' . ((errors() || $check==true) ? $_POST['right'] : $s['exclude_right']) . '</textarea></td>
	</tr>
	<tr>
	  <td>' . $THEME_LANG["exclude_upper"] . ':</td>
	  <td><textarea rows="5" cols="30" name="upper">' . ((errors() || $check==true) ? $_POST['upper'] : $s['exclude_upper']) . '</textarea></td>
	</tr>
	<tr>
	  <td>' . $THEME_LANG["exclude_lower"] . ':</td>
	  <td><textarea rows="5" cols="30" name="lower">' . ((errors() || $check==true) ? $_POST['lower'] : $s['exclude_lower']) . '</textarea></td>
	</tr>
	<tr>
	  <td align="center" colspan="2"><input type="submit" name="update" value=" ' . $THEME_LANG["btn_update"] . ' " /> <input type="reset" name="Reset" value=" ' . $THEME_LANG["btn_reset"] . ' " /></td>
	</tr>
</table>'.theme('end_content').'</div>
</form>';
}
if(isset($_GET['type']) && $_GET['type']=='delete') {
# check to make sure this isnt the only theme installed on the site
	$sql = call('sql_query', "SELECT * FROM themes");
	if(call('sql_num_rows', $sql)==1) {
# uh oh looks like it is, someone has been playing with the URLs
		$error[] = $THEME_LANG["e_not_deleted"];
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=themes&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM themes WHERE theme_id = '" . $_GET['themeid'] . "'");
	$p = call('sql_fetch_array', $sql);
	include($dir . $p['folder'] . '/theme-info.php');
	if($_POST) {
		$check = call('deletetheme', $_GET['themeid'], $_POST['reset']);
		if($check==true && !errors()) {
			$_SESSION['update'] = $theme['name'] . ' ' . $THEME_LANG["theme_deleted"];
			session_write_close();
			call('redirect', 'index.php?act=admin&opt=themes&'.$authid);
		}
	}
	$body = '<form action="" method="post">
<table align="center" cellspacing="0" cellpadding="0">
  <tr class="titlebg">
    <td class="titlebg-left-img" align="center">"' . $theme['name'] . '" ' . $THEME_LANG["delete_theme"] . '</td>
  </tr>
  <tr>
    <td><table align="center" class="content" width="100%">
	<tr>
	  <td>' . $THEME_LANG["delete_theme"] . ':</td>
	  <td><select name="reset">';
	$themes = call('sql_query', "SELECT * FROM themes", 'cache');
	foreach($themes as $theme) {
		if($theme['theme_id']!=$_GET['themeid']) {
			$body.='
		<option value="' . $theme['folder'] . '">' . $theme['theme_name'] . '</option>';
		}
	}
	$body.='</select></td>
	</tr>
	<tr>
	  <td  align="center" colspan="2"><input type="submit" name="delete" value=" ' . $THEME_LANG["bnt_delete"] . ' " /></td>
	</tr>
    </table></td>
  </tr>
</table>
</form>';
}
?>