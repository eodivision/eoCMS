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