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
  function sql_fetch_assoc($data) {
  global $con;
	  if(defined('DB_TYPE')) {
	  if(DB_TYPE == 'mysql')
$sql = mysql_fetch_assoc($data);
elseif(DB_TYPE == 'sqlite')
$sql = sqlite_fetch_array($data, SQLITE_ASSOC);
return $sql;
	}
}
?>