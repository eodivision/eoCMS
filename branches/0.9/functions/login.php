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
function login($username, $password, $remember, $token='') {
	global $settings, $user, $error, $error_die;
	$result = call('sql_query', "SELECT * FROM users WHERE user = '$username' AND pass = '$password'");
	$row = call('sql_fetch_array',$result);
	// Can they login?
	if(call('sql_num_rows', $result) == 0) {
		$error[] = 'Wrong Username or Password';
		return false;
	}
	if($row['allow_login'] == 0) {
		$error[] = 'Sorry, You can\'t login';
		return false;
	}
	if($username != $row['user']) {
		$error[] = 'Wrong Username or Password';
		return false;
	}
	if($password != $row['pass']) {
		$error[] = 'Wrong Username or Password';
		return false;
	}
	if($row['membergroup'] == 1) {
		$error[] = 'Sorry, You can\'t login your account needs to be activated';
		return false;
	}
	if(!errors()) {
		$key = md5(call('generate_key', 32));
		//Update there IP, last login and Session ID
		$sql = call('sql_query', "UPDATE users SET ssid = '$key', lastlogin = '".time()."', ip = '".call('visitor_ip')."', agent = '".$_SERVER['HTTP_USER_AGENT']."' WHERE id = '".$row['id']."'");
		//remove their IP from the user's online so it does not say they are a guest any more in the users online panel
		$query = call('sql_query', "DELETE FROM user_online WHERE ip = '".call('visitor_ip')."'");
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		//check if they want to be remembered
		if($remember == 'on')
			$time = time() + (60*60*24*100); //make it so they are logged in for a very long time
		else
			$time = time() + 3600; //Dont want to be remembered so only keep them logged in for an hour
		setcookie(COOKIE_NAME, $key, $time, '', $domain);
		if($sql)
			return true;
	}
}
?>
