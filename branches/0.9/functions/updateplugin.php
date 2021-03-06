<?php
/*  eoCMS � 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
function updateplugin($id) {
	global $user, $settings, $error, $error_die;
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if($id == '' ){
		$error[] = 'No Plugin was selected';
		return false;
	}
	$sql = call('sql_query', "SELECT * FROM plugins WHERE id = '$id'");
	$p = call('sql_fetch_array', $sql);
	include('./Plugins/' . $p['folder'] . '/plugin-info.php');
	//serialize any arrays
	if(isset($plugin['layouts']) && !empty($plugin['layouts']) && is_array($plugin['layouts']))
		$plugin['layouts'] = serialize($plugin['layouts']);
	else
		$plugin['layouts'] = '';
	if(isset($plugin['admin']['layouts']) && !empty($$plugin['admin']['layouts']) && is_array($plugin['admin']['layouts']))
		$plugin['admin']['layouts'] = serialize($plugin['admin']['layouts']);
	else
		$plugin['admin']['layouts'] = '';
	if(isset($plugin['panels']) && !empty($plugin['panels']) && is_array($plugin['panels']))
		$plugin['panels'] = serialize($plugin['panels']);
	else
		$plugin['panels'] = '';
	if(!isset($plugin['settings']))
		$plugin['settings'] = '';
	if(!isset($plugin['everypage']))
		$plugin['everypage'] = '';
	if(!empty($plugin['layout_include']) && is_array($plugin['layout_include']) && isset($plugin['layout_include']))
		$plugin['layout_include'] = serialize($plugin['layout_include']);
	else
		$plugin['layout_include'] = '';
	//eoCMS version check
	if(version_compare($settings['version'], $plugin['eocms_version']) === -1) {
		$error[] = 'The plugin you attempted to install requires a newer version of eoCMS';
		return false;
	}
	if(!errors()) {
		$pluginupdate = call('sql_query', "UPDATE plugins SET name = '".$plugin['name']."', version = '".$plugin['version']."', eocms_version = '".$plugin['eocms_version']."', layout = '".$plugin['layout']."', layout_include =  '".$plugin['layout_include']."', admin_control = '".$plugin['admin']['control']."', admin_layouts = '".$plugin['admin']['layouts']."', author = '".$plugin['author']['name']."', author_site = '".$plugin['author']['site']."', panels = '".$plugin['panels']."', settings = '".$plugin['settings']."', everypage = '".$plugin['everypage']."' WHERE id = '$id'");
		if($pluginupdate) {
			//check if there are any tables that need creating
			if(isset($plugin['update']['create'])) {
				//run them through a function to create the query
				$sql = call('create_sql', $plugin['update']['create']);
				foreach($sql as $query) {
					call('sql_query', $query);	
				}
			}
			if(isset($plugin['update']['alter'])) {
				//run them through a function to create the query
				$sql = call('alter_sql', $plugin['update']['alter']);
				foreach($sql as $query) {
					call('sql_query', $query);	
				}
			}
			return true;
		}
	}
}
?>