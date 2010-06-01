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

$theme['title'] = 'Plugins';

# unset previous pass info
if(isset($_GET['id']) && ($_GET['type']=="uninstall" || $_GET['type']=="activate" || $_GET['type']=="deactivate") && $_POST) { unset($_GET['type']); unset($_GET['id']); }

# 1.5 passes instead of 2 passes (above cleans url from below actions)
if(isset($_GET['type']) && $_GET['type']=='install') {
	$check = call('installplugin', $_GET['folder']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The plugin has been installed. Please activate it below to use it';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=plugins&'.$authid);
	}
}
if(isset($_GET['type']) && $_GET['type']=='uninstall') {
	$check = call('uninstallplugin', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The plugin has been uninstalled';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=plugins&'.$authid);
	}
}
if(isset($_GET['type']) && $_GET['type']=='activate') {
	$check = call('activateplugin', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The plugin has been activated';
	unset($_GET['type']);
	}
}
if(isset($_GET['type']) && $_GET['type']=='deactivate') {
	$check = call('deactivateplugin', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The plugin has been de-activated';
	}
	unset($_GET['type']);
}
if(isset($_GET['type']) && $_GET['type']=='update') {
	$check = call('updateplugin', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The plugin has been updated';
	}
	unset($_GET['type']);
}
if(isset($_GET['type']) && $_GET['type']=='admin') {
	$sql = call('sql_query', "SELECT folder, admin_layouts, admin_control FROM plugins WHERE id='" . $_GET['id'] . "'");
	if(call('sql_num_rows', $sql) != 0) {
		$pluginfetch = call('sql_fetch_array',$sql);
		if(!empty($pluginfetch['admin_control'])) {
			if(file_exists('plugins/' . $pluginfetch['folder'] . '/Layouts/' . $pluginfetch['admin_control'])) {
				include('./plugins/' . $pluginfetch['folder'] . '/Layouts/' . $pluginfetch['admin_control']);
			}
		}
		if(!empty($pluginfetch['admin_layouts'])) {
			$action = unserialize($pluginfetch['admin_layouts']);
			$receiver = $_GET['sa'];
			$isvalid = array_key_exists($receiver, $action);
			if ($isvalid) {
			if (file_exists('themes/' . $settings['site_theme'] . '/' . $action[$receiver]))
				include(IN_PATH.'themes/' . $settings['site_theme'] . '/' . $action[$receiver]);
			}
		}
	}
}
if(!isset($_GET['type']) || $_GET['type'] == null) {
	$sql = call('sql_query', "SELECT * FROM plugins");
	$sql2 = call('sql_query', "SELECT folder FROM plugins");
	if(call('sql_num_rows', $sql2) != 0) {
		$i=0;
		while($fetchfolder = call('sql_fetch_array',$sql2)) {
			$folderarray[$i] = $fetchfolder['folder'];
			$i++;
		}
	} else {
		$folderarray = array();
	}
	$theme['body'] ='<div class="admin-panel">'. theme('title', 'Install Plugins') . theme('start_content') . '<table class="admin-table">
	<tr>
	  <td>';
	$dir = 'plugins/';
	$plugins = 0;
	if(is_dir($dir)){
		if($dh = opendir($dir)){
			while(($file = readdir($dh)) !== false){
				if(filetype($dir . $file)=="dir"){
					if($file!="." && $file!=".." && !in_array($file, $folderarray) && $file['0']!=".") {
						if(file_exists($dir.$file.'/plugin-info.php')) {
							include($dir.$file.'/plugin-info.php');
							if(isset($plugin)) {
								$plugins++;
								$theme['body'] .= '<tr class="admin-subtitlebg">
											<td align="left">'.$plugin['name'].' by ';
											if(isset($plugin['author']['site']) && !empty($plugin['author']['site']))
												$theme['body'].= '<a href="'.$plugin['author']['site'].'">'.$plugin['author']['name'].'</a>';
											else
												$theme['body'] .= $plugin['author']['name'];
											$theme['body'] .='</td>
										</tr>
										<tr>
											<td align="left">'.$plugin['description']['short'].'</td>
										</tr>
										<tr>
											<td align="left"><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=plugins&amp;type=install&amp;folder='.$file.'&amp;'.$authid.'">Install</a></td>
										</tr>';
								//prevent confliction
								unset($plugin);
							}
						}
					}
				}
			}
		}
		closedir($dh);
	}
	if($plugins<=0){
		$theme['body'] .= '
		No new plugins available';
	}
	$theme['body'].='
</table>
'.theme('end_content').'</div>
</form>
<br />';
	$theme['body'] .= '
<div class="admin-panel">'.theme('title', 'Installed Plugins').theme('start_content').'<table class="admin-table">
  <tr>
    <td>Plugin Name</td>
    <td>Author</td>
    <td>Status</td>
  </tr>';
	if(call('sql_num_rows', $sql) == 0) {
		$theme['body'] .= '
  <tr>
    <td colspan=3>No plugins installed</td>
  </tr>';
	} else {
		while($p = call('sql_fetch_assoc', $sql)){
			include('plugins/'.$p['folder'].'/plugin-info.php');
			$status = ($p['active']=='1') ? '<a href="'.$settings['site_url'].'/index.php?act=admin&opt=plugins&type=deactivate&id=' . $p['id'] . '&amp;'.$authid.'" onclick="return confirm(\'Are you sure you want to deactivate this plugin? All features of this plugin will be disabled\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/online.png" alt="Active" /></a>' : '<a href="'.$settings['site_url'].'/index.php?act=admin&opt=plugins&type=activate&id=' . $p['id'] . '&amp;'.$authid.'"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/offline.png" alt="De-Activated" /></a>';
			$admin = (!empty($p['admin_control']) && $plugin['version'] == $p['version']) ? '<a href="'.$settings['site_url'].'/index.php?act=admin&opt=plugins&type=admin&id=' . $p['id'] . '&amp;'.$authid.'" title="Visit Plugin Admin">' . $p['name'] . '</a>' : $p['name'];
			$theme['body'].='
  <tr>
    <td>'.$admin.($plugin['version'] != $p['version'] ? ' <a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=plugins&amp;type=update&amp;id='.$p['id'].'&amp;'.$authid.'" class="error">Outdated Update now</a>!' : '').'</td>
    <td><a href="' . $p['author_site'] . '" target="_blank">' . $p['author'] . '</td>
    <td nowrap valign=middle>' . $status . ' <a href="'.$settings['site_url'].'/index.php?act=admin&opt=plugins&type=uninstall&id=' . $p['id'] . '&amp;'.$authid.'" title="Uninstall" onclick="return confirm(\'Are you sure you want to uninstall this plugin? All tables created will be dropped\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a></td></tr>';
		}
	}
	$theme['body'] .='</table>'.theme('end_content').'</div>';
}
?>