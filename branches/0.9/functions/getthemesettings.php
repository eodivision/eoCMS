<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

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