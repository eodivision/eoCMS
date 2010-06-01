<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
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
# ACTIONS
# predefine action mappings, required for language page
$action = array(
	'activate'          => 'Layouts/Activate.php',
	'admin'             => 'Layouts/Admin.php',
	'ajax'              => 'Layouts/Ajax.php',
	'articles'          => 'Layouts/Articles.php',
	'editaccount'       => 'Layouts/EditProfileDetails.php',
	'editlook'          => 'Layouts/EditProfileLook.php',
	'editmiscellaneous' => 'Layouts/EditProfileMisc.php',
	'editpost'          => 'Layouts/EditPost.php',
	'editprofile'       => 'Layouts/EditProfile.php',
	'deletepm'          => 'Layouts/DeletePm.php',
	'feeds'             => 'Layouts/Feeds.php',
	'finduser'          => 'Layouts/UserSearch.php',
	'forum'             => 'Layouts/IndexForum.php',
	'forgotpassword'    => 'Layouts/ForgotPassword.php',
	'login'             => 'Layouts/Login.php',
	'logout'            => 'Layouts/Logout.php',
	'maintenance'       => 'Layouts/Maintenance.php',
	'modcp'             => 'Layouts/ModeratorCP.php',
	'news'              => 'Layouts/News.php',
	'newtopic'          => 'Layouts/NewTopic.php',
	'register'          => 'Layouts/Register.php',
	'reply'             => 'Layouts/Reply.php',
	'page'              => 'Layouts/Pages.php',
	'plugin'            => 'Layouts/Plugins.php',
	'pm'                => 'Layouts/Pm.php',
	'profile'           => 'Layouts/Profile.php',
	'search'            => 'Layouts/Search.php',
	'sendpm'            => 'Layouts/SendPm.php',
	'trackip'           => 'Layouts/TrackIP.php',
	'userpreferances'   => 'Layouts/UserPreferances.php',
	'viewboard'         => 'Layouts/ViewBoard.php',
	'viewpm'            => 'Layouts/ViewPm.php',
	'viewtopic'         => 'Layouts/ViewTopic.php',
	'404'               => 'Layouts/404.php',
# default page action
	''                  => (isset($user['default'])? $user['default'] : (isset($settings['site_default'])? $settings['site_default'] : 'Layouts/Pages.php' ))
);
# predefine form GET action on landing page
#  and do setup if "perminant link" (permalink) found
if (!isset($_GET['act'])) {
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
	$lang_file = str_replace('Layouts','',$action[$receiver]);
	$lang_array = strtoupper(str_replace('/','',str_replace('.php','',$lang_file)));
}else{
	$lang_file = str_replace('Layouts','',$action['404']);
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
				include('Plugins/'.$pluginfetch['folder'].'/Layouts/'.$layout);
		}
	}
}
# MAIN
# process actions
if ($isvalid) {
	if (file_exists('themes/' . $settings['site_theme'] . '/' . $action[$receiver])) {
		include(IN_PATH.'themes/' . $settings['site_theme'] . '/' . $action[$receiver]);
	} else {
		include(IN_PATH.$action[$receiver]);
	}
} else {
	header("HTTP/1.0 404 Not Found");
	include IN_PATH.'Layouts/404.php';
}
# predefine HTML head section, if not already set
if (!isset($head) || empty($head)) {
	$head = '';
}
#include any everypage layouts set by plugins or any layouts that need to be included on a certain act after the content
$plugin = call('sql_query', "SELECT folder, everypage, layout_include FROM plugins WHERE active = '1'", 'cache');
foreach($plugin as $pluginfetch) {
	if(!empty($pluginfetch['everypage'])) {
		if (file_exists('Plugins/' . $pluginfetch['folder'] . '/Layouts/'. $pluginfetch['everypage'])) {
			include(IN_PATH.'Plugins/' . $pluginfetch['folder'] . '/Layouts/'. $pluginfetch['everypage']);
		}
	}
	$pluginfetch['layout_include'] = unserialize($pluginfetch['layout_include']);
	if(!empty($pluginfetch['layout_include']) && isset($pluginfetch['layout_include']['after']) && is_array($pluginfetch['layout_include']['after'])) {
		foreach($pluginfetch['layout_include']['after'] as $act => $layout) {
			if($act == $_GET['act'])
				include('Plugins/'.$pluginfetch['folder'].'/Layouts/'.$layout);
		}
	}
}

# OUTPUT
# outputs all actions as HTML
if ($_GET['act']=='admin') {
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
	theme('output', $title, $body, $head);
}

# FINAL
ob_end_flush();

# END
?>