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