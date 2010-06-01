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

# START
if(!$user['admin_panel'] || !isset($_REQUEST['authid']) || AUTHID != $_REQUEST['authid']) {
# if not valid admin session, redirect
	call('redirect', 'index.php');
}
# set the theme output function
$theme['output'] = 'main_template_admin_ajax';
$theme['title'] = $ADMIN_LANG["title"];
# ACTIONS
# predefine ADMIN action mappings, required for language page
$a_action = array(
	'articles'          => 'Admin/Articles.php',
	'addboard'		    => 'admin/addboard.php',
	'addforumcat'		=> 'admin/addforumcat.php',
	'addlink'			=> 'admin/addlink.php',
	'ajax'			 	=> 'admin/ajax.php',
	'bans'              => 'Admin/Bans.php',
	'boards'			=> 'admin/boards.php',
	'clearcache'		=> 'admin/clearcache.php',
	'editboard'			=> 'admin/editboard.php',
	'editlink'			=> 'admin/editlink.php',
	'emoticons'         => 'Admin/Emoticons.php',
	'forum'             => 'Admin/Boards.php',
	'links'             => 'Admin/Links.php',
	'linkgenerator'		=> 'admin/linkgenerator.php',
	'mailsettings'		=> 'admin/mailsettings.php',
	'maintenance'		=> 'admin/maintenance.php',
	'members'           => 'Admin/members.php',
	'miscellaneous'		=> 'admin/miscellaneous.php',
	'news'              => 'Admin/News.php',
	'pages'             => 'Admin/Pages.php',
	'panels'            => 'Admin/Panels.php',
	'permissions'       => 'Admin/Permissions.php',
	'plugins'           => 'Admin/Plugins.php',
	'registration'		=> 'admin/registration.php',
	'sitesettings'      => 'Admin/SiteSettings.php',
	'themes'            => 'Admin/Theme.php',
# default page action
	''                  => (isset($user['default'])? $user['default'] : (isset($settings['site_default'])? $settings['site_default'] : 'admin/admin.php' ))
);
# predefine form GET action on landing page
if(!isset($_REQUEST['opt'])) {
	$_REQUEST['opt'] = '';
}
$a_receiver = $_REQUEST['opt'];
$a_isvalid = array_key_exists($a_receiver, $a_action);

# LANGUAGE
# one per file name in above array
if($a_isvalid){
	$a_lang_file = $a_action[$a_receiver];
	$a_lang_array = strtoupper(str_replace('Admin/','',str_replace('.php','',$a_lang_file)));
}
# include actual ADMIN language files
@include_once('language/' . $lang_path . '/' . $a_lang_file);
# use english if there was an error
if(@eval('!is_array($'  .$a_lang_array . '_LANG)'))
	@include_once('language/en/' . $a_lang_file);
# developers warning
if(@eval('!is_array($' . $a_lang_array . '_LANG)') && !defined('FINAL'))
	echo 'WARNING: no file for "language/' . $lang_path . '/' . $a_lang_file . '" (check "en")';
# set the window id
$theme['window_id'] = (isset($_REQUEST['window']) ? $_REQUEST['window'] : null);
# PANES & PANELS
# default landing page
if($a_isvalid && file_exists(IN_PATH.'themes/'.$settings['site_theme'].'/blueprints/'.$a_lang_file)) {
# actual menu section, or subsection
		include(IN_PATH.'themes/'.$settings['site_theme'].'/blueprints/'.$a_lang_file);
} else {
# its does not exist so lets do a 404
	header("HTTP/1.0 404 Not Found");
}
# END
?>