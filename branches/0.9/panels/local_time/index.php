<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

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