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

   Added Language - 01/06/09 - Paul Wratt
*/
function getthemesettings() {
	global $settings;
	$themeid = call('sql_query', "SELECT theme_id FROM themes WHERE folder = '" . $settings['site_theme'] . "'", 'cache');
	$themequery = call('sql_query', "SELECT * FROM theme_settings WHERE theme_id = '" . @$themeid[0]['theme_id'] . "'", 'cache');
	$themesettings = array();
	foreach($themequery as $settingrow) {
		$themesettings[$settingrow['variable']] = $settingrow['value'];
	}
	$settings = array_merge($settings, $themesettings);
	return $settings;
}
?>