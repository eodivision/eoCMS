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
function uninstallplugin($id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel']) {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($id)){
		$error[] = 'No Plugin was selected';
		return false;
	}
	$sql = call('sql_query', "SELECT folder FROM plugins WHERE id = '$id'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This Plugin does not exist';
		return false;
	}
	if(!errors()) {
		$fetch = call('sql_fetch_array',$sql);
		include('./Plugins/' . $fetch['folder'] . '/plugin-info.php');
		$delete = call('sql_query', "DELETE FROM plugins WHERE id = '$id'");
		if(isset($plugin['uninstall']) && is_array($plugin['uninstall'])) {
			foreach($plugin['uninstall'] as $uninstall) {
				call('sql_query', $uninstall);	
			}
		}
		if($delete)
			return true;
	}
}
?>