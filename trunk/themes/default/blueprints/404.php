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

   Added Language - 29/03/09 - Paul Wratt
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$theme['title'] = $FOUROFOUR_LANG["title"];
$theme['body'] = theme('title', $FOUROFOUR_LANG["theme_title"]) . theme('start_content') . $FOUROFOUR_LANG["slight_problem"] . '
<p>' . $FOUROFOUR_LANG["try"] . ':<p>
<p><b>1.)</b>' . $FOUROFOUR_LANG["check_web_address"] . '</p>
<p><b>2.)</b> ' . $FOUROFOUR_LANG["click_back"] . ' (<a href=javascript:history.back(-1)>' . $FOUROFOUR_LANG["page_back"] . '</a>)
<p>' . $FOUROFOUR_LANG["let_us_know"] . ' (<a href="' . $settings['site_url'] . '/index.php?act=forum"><u>' . $FOUROFOUR_LANG["page_forum"] . '</u></a>)</p>' . theme('end_content');
?>