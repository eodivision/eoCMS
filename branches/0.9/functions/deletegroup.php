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
  function deletegroup($id, $membergroup) {
$sql = call('sql_query', "UPDATE users SET membergroup = '" . $membergroup . "' WHERE membergroup='$id'");
$delete = call('sql_query', "DELETE FROM permissions WHERE membergroup_id = '$id'");
$delete2 = call('sql_query', "DELETE FROM membergroups WHERE membergroup_id = '$id'");
if($sql && $delete && $delete2) {
return true;
}
}
?>