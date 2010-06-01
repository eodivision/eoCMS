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
if(!$user['admin_panel'] || !isset($_GET['authid']) || AUTHID != $_GET['authid']) {
# if not valid admin session, redirect
	call('redirect', 'index.php');
}

$title = $ADMIN_LANG["title"];
# ACTIONS
# predefine ADMIN action mappings, required for language page
$a_action = array(
	'articles'          => 'Admin/Articles.php',
	'bans'              => 'Admin/Bans.php',
	'emoticons'         => 'Admin/Emoticons.php',
	'forum'             => 'Admin/Boards.php',
	'links'             => 'Admin/Links.php',
	'news'              => 'Admin/News.php',
	'pages'             => 'Admin/Pages.php',
	'panels'            => 'Admin/Panels.php',
	'permissions'       => 'Admin/Permissions.php',
	'plugins'           => 'Admin/Plugins.php',
	'sitesettings'      => 'Admin/SiteSettings.php',
	'themes'            => 'Admin/Theme.php',
	'users'             => 'Admin/Users.php',
# default page action
//	''                  => (isset($user['default'])? $user['default'] : (isset($settings['site_default'])? $settings['site_default'] : 'Admin/custom.php' ))
);
# predefine form GET action on landing page
if(!isset($_GET['opt'])) {
	$_GET['opt'] = '';
}
$a_receiver = $_GET['opt'];
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

# include actual ADMIN NEMU language files
@include_once('language/' . $lang_path . '/Admin/defaultmenu.php');
# use english if there was an error
if(!@is_array($ADMIN_MENU_LANG)) {
# developers warning
	if(!defined('FINAL'))
		echo 'WARNING: no file for "language/' . $lang_path . '/Admin/defaultmenu.php"';
	@include_once('language/en/Admin/defaultmenu.php');
}

# PANES & PANELS
# default landing page
if(!$a_isvalid) {
	$head = '<script type="text/javascript" src="http://eocms.com/news.js"></script><script type="text/javascript">
$(document).ready(function(){
	$.each(news,function(){
		if(this.version > "' . $settings['version'] . '") {
			$("#update").append("' . $ADMIN_LANG["js_out_of_date"] . '")
		}
		$("#eocmsnews").append("<a href=\'"+this.topic+"\' target=\'_blank\'><b>"+this.subject+"</a> ' . $ADMIN_LANG["js_at"] . ' " + this.date + "</b><br /><div style=\'padding-left: 2px; padding-bottom: 2px;\'>"+this.message + "</div>")
	})
})
</script>';
	$body = '
<table class="home-admin-panel">
  <tr>
    <td>'.theme('title', $ADMIN_LANG["theme_title"]).theme('start_content').'<div id="eocmsnews" class="small-text"></div>'.theme('end_content').'</td>
  <tr>
    <td><br />' . $ADMIN_LANG["body_content"] . '</td>
  </tr>
</table>';
} else {
# actual menu section, or subsection
	include($a_lang_file);
}

# END
?>