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