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
function login($username, $password, $remember, $token='') {
	global $settings, $user, $error, $error_die;
	$result = call('sql_query', "SELECT * FROM users WHERE user = '$username'");
	// Can they login?
	if(call('sql_num_rows', $result) == 0) { //the username doesnt exist
		$error[] = 'Wrong Username or Password';
		return false;
	} else {
		$row = call('sql_fetch_array', $result);
		if($row['allow_login'] == 0) { //oh dear, they are not allowed to login.... PWNED
			$error[] = 'Sorry, You can\'t login';
			return false;
		}
		if($username != $row['user']) { //just to be safe, would be weird if this returned true >_>
			$error[] = 'Wrong Username or Password';
			return false;
		}
		if(md5($row['salt1'].$password.$row['salt2']) != $row['pass']) { //check the password
			//lets change the salt again for this user, make any hackers' lives a nightmare :D
			$salt1 = call('generate_key', 10);
			$salt2 = call('generate_key', 10);
			//salt the password
			$password = md5($salt1.$password.$salt2);
			$sql = call('sql_query', "UPDATE users SET pass = '$password', salt1 = '$salt1', salt2 = '$salt2', WHERE id = '".$row['id']."'");
			$error[] = 'Wrong Username or Password';
			return false;
		}
		if($row['membergroup'] == 1) { //check if the membergroup is set to Guest
			$error[] = 'Sorry, You can\'t login your account needs to be activated';
			return false;
		}
		if(!errors()) {
			//generate the ssid
			$key = md5(call('generate_key', 32));
			//update the salts
			$salt1 = call('generate_key', 10);
			$salt2 = call('generate_key', 10);
			//salt the password
			$password = md5($salt1.$password.$salt2);
			//Update there IP, last login and Session ID
			$sql = call('sql_query', "UPDATE users SET ssid = '$key', pass = '$password', salt1 = '$salt1', salt2 = '$salt2', lastlogin = '".time()."', ip = '".call('visitor_ip')."', agent = '".$_SERVER['HTTP_USER_AGENT']."' WHERE id = '".$row['id']."'");
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
}
?>
