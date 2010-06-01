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
if(!(defined("IN_ECMS"))) die("Hacking Attempt...");
$pluginsql = call('sql_query', "SELECT * FROM plugins WHERE active = '1' AND id = '" . $_GET['id'] . "'");
if(call('sql_num_rows', $pluginsql) != 0) {
	while($pluginfetch = call('sql_fetch_array',$pluginsql)) {
		if(file_exists('plugins/' . $pluginfetch['folder'] . '/Layouts/' . $pluginfetch['layout'])) {
			$plugin = array(
				'url' => 'index.php?act=plugin&id='.$_GET['id'],
				'site_url' => $settings['site_url'].'/index.php?act=plugin&id='.$_GET['id'],
				'id' => $_GET['id']
			);
			include('plugins/' . $pluginfetch['folder'] . '/Layouts/' . $pluginfetch['layout']);
		}
		else
			call('redirect', 'index.php');
	}
} else //plugin doesnt exist, redirect back to the index
	call('redirect', 'index.php');
?>