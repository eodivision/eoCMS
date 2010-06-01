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
  function urlidcheck() {
//makes sure that the phpsessid is NOT in the url!
if (isset($_GET['PHPSESSID'])) 
{
$requesturi = preg_replace('/?PHPSESSID=[^&]+/',"",$_SERVER['REQUEST_URI']);
$requesturi = preg_replace('/&PHPSESSID=[^&]+/',"",$requesturi);
header("HTTP/1.1 301 Moved Permanently");
header("Location: http://".$_SERVER['HTTP_HOST'].$requesturi);
exit;
}
}
?>