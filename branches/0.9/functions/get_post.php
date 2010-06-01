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
function get_post($id) {
	global $user, $error_die;
	if(is_numeric($id)) {
		$sql = call('sql_query', "SELECT * FROM forum_posts WHERE id = '$id'");
		$fetch = call('sql_fetch_array',$sql);
		if(((isset($user['id']) && $fetch['author_id'] == $user['id'] && $user['modify_own_posts']) || ($user['guest'] && $fetch['ip']== call('visitor_ip') && $fetch['author_id']=='0')) || ($user['modify_any_posts'])) {
			if (empty($id))
				$error_die[] = 'No post selected';
			return $fetch;
		} else {
			$error_die[] = 'You do not have permission to edit this post';
		}
	}
}
?>