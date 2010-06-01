<?php
/*  eoCMS � 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
function deleteforumcat($id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel']) {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($id)) {
		$error[] = 'You must specify a id';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "DELETE FROM forum_categories WHERE id = '$id'");
		$boards = call('sql_query', "DELETE FROM forum_boards WHERE cat = '$id'");
		if($query)
			return true;
	}
}
?>