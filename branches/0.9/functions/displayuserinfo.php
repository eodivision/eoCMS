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
  function displayuserinfo($userid, $limit = '') {
if(!empty($limit)) {
$query = $limit;
}
else {
$query = '*';
}
  $sql = call('sql_query', "SELECT " . $query . " FROM users WHERE id = '$userid'");
  $fetch = call('sql_fetch_array',$sql);
  if($sql) {
  return $fetch;
  }
  else {
  return false;
  }
  }
?>