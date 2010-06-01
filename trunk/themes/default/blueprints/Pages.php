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
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$fetch = call('page', $_GET['id']); 
$theme['head'] = call('seokeyword', $fetch['content']) . call('seodescription', $fetch['content']);
if($fetch != false)
	$theme['title'] = $fetch['pagename'];
$theme['head'] = theme('title', $fetch['pagename']) . theme('start_content') . html_entity_decode(htmlspecialchars_decode($fetch['content'], ENT_QUOTES)).theme('end_content');
if($fetch['ratings'] == '1')
	$theme['head'] .= call('displayrating', $_GET['id'], 'pages'); 
if($fetch['comments'] == '1')
	$theme['head'] .= call('displaycomment', $_GET['id'], 'pages');
?>