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
function updatelook($userid, $topicspage, $postspage, $currentpass, $quickreply, $theme, $token) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	if(!empty($currentpass)) {
		if($currentpass != $user['pass']) {
			$error[] = 'The current password entered is not correct';
			return false;
		}
	}
	if(empty($currentpass)) {
		$error[] = 'You must enter your current password to update your profile';
		return false;
	}
	if(!is_numeric($topicspage) && !empty($topicspage)) {		
		$error[] = 'You must enter numeric values only for Topics per Page';
		return false;		
	}
	if(!empty($topicspage) && $topicspage < '5') {		
		$error[] = 'You must enter a minimum of 5 for Topics per Page';
		return false;		
	}
	if(!is_numeric($postspage) && !empty($postspage)) {		
		$error[] = 'You must enter numeric values only for Posts per Page';
		return false;		
	}
	if(!empty($postspage) && $postspage < '5') {		
		$error[] = 'You must enter a minimum of 5 for Posts per Page';
		return false;		
	}
	$postspage = (empty($postspage) ? 0 : $postspage);
	$topicspage = (empty($topicspage) ? 0 : $topicspage);
	if(!errors()) {
		$sql = call('sql_query', "UPDATE users SET topics_page = '$topicspage', posts_topic = '$postspage', quickreply = '$quickreply', theme = '$theme' WHERE id = '$userid'");
		if($sql)
			return true;
	}
}
?>