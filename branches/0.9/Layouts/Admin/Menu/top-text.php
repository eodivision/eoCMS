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
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:15px;">
  <tr>
    <td valign="top"><div class="titlebg-left"></div><div class="titlebg titlebg-right"></div><div align="center" class="titlebg">' .$title . '</div></td>
  </tr>
  <tr>
    <td valign="top"><div id="adminNavTop" align="center">
	<ul>';
$links = call('adminlinks');
foreach($links as $titles => $href) {
	if($titles!='images') {
	$menu.='
	  <li><a href="index.php?act=admin&amp;opt='.$href.'&amp;'.$authid.'" title=" '.$titles.' ">'.$titles.'</a></li>';
	}
}
$menu.='</ul></div></td>
  </tr>
  <tr>
    <td valign="top">
	' . $update . $error. '<br />
	' .stripslashes($body) . '</td>
  </tr>
</table>';
?>