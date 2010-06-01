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

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

if(!isset($_GET['id'])){
# default home page content (Home)
	$fetch = call('page', 1);
	$title = $fetch['pagename'];
	$body = theme('title', $fetch['pagename']) . theme('start_content') . html_entity_decode(htmlspecialchars_decode($fetch['content'], ENT_QUOTES));
	$head = call('seokeyword', $fetch['content']) . call('seodescription', $fetch['content']);
} else {
	$fetch = call('page', $_GET['id']); 
	$head = call('seokeyword', $fetch['content']) . call('seodescription', $fetch['content']);
	if($fetch != false) {
		$title = $fetch['pagename'];
	}
	$body = theme('title', $fetch['pagename']) . theme('start_content') . html_entity_decode(htmlspecialchars_decode($fetch['content'], ENT_QUOTES));
	if($fetch['ratings'] == '1') {
		$body .= call('displayrating', $_GET['id'], 'pages'); 
	}
	if($fetch['comments'] == '1') {
		$body .= call('displaycomment', $_GET['id'], 'pages');
	}
}
$body.= theme('end_content');
?>