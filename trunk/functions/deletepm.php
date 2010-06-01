<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons 
	Attribution-Share Alike 3.0 United States License. 
	To view a copy of this license, visit 
	http://creativecommons.org/licenses/by-sa/3.0/us/
	or send a letter to Creative Commons, 171 Second Street, 
	Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
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