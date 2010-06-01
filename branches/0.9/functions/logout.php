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
function logout() {
	global $settings, $user;
	if(isset($_COOKIE[COOKIE_NAME])) {
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		//delete the cookie so they wont be logged back in again by mistake
		setcookie(COOKIE_NAME, '', time() - (60 * 60 * 24 * 100), '', $domain);
	}
	//remove them from the users online list so they arent still noted as online
	$deleteuseronline = call('sql_query', "DELETE FROM user_online WHERE user_id = '".$user['id']."'");
	//destroy their session, basically logs them out ;)
	session_destroy();
	header("Location: index.php");
	exit;
}
?>