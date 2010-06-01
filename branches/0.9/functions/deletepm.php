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

function deletepm($id) {
	global $user;
	if(is_numeric($id)) {
		$sql = call('sql_query', "SELECT * FROM pm WHERE id = '$id'");
		$fetch = call('sql_fetch_array', $sql);
		if($fetch['to_send'] == $user['id']) {
			if($fetch['mark_sent_delete'] == '1') {
				$sql = call('sql_query', "DELETE FROM pm WHERE id = '$id'");
			} else {
				$sql = call('sql_query', "UPDATE pm SET mark_delete = '1' WHERE id = '$id'");
			}
			$return = true;
		} elseif($fetch['sender'] == $user['id']) {
			if($fetch['mark_delete'] == '1') {
				$sql = call('sql_query', "DELETE FROM pm WHERE id = '$id'");
			} else {
				$sql = call('sql_query', "UPDATE pm SET mark_sent_delete = '1' WHERE id = '$id'");
			}
			$return = true;
		} else {
			$return = false;
		}
	}
	return $return;
}
?>