<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
  function updategroup($stuff, $names, $image, $colour, $id) {
  $new = array();
$val = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id = '$id'");
while($r = call('sql_fetch_array', $val)) {
$stuff[$r['id']] = $stuff[$r['id']] == 'on' ? 1 : 0; 
if($stuff[$r['id']]!=$r['value']) {
$new[$r['id']]=$stuff[$r['id']];
}
}
foreach($new as $key => $value) {
$sql = call('sql_query', "UPDATE permissions SET value = '" . $value . "' WHERE id='" . $key . "' AND membergroup_id = '" . $id . "'");
}
$updateimage = call('sql_query', "UPDATE membergroups SET image = '$image', colour = '$colour' WHERE membergroup_id = '$id'");
$username = explode(", ", $names);
          foreach ($username as $recipient) {
              $sql_2 = call('sql_query', "UPDATE users SET membergroup = '$id' WHERE user = '$recipient'");
          }
		  return true;
}
?>