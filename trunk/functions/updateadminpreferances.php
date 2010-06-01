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
function updateadminpreferances($userid, $menu, $currentpass, $token) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	$fetch = call('displayuserinfo', $userid);
	if(!empty($currentpass)) {
		if($currentpass != $user['pass']) {
			$_error[] = 'The current password entered is not correct';
			return false;
		}
	}
	if(empty($currentpass)) {
		$error[] = 'You must enter your current password to update your profile';
		return false;
	}
	if(!errors()) {
		$sql = call('sql_query', "UPDATE users SET admin_menu = '$menu' WHERE id = '$userid'");
		if($sql)
			return true;
	}
}
?>