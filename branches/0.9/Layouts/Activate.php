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

$title = $ACTIVATE_LANG["title"];
$check = call('activate', $_GET['id'], $_GET['key']);
if($check==true){
$body = theme('title', $ACTIVATE_LANG["theme_title"]) . theme('start_content') . $ACTIVATE_LANG["body_content"] . theme('end_content');
}
?>