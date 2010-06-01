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
  function addgroup($stuff, $name, $image, $colour) {
$sql = call('sql_query', "INSERT INTO membergroups (name, image, colour) VALUES('$name', '$image', '$colour')");
$group_id = call('sql_insert_id');
$val = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id = '4'");
while($r = call('sql_fetch_array', $val)) {
$stuff[$r['variable']] = $stuff[$r['variable']] == 'on' ? 1 : 0; 
}
foreach($stuff as $key => $value) {
$sql = call('sql_query', "INSERT INTO permissions (membergroup_id, variable, value) VALUES ('$group_id', '$key', '$value')");
}
return true;
}
?>