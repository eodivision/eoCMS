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
function getboardpermission($boardid) {
	global $user;
	if(isset($user['id'])) {  
		$permissionquery = call('sql_query', "SELECT board_permissions.variable AS variable, board_permissions.value AS value FROM membergroups LEFT JOIN users ON membergroups.membergroup_id=membergroup LEFT JOIN board_permissions ON board_permissions.membergroup_id=membergroups.membergroup_id WHERE users.id = '".$user['id']."' AND board_permissions.membergroup_id = membergroup AND board_permissions.board_id = '$boardid'");
		while ($perms = call('sql_fetch_array', $permissionquery)) {
			$permissions[$perms['variable']] = $perms['value'];
      }
		if(isset($permissions))
			$user = array_merge($user, $permissions);
		return $user;
	}
}
?>