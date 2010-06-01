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

function top() {
	global $settings;
	$output = '<div class="topside-l">
    <div class="topside-r">
  <div id="topbar"></div>
     <div id="ajax" class="ajax"></div>
	 <div class="header-left"><div class="header-right"><div class="header">
    <div align="center"><img src="' . (isset($settings['logo'])?$settings['logo']:'themes/default/images/logo.png') . '" alt="logo" /></div></div></div></div>';
	return $output;
}
?>