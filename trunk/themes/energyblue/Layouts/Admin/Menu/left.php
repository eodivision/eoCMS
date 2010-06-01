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
<table class="admin-area">
  <tr>
    <td valign="top" class="navbar-admin-top">' . theme('title', 'Navigation') . '
	' . theme('start_content') . '
	<ul>';
$links = call('adminlinks');
foreach($links as $titles => $href) {
	if($titles!='images') {
		$menu.='
	  <li><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt='.$href.'&amp;'.$authid.'" title="'.$titles.'">'.$titles.'</a></li>';
	}
}
		$menu.='
	</ul>'.theme('end_content').'
	</td>
    <td valign="top" class="admin-divide">' . theme('title', $title) . theme('start_content') . '
	'.$update.$errors.'<br />
	' .stripslashes($body) . theme('end_content').'</td>
  </tr>
</table>';
?>