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

function top() {
	global $settings;
	$output = '<div class="topside-l">
    <div class="topside-r">
  <div id="topbar"></div>
     <div id="ajax" class="ajax"></div>
   <div id="header">
    <div align="center"><img src="' . (isset($settings['logo'])?$settings['logo']:$settings['site_url'].'themes/default/images/logo.png') . '" alt="logo" /></div></div>';
	return $output;
}
?>