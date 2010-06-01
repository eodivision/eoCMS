<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

    Membership Stats Panel - 14/06/09 - Paul Wratt
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$_ms_members = call('sql_query','SELECT max(id) AS numids FROM users', 'cache');
$_ms_members = $_ms_members[0][0];
$_ms_visited_24h = call('sql_query', "SELECT COUNT(id) AS numrows FROM users WHERE lastlogin>'" . (time()-(24 * 60 * 60)) . "'", 'cache');
$_ms_visited_24h = $_ms_visited_24h[0][0];
$_ms_users_online = call('sql_query', "SELECT COUNT(user_id) AS numrows FROM user_online WHERE user_id!='0'", 'cache');
$_ms_users_online = $_ms_users_online[0][0];
$_ms_guests_online = call('sql_query', "SELECT COUNT(user_id) AS numrows FROM user_online WHERE user_id = '0'", 'cache');
$_ms_guests_online = $_ms_guests_online[0][0];

$_ms_body = '';

$_ms_body .= $_ms_members . ' ' . $PANEL_LANG['ms_registered_members'] . '<br />';
$_ms_body .= $_ms_visited_24h . ' ' . $PANEL_LANG['ms_visited_past_24h'] . '<br />';
if(isset($setting['ms_use_links'])) {
	$_ms_body .= '<a href="?act=online">' . $_ms_users_online . ' ' . $PANEL_LANG['ms_members_online_now'] . '</a><br />';
}else{
	$_ms_body .= $_ms_users_online . ' ' . $PANEL_LANG['ms_members_online_now'] . '<br />';
}
$_ms_body .= $_ms_guests_online . ' ' . $PANEL_LANG['ms_guests_online_visiting'] . '<br />';

$body .= '<div class="panel-header">'.theme('title', $PANEL_LANG['ms_title']).'</div>'.theme('start_content_panel').'
<div id="ms_panel">' . $_ms_body . '</div>'.theme('end_content');
?>