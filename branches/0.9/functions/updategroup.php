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