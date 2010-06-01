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
	call('redirect', 'index.php');
}
?>