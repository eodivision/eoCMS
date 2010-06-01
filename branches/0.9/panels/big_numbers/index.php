<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

    Big Numbers Panel - 14/06/09 - Paul Wratt
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$_bn_articles = call('sql_query','SELECT COUNT(id) FROM articles', 'cache');
$_bn_threads = call('sql_query','SELECT COUNT(topic_id) FROM forum_topics', 'cache');
$_bn_posts = call('sql_query','SELECT COUNT(id) FROM forum_posts', 'cache');
$_bn_pms = call('sql_query','SELECT COUNT(id) FROM pm', 'cache');

$_bn_body = '';
$_bn_body .= $_bn_threads[0][0] . ' ' . $PANEL_LANG['bn_threads'] . '<br />';
$_bn_body .= $_bn_posts[0][0] . ' ' . $PANEL_LANG['bn_posts'] . '<br />';
$_bn_body .= $_bn_pms[0][0] . ' ' . $PANEL_LANG['bn_messages_sent'] . '<br />';
if(isset($setting['bn_use_links'])) {
	$_bn_body .= '<a href="?act=articles">' . $_bn_articles[0][0] . ' ' . $PANEL_LANG['bn_articles'] . '</a><br />';
}else{
	$_bn_body .= $_bn_articles[0][0] . ' ' . $PANEL_LANG['bn_articles'] . '<br />';
}

$body .= '<div class="panel-header">'.theme('title', $PANEL_LANG['bn_title']).'</div>'.theme('start_content_panel').'
<div id="bn_panel">' . $_bn_body . '</div>'.theme('end_content');
?>