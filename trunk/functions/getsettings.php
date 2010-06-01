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
function getsettings() {
	global $user;
	$settingquery = call('sql_query', "SELECT * FROM settings", 'cache');
	foreach($settingquery as $settingrow) {
		$settings[$settingrow['variable']] = $settingrow['value'];
	}
	if (!$user['guest'] && !empty($user['theme']))
		$settings['site_theme'] = $user['theme'];
	return $settings;
}
?>