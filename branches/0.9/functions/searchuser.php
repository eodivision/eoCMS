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
  function searchuser($search) {
$sql = call('sql_query', "SELECT user FROM users WHERE user LIKE '%".$search."%' ORDER BY user");
if(call('sql_num_rows', $sql) == 0 || empty($search)) {
return false;
}
else {
$fetch = array();
while ($row = call('sql_fetch_array',$sql)) {
              $fetch[] = array('user'=>$row['user']);
}
return $fetch;
}
}
?>