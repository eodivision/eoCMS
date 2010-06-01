<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

   Added Language - 29/03/09 - Paul Wratt
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$title = $FOUROFOUR_LANG["title"];
$body = theme('title', $FOUROFOUR_LANG["theme_title"]) . theme('start_content') . $FOUROFOUR_LANG["slight_problem"] . '
<p>' . $FOUROFOUR_LANG["try"] . ':<p>
<p><b>1.)</b>' . $FOUROFOUR_LANG["check_web_address"] . '</p>
<p><b>2.)</b> ' . $FOUROFOUR_LANG["click_back"] . ' (<a href=javascript:history.back(-1)>' . $FOUROFOUR_LANG["page_back"] . '</a>)
<p>' . $FOUROFOUR_LANG["let_us_know"] . ' (<a href="' . $settings['site_url'] . '/index.php?act=forum"><u>' . $FOUROFOUR_LANG["page_forum"] . '</u></a>)</p>' . theme('end_content');
?>