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
  function form_token() {
$token = md5(uniqid(rand(), TRUE));
if (!isset($_SESSION['token']))
{
  $_SESSION['token'] = $token;
  }
  $_SESSION['token_timestamp'] = time();
  $hiddenfield = '<input type="hidden" name="token" value="' . $_SESSION['token'] . '"/>';
  return $hiddenfield;
  }
?>