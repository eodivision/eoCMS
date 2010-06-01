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
  function updateboardperms($variables, $board, $membergroup) {
  global $cperms, $bperms;
foreach($variables as $key => $value) {
	if($value == 'on') {
	$variables[$key] = '1';
	}
}
$cperms = array_merge($cperms, $variables);
foreach($cperms as $key => $value) {
if(array_key_exists($key, $bperms)) {
$sql = call('sql_query', "UPDATE board_permissions SET value = '" . $value . "' WHERE variable='" . $key . "' AND membergroup_id = '" . $membergroup . "' AND board_id = '" . $board . "'");
} else {
$sql = call('sql_query', "INSERT INTO board_permissions (membergroup_id, board_id, variable, value) VALUES ('$membergroup', '$board', '$key', '$value')");
}
}
	return true;
}
?>