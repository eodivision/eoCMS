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
function activate($id, $key) {
	global $error_die;
	if(!is_numeric($id)) {
		$error_die[] = 'The key and or id given is not valid';
		return false;	
	} else {
		$sql = call('sql_query', "SELECT * FROM activation_keys WHERE key_number = '$key' AND user_id = '$id'");
		if(call('sql_num_rows', $sql) > 0) {
			$sql = call('sql_query', "UPDATE users SET membergroup = '1' WHERE id = '$id'");
			if($sql)
				return true;
		$delete = call('sql_query', "DELETE FROM activation_keys WHERE key_number = '$key' AND user_id = '$id'");
		} else {
			$error_die[] = 'The key and or id given is not valid';
			return false;
		}
	}
}
?>