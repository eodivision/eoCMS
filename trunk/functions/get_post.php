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