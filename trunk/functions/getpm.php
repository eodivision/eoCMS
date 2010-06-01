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
function getpm($id) {
	global $user;
	if($user['read_pms'] && is_numeric($id)) {
		$query = call('sql_query', "SELECT u.id AS user_id, u.avatar AS avatar, u.posts AS posts, u.membergroup AS membergroup, u.signature AS signature, u.user AS user, p.id AS id, p.title AS title, p.message AS message, p.sender AS sender, p.to_send AS to_send, p.time_sent AS time_sent, p.mark_delete AS mark_delete, p.mark_read AS mark_read FROM pm p LEFT JOIN users u ON p.sender=u.id WHERE p.id = '$id' ORDER BY p.time_sent DESC;");
		$pm = call('sql_fetch_assoc', $query);
		if ($pm['to_send'] == $user['id'] || $pm['sender'] == $user['id']) {
			if($pm['mark_read'] !='1' && $pm['to_send'] == $user['id'])
				call('sql_query', "UPDATE pm SET mark_read = '1' WHERE id = '$id'");
			return $pm;
		}
		else
			echo 'This message is not for you!';
	} else {
		echo 'You do not have permission to read messages';
		return false;
	}
}
?>
