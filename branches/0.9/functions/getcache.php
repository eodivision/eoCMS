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
function getcache($query) {
	$file = md5($query) . '.php';
	$location = './'.CACHE.'/';
	if(@file_exists($location . $file) && @is_readable($location . $file)) {
		$destroy = false;
		$data = unserialize(file_get_contents($location . $file, NULL, NULL, 16)); 
		return $data;
	} else
		return false;
}
?>