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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if(!$user['track_ip'])
	$error_die[] = $TRACKIP_LANG["error_die"];
$title = $TRACKIP_LANG["title"];
$body = theme('title', $TRACKIP_LANG["theme_title"]) . theme('start_content') . '<form method="get" action="">Ip(or range)<input type="hidden" name="act" value="trackip" /><input type="text" name="ip" value="'.(isset($_GET['ip']) ? $_GET['ip'] : '').'"/> <input type="submit" value="'.$TRACKIP_LANG["btn_track_ip"].'"/></form><br />';
if(isset($_GET['ip']) && !empty($_GET['ip'])) {
	$title .= ' - '. $_GET['ip'];
	$fetch = call('trackip', $_GET['ip']);
	$messagepage = call('pagination', isset($_GET['page']), $settings['posts_topic'], 'SELECT COUNT(id) AS numrows FROM forum_posts WHERE ip LIKE "%'.$_GET['ip'].'%"', '?act=trackip&ip=' . $_GET['ip'] . '&page=', 3);
	$body.=theme('title', $TRACKIP_LANG["theme_title_track"]).theme('start_content').'<table><tr class="subtitlebg"><td width="20%">'.$TRACKIP_LANG["ip_address"].'</td><td>'.$TRACKIP_LANG["user"].'</td>';
	foreach($fetch as $r) {
		if($r['type']=='user')
			$body.='<tr><td>'.$r['ip'].'</td><td>'.$r['user'].'</td></tr>';
	}
	$body.= '</table>'.theme('end_content');
	$body.=theme('title', $TRACKIP_LANG["theme_title_posts"]) . theme('start_content') . $messagepage . '<table><tr class="subtitlebg"><td width="20%">'.$TRACKIP_LANG["ip_address"].'</td><td width="25%">'.$TRACKIP_LANG["poster"].'</td><td width="20%">'.$TRACKIP_LANG["subject"].'</td><td width="25%">'.$TRACKIP_LANG["date"].'</td>';
	foreach($fetch as $r) {
		if($r['type']=='post')
			$body.='<tr><td>'.$r['ip'].'</td><td>'.$r['author'].'</td><td>'.$r['subject'].'</td><td>'.$r['post_time'].'</td></tr>';
	}
	$body.= '</table>'.theme('end_content');
}
$body .=theme('end_content');
?>