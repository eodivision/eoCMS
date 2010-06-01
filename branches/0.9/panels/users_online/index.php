<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

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