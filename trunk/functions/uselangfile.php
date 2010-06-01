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

function uselangfile($act){
	global $action,$lang_path;
	$lang_file = str_replace('Layouts','',$action[$act]);
	$lang_array = strtoupper(str_replace('/','',str_replace('.php','',$lang_file)));
	@include(IN_PATH . 'language/' . $lang_path . '/' . $lang_file);
	if(@eval('!is_array($' . $lang_array . '_LANG)'))
		@include(IN_PATH . 'language/en' . $lang_file);
	@eval('$arr = $' . $lang_array . '_LANG;');	
return $arr;
}
?>