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

    Local Time Panel - 11/06/09 - Paul Wratt
*/
if(!defined("IN_ECMS")) die("Hacking Attempt...");

# 2009-Jun-10  3:19 AM
if(!isset($lt_format)) $lt_format = 'Y-M-j  g:i A';

$lt_output = date($lt_format);
if(!$user['guest'] && !isset($setting['local_time'])) 
	$lt_time = '<a href="?act=editmiscellaneous">' . $lt_output . '</a>';
else
	$lt_time = $lt_output;
$body .= '<div class="panel-header">'.theme('title', $PANEL_LANG['lt_title']).'</div>'.theme('start_content_panel').'
<div id="lt_panel">' . $lt_time . '
</div>'.theme('end_content');
?>