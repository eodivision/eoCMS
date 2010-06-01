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