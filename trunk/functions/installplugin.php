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
function installplugin($folder) {
	global $user, $settings, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($folder)){
		$error[] = 'No Plugin was selected';
		return false;
	}
	//check to make sure the plugin-info file exists
	if(!empty($folder) && file_exists('./plugins/'.$folder.'/plugin-info.php')) {
		//set $plugin_install to true, so other code may be run during installation
		$plugin_install = true;
		include('./plugins/' . $folder . '/plugin-info.php');
		//serialize any arrays
		if(!empty($plugin['layouts']) && is_array($plugin['layouts']) && isset($plugin['layouts']))
			$plugin['layouts'] = serialize($plugin['layouts']);
		else
			$plugin['layouts'] = '';
		if(!empty($$plugin['admin']['layouts']) && is_array($plugin['admin']['layouts']) && isset($plugin['admin']['layouts']))
			$plugin['admin']['layouts'] = serialize($plugin['admin']['layouts']);
		else
			$plugin['admin']['layouts'] = '';
		if(!empty($plugin['panels']) && is_array($plugin['panels']) && isset($plugin['panels']))
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
	} else {
		$error[] = 'plugin-info file does not exist for this plugin';
		return false;
	}
	if(!errors()) {
		$install = call('sql_query', "INSERT INTO plugins (name, version, eocms_version, layout, layout_include, admin_control, admin_layouts, active, folder, author, author_site, panels, settings, everypage) VALUES ('".$plugin['name']."', '".$plugin['version']."', '".$plugin['eocms_version']."', '".$plugin['layout']."', '".$plugin['layout_include']."', '".$plugin['admin']['control']."', '".$plugin['admin']['layouts']."', '0', '$folder', '".$plugin['author']['name']."', '".$plugin['author']['site']."', '".$plugin['panels']."', '".$plugin['settings']."', '".$plugin['everypage']."')");
		if($install) {
			//check if there are any tables that need creating
			if(isset($plugin['tables']['create'])) {
				//run them through a function to create the query
				$sql = call('create_sql', $plugin['tables']['create']);
				foreach($sql as $query) {
					call('sql_query', $query);	
				}
			}
			//check if there is any data to insert into any tables
			if(isset($plugin['tables']['extra']) && is_array($plugin['tables']['extra'])) {
				//run them through a function to create the query
				foreach($plugin['tables']['extra'] as $query) {
					call('sql_query', $query);	
				}
			}
			return true;
		}
	}
}
?>