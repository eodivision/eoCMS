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
function updateprofile2($userid, $currentpass, $avatar, $signature, $token, $location, $bday1, $bday2, $bday3, $msn, $icq, $yim, $aim, $offset, $gender) {
	global $user, $error, $error_die;
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
	call('checktoken', $token);
	$bday = (!empty($bday1) ? $bday1.'/'.$bday2.'/'.$bday3 : '');
	if(!preg_match("/^[a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i", $avatar) && !empty($avatar)) {
		$error[] = 'The avatar url entered is not valid';
		return false;
	}
	if(!empty($avatar)) {
		$imagecheck = call('image_info', $avatar);
		if($imagecheck == false)
			return false;
	} else
		$imagecheck = true;
	//revert the sanitization to get a correct character length reading
	$signature_decode = htmlspecialchars_decode($signature, ENT_QUOTES);
	if(strlen($signature_decode) > 300 && !empty($signature)) {
		$error[] = 'The Signature is too long';
		return false;
	}
	if($offset > 24 || $offset < -24){
		$error[] = 'The offset entered is not valid';
		return false;
	}
	if(!errors() && $imagecheck != false) {
		$sql = call('sql_query', "UPDATE users SET avatar = '$avatar', signature= '$signature', birthday = '$bday', location = '$location', msn = '$msn', icq = '$icq', yim = '$yim', aim = '$aim', gender = '$gender', offset = '$offset' WHERE id = '$userid'");
		if($sql)
			return true;
	}
}
?>