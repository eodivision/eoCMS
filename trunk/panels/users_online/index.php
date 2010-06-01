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

   Added Language - 07/05/09 - Paul Wratt
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$body .= '<div class="panel-header">'.theme('title', $PANEL_LANG['uo_title']) .'</div>'. theme('start_content_panel');
# get the total number of members
$sql = call('sql_query', "SELECT COUNT(id) AS total FROM users", 'cache');
$total_users = $sql[0][0];
# get the number of guests online!
$guests = call('sql_query', "SELECT COUNT(user_id) AS numrows FROM user_online WHERE user_id = '0'", 'cache');
$guests = $guests[0][0];
# get the number of members online!
$members = call('sql_query', "SELECT COUNT(user_id) AS numrows FROM user_online WHERE user_id != '0'", 'cache');
$members = $members[0][0];
$body .= $PANEL_LANG['uo_guests_on'].$guests.'<br />' .$PANEL_LANG['uo_members_on'].$members.'<br />'.$PANEL_LANG['uo_total_on'].$total_users;
$body .= theme('end_content');
?>