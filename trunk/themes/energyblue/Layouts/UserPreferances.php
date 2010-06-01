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

    03/06/09 - Language added - Paul Wratt
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$title = $USERPREFERANCES_LANG["title"];
$check = false;
if ($user['guest']) {
	$error_die[] = $USERPREFERANCES_LANG["error_die"];
}
if ($_POST) {
	$check = call('updateadminpreferances', $user['id'], $_POST['menu'], md5($_POST['currentpassword']), $_POST['token']);
}
if ($check == true && !errors()) {
	$head = '<META HTTP-EQUIV="refresh" content="3;URL=' . $settings['site_url'] . '/index.php?act=userpreferances">';
	$body = theme('title', "Success") . theme('start_content') . 'Profile Updated!' . theme('end_content');
} else {
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#admin").validate();
	$("#menu").change(function() {
		location.href="'.$settings['site_url'].'/index.php?act=userpreferances&select="+$("#menu").val();
	});
});
</script>';
	$body = theme('title', $title) . theme('start_content') . '<form action="" method="post" id="admin">
<table>
	<tr>
			<td colspan="2">'.theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=editprofile">Edit Profile</a>', '<a href="'.$settings['site_url'].'/index.php?act=userpreferances">Preferances</a>')).'</td>
	</tr>
  <tr>
    <td>' . $USERPREFERANCES_LANG["choose_admin_menu"] . ':</td>
    <td><select name="menu" id="menu" class="required">';
	$get_selected = '';
	$selected = $user['admin_menu'];
	$select = isset($_GET['select']) ? $_GET['select'] : $selected ;
	$dir = opendir('Layouts/Admin/Menu');
	while (($file=readdir($dir))!==false) {
		if (strstr($file,'.')!=$file) {
			if (!defined('FINAL') || (defined('FINAL') &&  strstr($file,'_')!=$file)) {
				$name = str_replace('.php', '', $file);
				$body .= '
	<option value="' . $file . '"';
				if ($select==$file) {
					$body .= ' selected="selected"';
				}
				$body .= '>' . $name . '</option>';
			}
		}
	}
	closedir($dir);
	$folder = 'themes/_Admin/';
	$dir = opendir($folder);
	while (false!==($file=readdir($dir))) {
		if (filetype($folder.$file)=="dir" && strstr($file,'.')!=$file) {
			if (!defined('FINAL') || (defined('FINAL') &&  strstr($file,'_')!=$file)) {
				$body .= '
	<option value="' . $file . '"';
				if ($select==$file) {
					$body .= ' selected="selected"';
					$get_selected = $select;
				}
				$body .= '>' . $file . '</option>';
			}
		}
	}
	closedir($dir);
	$body .= '</select></td>
  </tr>';
	if($get_selected!='') {
		include($folder . $get_selected .'/theme-info.php');
		foreach($theme_options as $key=>$list) {
			$body .= '
  <tr>
    <td align=right>' . str_replace('_',' ',$key) . ':</td>
    <td>';
			if (is_array($list)) {
				$body .= '
      <select name="' . $key . '" class="required">';
				foreach($list as $value){
					$body .= '
	<option value="' . $value . '">' . $value . '</option>';

				}
				$body .= '
      </select>';
			}
			$body .= '</td>
  </tr>';
		}
	}
	$sql = call('sql_query', "SELECT settings, folder FROM plugins WHERE active = '1'", 'cache');
	if(count($sql) != 0) {
		foreach($sql as $p) {
			if(!empty($p['settings'])) {
				if(file_exists(IN_PATH.'plugins/'.$p['folder'].'/Layouts/'.$p['settings_layout']))
					include IN_PATH.'plugins/'.$p['folder'].'/Layouts/'.$p['settings_layout'];
			}
		}
	}
	$body .= '
  <tr>
    <td>' . $USERPREFERANCES_LANG["current_password"] . ': <br />
	<span class="small-text">' . $USERPREFERANCES_LANG["required_for_security"] . '</span></td>
    <td><input name="currentpassword" type="password" class="required"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center">' . call('form_token') . '<input type="submit" name="update" value=" ' . $USERPREFERANCES_LANG["btn_update"] . ' "/> <input type="button" name="cancel" value=" ' . $USERPREFERANCES_LANG["btn_cancel"] . ' " onClick="location.href=\'index.php?act=adminpreferances\';" /></td></tr>
</table>
</form>' . theme('end_content');
}
?>