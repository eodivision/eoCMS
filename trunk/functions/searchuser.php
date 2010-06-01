<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
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