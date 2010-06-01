<?php
/*  eoCMS � 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/

function visitor_ip() {
	if(!isset($_SERVER['SERVER_ADDR'])) $_SERVER['SERVER_ADDR'] = '';
	$ip = ($_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR'] && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	return $ip;
}
?>