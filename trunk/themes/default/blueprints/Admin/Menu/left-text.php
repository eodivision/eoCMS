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

if (!(defined("IN_ECMS"))) die("Hacking Attempt...");

$menu='
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 15px;">
  <tr>
    <td valign="top" width="150">' . theme('title', 'Navigation') . '
	<div id="adminNav">' . theme('start_content') . '
	  <ul>';
$links = call('adminlinks');
foreach($links as $theme['title']s => $href) {
	if($theme['title']s!='images') {
		$menu.='
	  <li><a href="index.php?act=admin&amp;opt='.$href.'&amp;'.$authid.'" title="'.$theme['title']s.'">'.$theme['title']s.'</a></li>';
	}
}
$menu.='
	  </ul>'.theme('end_content').'
	</div></td>
    <td valign="top"><div class="titlebg" align="center">' . $theme['title'] . '</div>
	'.$update.$error.'<br />
	' .stripslashes($theme['body']) . '</td>
  </tr>
</table>';
?>