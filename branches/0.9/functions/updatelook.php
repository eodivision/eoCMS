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