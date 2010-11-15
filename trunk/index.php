<?php
/*
	eoCMS © 2007 - 2010, a Content Management System
	by James Mortemore
	http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
define("IN_ECMS", true);
require_once('framework.php');
// gzip check
if(setting('gzip'))
    ob_start('ob_gzhandler');
else
	ob_start(); // Set caching of output to web server
// IE8 frame busting, well thats the only good thing it has :P (Now supported by Firefox woot)
if(!setting('xframe'))
	header('X-FRAME-OPTIONS: SAMEORIGIN');
?>
