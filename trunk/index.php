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
if(!defined('FINAL')) {
# for non-FINAL (DEV or SVN) release versions
	error_reporting(E_ALL);
}
# START
define("IN_ECMS", true);
define("IN_PATH", realpath('.') . '/');
# set caching of output to web server
ob_start();
# begin timing functions
$time = explode(' ', microtime());
$time = $time[1] + $time[0]; // return array
$begintime = $time; //define begin time

# INITIALIZE
# initial setup include, contains
#   config contents
#   user settings ($user array)
#   site settings ($settings array)
require_once 'framework.php';

# if you are running an upgraded system
if(!defined('COOKIE_LANG')) define('COOKIE_LANG', 'en');

//IE8 frame busting, well thats the only good thing it has :P
header('X-FRAME-OPTIONS: SAMEORIGIN');
// display the captcha, here as it needs call()
if(isset($_GET['act']) && $_GET['act'] == 'captcha') {
	if(!isset($_GET['audio'])) {
		$img = new securimage();
		$img->show();
	} else {
		$img = new Securimage();
		$img->audio_format = 'mp3';
		$img->setAudioPath(IN_PATH.'functions/securimage/audio/');
		$img->outputAudioFile();
	}
	die();
}
# ACTIONS
# predefine action mappings, required for language page
$action = array(
	'activate'          => 'blueprints/Activate.php',
	'admin'             => 'blueprints/admin.php',
	'ajax'              => 'blueprints/Ajax.php',
	'articles'          => 'blueprints/Articles.php',
	'editaccount'       => 'blueprints/EditProfileDetails.php',
	'editlook'          => 'blueprints/EditProfileLook.php',
	'editmiscellaneous' => 'blueprints/EditProfileMisc.php',
	'editpost'          => 'blueprints/EditPost.php',
	'editprofile'       => 'blueprints/EditProfile.php',
	'deletepm'          => 'blueprints/DeletePm.php',
	'feeds'             => 'blueprints/Feeds.php',
	'finduser'          => 'blueprints/UserSearch.php',
	'forum'             => 'blueprints/indexforum.php',
	'forgotpassword'    => 'blueprints/ForgotPassword.php',
	'login'             => 'blueprints/login.php',
	'logout'            => 'blueprints/Logout.php',
	'maintenance'       => 'blueprints/Maintenance.php',
	'modcp'             => 'blueprints/ModeratorCP.php',
	'news'              => 'blueprints/News.php',
	'newtopic'          => 'blueprints/NewTopic.php',
	'register'          => 'blueprints/Register.php',
	'reply'             => 'blueprints/Reply.php',
	'page'              => 'blueprints/Pages.php',
	'plugin'            => 'blueprints/Plugins.php',
	'pm'                => 'blueprints/pm.php',
	'profile'           => 'blueprints/Profile.php',
	'search'            => 'blueprints/Search.php',
	'sendpm'            => 'blueprints/SendPm.php',
	'trackip'           => 'blueprints/TrackIP.php',
	'userpreferances'   => 'blueprints/UserPreferances.php',
	'viewboard'         => 'blueprints/viewboard.php',
	'viewpm'            => 'blueprints/ViewPm.php',
	'viewtopic'         => 'blueprints/ViewTopic.php',
	'404'               => 'blueprints/404.php',
# default page action
	''                  => 'blueprints/home.php'
);
# predefine form GET action on landing page
#  and do setup if "perminant link" (permalink) found
if(!isset($_GET['act'])) {
	$_GET['act'] = '';
	if(!empty($_SERVER['QUERY_STRING'])) {
# eg permalinks:
# http://eocms.com/?perma-links == http://eocms.com/index.php?act=viewtopic&id=550
# http://eocms.com/?Blog_Roll_plugin#3753 == http://eocms.com/index.php?act=viewtopic&id=545#3753
		$permalink = call('findPermaLink',$_SERVER['QUERY_STRING']);
		if ($permalink!==false){
			$_GET['act'] = 'viewtopic';
			$_GET['id'] = $permalink['id'];
//			$_GET['page'] = $permalink['page']; #DEV: need to add pagination (?)
		}
	}
}
$receiver = $_GET['act'];
$isvalid = array_key_exists($receiver, $action);

# LANGUAGE
# one per file name in above array + functions
if($isvalid){
	$lang_file = str_replace('blueprints','',$action[$receiver]);
	$lang_array = strtoupper(str_replace('/','',str_replace('.php','',$lang_file)));
}else{
	$lang_file = str_replace('blueprints','',$action['404']);
	$lang_array = 'FOUROFOUR';
}
if(defined('COOKIE_LANG'))
	$lang_path = COOKIE_LANG;
if(isset($settings['site_lang']))
	$lang_path = $settings['site_lang'];
if(isset($user['lang']))
	$lang_path = $user['lang'];
if(isset($_GET['lang']))
	$lang_path = $_GET['lang'];
if(!isset($lang_path))
	$lang_path = 'en';
# include actual language files
include_once(IN_PATH.'language/' . $lang_path . '/' . $lang_file);
include_once(IN_PATH.'language/' . $lang_path . '/functions.php');
include_once(IN_PATH.'language/' . $lang_path . '/panel.php');
# use english if there was an error
if(!is_array('$'.$lang_array.'_LANG'))
	include_once(IN_PATH.'language/en' . $lang_file);
if(!isset($FUNCTIONS_LANG) || !is_array($FUNCTIONS_LANG))
	include_once(IN_PATH.'language/en/functions.php');
if(!isset($PANEL_LANG) || !is_array($PANEL_LANG))
	include_once(IN_PATH.'language/en/panel.php');
# developers warning
if(!is_array('$'.$lang_array.'_LANG') && !defined('FINAL') && !file_exists('language/' . $lang_path . $lang_file))
	echo 'WARNING: no file for "language/' . $lang_path . $lang_file . '" (check "en")';

# include actual ADMIN NEMU language files
@include_once('language/' . $lang_path . '/Admin/defaultmenu.php');
# use english if there was an error
if(!@is_array($ADMIN_MENU_LANG)) {
# developers warning
	if(!defined('FINAL'))
		echo 'WARNING: no file for "language/' . $lang_path . '/Admin/defaultmenu.php"';
	@include_once('language/en/Admin/defaultmenu.php');
}
# CHECKS
# check site mode
call('urlidcheck');
if(!$user['maintenance_access'])
	call('maintenance');
# check admin status
if(!$user['admin_panel'])
	call('banned');
call('usersonline');

# PLUGINS
# load installed plugins
$plugin = call('sql_query', "SELECT folder, everypage, layout_include FROM plugins WHERE active = '1'", 'cache');
#include any layouts that need to be included on a certain act before the content
foreach($plugin as $pluginfetch) {
	$pluginfetch['layout_include'] = unserialize($pluginfetch['layout_include']);
	if(!empty($pluginfetch['layout_include']) && isset($pluginfetch['layout_include']['before']) && is_array($pluginfetch['layout_include']['before'])) {
		foreach($pluginfetch['layout_include']['before'] as $act => $layout) {
			if($act == $_GET['act'])
				include('plugins/'.$pluginfetch['folder'].'/Layouts/'.$layout);
		}
	}
}
# MAIN
# process actions
if ($isvalid) {
	include(IN_PATH.'themes/' . $settings['site_theme'] . '/' . $action[$receiver]);
} else {
	header("HTTP/1.0 404 Not Found");
	include IN_PATH.'themes/'.$settings['site_theme'].'blueprints/404.php';
}

#include any everypage layouts set by plugins or any layouts that need to be included on a certain act after the content
$plugin = call('sql_query', "SELECT folder, everypage, layout_include FROM plugins WHERE active = '1'", 'cache');
foreach($plugin as $pluginfetch) {
	if(!empty($pluginfetch['everypage'])) {
		if (file_exists('plugins/' . $pluginfetch['folder'] . '/Layouts/'. $pluginfetch['everypage'])) {
			include(IN_PATH.'plugins/' . $pluginfetch['folder'] . '/Layouts/'. $pluginfetch['everypage']);
		}
	}
	$pluginfetch['layout_include'] = unserialize($pluginfetch['layout_include']);
	if(!empty($pluginfetch['layout_include']) && isset($pluginfetch['layout_include']['after']) && is_array($pluginfetch['layout_include']['after'])) {
		foreach($pluginfetch['layout_include']['after'] as $act => $layout) {
			if($act == $_GET['act'])
				include('plugins/'.$pluginfetch['folder'].'/Layouts/'.$layout);
		}
	}
}

# OUTPUT
# outputs all actions as HTML
if(!isset($theme['output']))
	theme($theme['default_output']);
elseif(isset($theme['output']) && empty($theme['output'])) {
	# do nothing
} else {
	theme($theme['output']);
}
/*$_GET['act']=='admin') {
# output admin pages
	theme('output_admin', $title, $body, $head);
} elseif ($_GET['act']=='maintenance') {
# output maintenace page
	theme('output_maintenance', $title, $body, $head);
} elseif ($_GET['act'] == 'finduser') {
# output (?) pages
	theme('output_popup', $head, $body, $title);
} elseif ($_GET['act'] == 'feeds') {
# output (control) rss feeds (without html)
} elseif ($_GET['act']=='ajax') {
# output (control) ajax (without html)
} else {
# output all other actions (pages)
	theme('main_template');
}*/

# FINAL
ob_end_flush();

# END
?>