<?php
/*
Date Created: 21st November 2008
###########
#Changelog#
###########
===============================================================================
=v0.2 - Added new window, popups and append authid - 25th May 2009			  =
===============================================================================
=v0.3 - Updated plugin-info to new system and moved the panel - 19th July 2009=
===============================================================================
Copyright info below
*/
//plugin name
$plugin['name'] = 'Navigation Panel';
//plugin version. Increase number to make the system know if it is out of date
$plugin['version'] = '0.3';
//minimum version of eoCMS required to install/use the plugin
$plugin['eocms_version'] = '0.9.0';
//Layout to be included when plugin accessed
$plugin['layout'] = '';
//Layouts to be included on existing $_GET['acts'], before or after
//example array('before' => array('profile' => 'Beforeme.php', 'forum' =>'beforeme2.php'), 'after' => array('viewtopic' => 'Afterme.php', '' => 'afterme2.php'))
//files included will be located in Plugins/Plugin folder/Layouts
$plugin['layout_include'] = array();
//the admin control file which will be included
$plugin['admin']['control'] = 'Admin.php';
//list of the different admin layouts, means the above layout specified can work similiarly to the index.php file of eoCMS using sa as the $_GET
$plugin['admin']['layouts'] = '';
//who created the plugin
$plugin['author']['name'] = 'confuser';
//author's site
$plugin['author']['site'] = 'http://eocms.com';
//Description to show in plugins admin area
$plugin['description']['short'] = 'Editable panel displaying links. Panel version of the Links admin area';
//Long description to show when the plugin is clicked
$plugin['description']['long']  = 'The first plugin ever built for eoCMS, like a "quick links" list, where items can be regular urls, menu links, or any other eoCMS internal url';
//create table
$plugin['tables']['create'] = array('navigation_menu' => array(
	'columns' => array('id', 'name', 'link', 'item_order', 'rank', 'authid', 'window', 'height', 'width'),
	'types' => array('int(225)', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'tinyint(1)', 'text', 'int(5)', 'int(5)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => 'item_order'
	)
));
//List of panels included with the plugin, key is panel name to display in the drop down box in admin panel, value is the folder name
$plugin['panels'] = array('Navigation Panel' => 'navigational_panel');
//query to run if the plugin is out of date, in this case it will be run if the plugin version installed on their site is less than 0.3
$plugin['update']['alter'] = array('navigation_menu' => array(
										'add_columns' => array(
											'columns' => array('authid', 'window', 'height', 'width'), 
											'types' => array('tinyint(1)', 'text', 'int(5)', 'int(5)')
											)						  
										)
									);
//queries to run when the plugin is uninstalled
$plugin['uninstall'] = array('DROP TABLE navigation_menu');
?>