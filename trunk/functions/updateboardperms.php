<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
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