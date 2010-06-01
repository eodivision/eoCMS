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