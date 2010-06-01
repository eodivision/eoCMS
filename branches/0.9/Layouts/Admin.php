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

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

# START
if(!$user['admin_panel'] || !isset($_GET['authid']) || AUTHID != $_GET['authid']) {
# if not valid admin session, redirect
	header("Location: index.php");
	exit();
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