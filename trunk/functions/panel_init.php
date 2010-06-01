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

function panel_init() {
	global $lang_path, $settings, $theme;
	//Grab all the panels from the database
	$panelquery = call('sql_query', 'SELECT * FROM panels WHERE online="1" ORDER BY item_order ASC', 'cache');
	//store the excluded pages
	$theme['panels']['exclude'] = array('left' => explode(", ", $settings['exclude_left']), 
							  'right' => explode(", ", $settings['exclude_right']),
							  'upper' => explode(", ", $settings['exclude_upper']),
							  'lower' => explode(", ", $settings['exclude_lower']));
	//clean the query string
	$querystring = '?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
	//loop through the data
	foreach($panelquery as $fetch) {
		//assign all the data to an array and process it so as little as possible non-html code is required within the theme functions
		$panelstuff = array('id' => $fetch['id'], 
							'rank' => $fetch['rank'],
							'all_pages' => $fetch['all_pages'],
							'file' => (!empty($fetch['file']) ? (strpos($panel['file'], 'plugins/') !== false ?  $fetch['file'].'/index.php' : 'panels/' . $fetch['file'] . '/index.php') : false),
							'lang' => (!empty($fetch['file']) ? (strpos($panel['file'], 'plugins/') !== false ?  $fetch['file'] .'/languages/' . $lang_path . '.php' : 'panels/' . $fetch['file'] .'/languages/' . $lang_path . '.php') : false),
							'name' => $fetch['panelname'],
							'content' => (empty($panel['type']) || $panel['type'] == 'html' ? html_entity_decode($fetch['panelcontent'], ENT_QUOTES) : (empty($panel['type']) && $panel['type'] == 'php') ? eval(html_entity_decode($fetch['panelcontent'], ENT_QUOTES)) : ''), //check if its PHP or HTML, decode accordingly
							'item_order' => $fetch['item_order'],
							'type' => $fetch['type']);
		//only add panels which they have permission to see
		if(call('visiblecheck', $user['membergroup_id'], $fetch['rank'])) {
			//show panels only on the correct pages
			if($fetch['all_pages'] == '1' || ($_GET['act'] == '' && $fetch['all_pages'] == '0')) {
				//check which side it belongs to and add it in the correct array
				//also check if panels are to be displayed on this page
				if($fetch['side'] == 'left' && ((strstr($settings['exclude_left'], '?') && in_array($querystring, $theme['panels']['exclude']['left']) === false) && in_array($_GET['act'], $theme['panels']['exclude']['left'] === false))) {
					$theme['panel']['left'][] = $panelstuff;
				} else
					$theme['panel']['left'] = false;
				if($fetch['side'] == 'right' && ((strstr($settings['exclude_right'], '?') && in_array($querystring, $theme['panels']['exclude']['right']) === false) && in_array($_GET['act'], $theme['panels']['exclude']['right'] === false))) {
					$theme['panel']['right'][] = $panelstuff;
				} else
					$theme['panel']['right'] = false;
				if($fetch['side'] == 'upper' && ((strstr($settings['exclude_upper'], '?') && in_array($querystring, $theme['panels']['exclude']['upper']) === false) && in_array($_GET['act'], $theme['panels']['exclude']['upper'] === false))) {
					$theme['panel']['upper'][] = $panelstuff;
				} else
					$theme['panel']['upper'] = false;
				if($fetch['side'] == 'lower' && ((strstr($settings['exclude_lower'], '?') && in_array($querystring, $theme['panels']['exclude']['lower']) === false) && in_array($_GET['act'], $theme['panels']['exclude']['lower'] === false))) {
					$theme['panel']['lower'][] = $panelstuff;
				} else
					$theme['panel']['lower'] = false;
			}
		}
	}
	//if the query is empty, lets set them all to false
	if(!isset($theme['panel']['left']))
		$theme['panel']['left'] = false;
	if(!isset($theme['panel']['right']))
		$theme['panel']['right'] = false;
	if(!isset($theme['panel']['upper']))
		$theme['panel']['upper'] = false;
	if(!isset($theme['panel']['lower']))
		$theme['panel']['lower'] = false;
}
?>