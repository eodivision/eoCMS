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
function updateprofile1($userid, $email, $currentpass, $newpassword, $vpassword, $token, $hideemail) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	$fetch = call('displayuserinfo', $userid);
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
	if(!preg_match("/^([a-z0-9._-](\+[a-z0-9])*)+@[a-z0-9.-]+\.[a-z]{2,6}$/i", $email)) {
		$error[] = 'The email address entered is not valid';
		return false;
	}
	if(empty($email)) {
		$error[] = 'You must enter an email address';
		return false;
	}
	if(!empty($newpassword) && $newpassword != $vpassword) {
		$error[] = 'The new passwords do not match';
		return false;
	}
	if(!empty($newpassword) && strlen($newpassword) < 6) {
		$error[] = 'The Password must be 6 characters or longer';
		return false;
	}
	if(!errors()) {
		if(!empty($newpassword)) {
			$vpassword = md5($vpassword);
			$sql = call('sql_query', "UPDATE users SET email = '$email', pass = '$vpassword', show_email = '$hideemail' WHERE id = '$userid'");
		} else
			$sql = call('sql_query', "UPDATE users SET email = '$email', show_email = '$hideemail' WHERE id = '$userid'");
		if($sql)
			return true;
	}
}
?>