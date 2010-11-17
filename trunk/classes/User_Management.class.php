<?php
/*
	eoCMS © 2007 - 2010, a Content Management System
	by James Mortemore
	http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
class User_Management {
	function __construct() {
		/**
		 * Handles login and populates $eocms['user'] variable
		 * Returns: @Void
		 */
		global $eocms, $sql;
		
		// Set them as guest by default
		$eocms['user']['guest'] = 1;
		// Get the domain name
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		if(isset($_COOKIE[LOGIN_COOKIE]) && (strlen($_COOKIE[LOGIN_COOKIE]) != 32 || !preg_match('/^([-a-z0-9])+$/i', $_COOKIE[LOGIN_COOKIE]))) {
			// Security problem, someone is attempting to inject
			// 32 length check as we only create a ssid with 32 characters and it only contains alphanumeric characters
			setcookie(COOKIE_NAME, '', time()-(60*60*24*100), '', $domain); // Delete the cookie
		} elseif(isset($_COOKIE[LOGIN_COOKIE])) {
			// Genuine cookie, lets check the system
			$sql -> query("SELECT permissions.variable AS variable, permissions.value AS value FROM ".PREFIX."membergroups LEFT JOIN ".PREFIX."users ON ".PREFIX."membergroups.membergroup_id = ".PREFIX."users.membergroup LEFT JOIN ".PREFIX."permissions ON ".PREFIX."permissions.membergroup_id = ".PREFIX."membergroups.membergroup_id WHERE ".PREFIX."users.ssid = '".$_COOKIE[COOKIE_NAME]."' AND ".PREFIX."permissions.membergroup_id = membergroup");
			// Yes above looks big query but it only runs if there is a cookie found, and stops the need for more than one query to log them in (i.e. set permissions etc)
			if($sql -> num_rows() != 0) {
				while($userrow = $sql -> fetch_array())
					$eocms['user'][$userrow['variable']] = $userrow['value'];
				$eocms['user']['guest'] = 0; // They are not a guest so make it so :)
			}
		}
		else {
			// No cookie found meaning they are just a guest
			$eocms['user']['membergroup'] = 1; // Set their membergroup to guest
			$guestSQL = $sql -> query("SELECT * FROM ".PREFIX."permissions WHERE membergroup_id = '1'", 'cache'); // Cache this query as it will be run very often
			foreach($guestSQL as $guest)
				$eocms['user'][$guest['variable']] = $guest['value'];
		}
	}
	public function login($usernme, $password, $remember = true) {
		/**
		 * Checks the username and password
		 * Creates the cookie which keeps users logged in
		 * Updates relative database info such as last logged in
		 * Returns: @Boolean
		 */
		global $sql;
		
		$sql -> query("SELECT * FROM users WHERE username = '$username'");
		if($sql -> num_rows() == 0) { // The username doesn't exist
			error('Wrong Username or Password'); // Dont tell them which was wrong, makes things harder for any hackers
			return false;
		} else {
			$user = $sql -> fetch_array();
			if($user['disabled'] == 1) { //oh dear, they are not allowed to login.... PWNED
				error('Your account has been disabled');
				return false;
			}
			if($username != $user['user']) { // Just to be safe, would be weird if this returned true >_>
				error('Wrong Username or Password');
				return false;
			}
			if(sha1($user['salt1'].$password.$user['salt2']) != $user['password']) { //check the password
				//lets change the salt again for this user
				$salt1 = call('generate_key', 10);
				$salt2 = call('generate_key', 10);
				//salt the password
				$password = sha1($salt1.$password.$salt2);
				$sql -> query("UPDATE users SET password = '$password', salt1 = '$salt1', salt2 = '$salt2', WHERE id = '".$user['id']."'");
				error('Wrong Username or Password');
				return false;
			}
			if($user['membergroup'] == 1) { //check if the membergroup is set to Guest
				error('Your account needs to be activated first');
				return false;
			}
			if(!errors()) {
				// Regenerate the ssid
				$ssid = random_string(32);
				// Regenerate the salts
				$salt1 = random_string(10);
				$salt2 = random_string(10);
				// Salt the password
				$password = sha1($salt1.$password.$salt2);
				// Update there IP, last login and Session ID (ssid)
				$loginSQL = $sql -> query("UPDATE users SET ssid = '$ssid', password = '$password', salt1 = '$salt1', salt2 = '$salt2', last_login = '".time()."', ip = '".call('visitor_ip')."', useragent = '".$sql -> escape(htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES))."' WHERE id = '".$user['id']."'");
				// Remove their IP from the user's online so it does not say they are a guest any more in the users online panel
				$sql -> query("DELETE FROM user_online WHERE ip = '".$this -> ip()."'");
				$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
				// Check if they want to be remembered
				if($remember)
					$time = time() + (60*60*24*100); //make it so they are logged in for a very long time
				else
					$time = time() + 3600; //Dont want to be remembered so only keep them logged in for an hour
				setcookie(COOKIE_NAME, $key, $time, '', $domain);
				if($loginSQL)
					return true;
				else
					return false;
			}
		}
	}
	public function logout() {
		/**
		 * Deletes the cookie keeping them logged in
		 * Removes them from the online list
		 * Returns: @Boolean
		 */
		global $eocms, $sql;
		
		if(isset($_COOKIE[COOKIE_NAME])) {
			//remove them from the users online list so they arent still noted as online
			$sql ->query("DELETE FROM ".PREFIX."user_online WHERE user_id = '".user('id')."'");
			$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
			//delete the cookie so they wont be logged back in again by mistake
			setcookie(COOKIE_NAME, '', time() - (60 * 60 * 24 * 100), '', $domain);
			// Note the cookie still exists in the system, so reset $eocms['user'] to instantly log them out
			unset($eocms['user']);
			// Below is effectivley same as this class construct but for guests only
			$eocms['user']['membergroup'] = 1; // Set their membergroup to guest
			$guestSQL = $sql -> query("SELECT * FROM ".PREFIX."permissions WHERE membergroup_id = '1'", 'cache');
			foreach($guestSQL as $guest)
				$eocms['user'][$guest['variable']] = $guest['value'];
			return true;
		} else
			return false;
	}
	public function ip() {
		/**
		 * Finds the correct IP Address
		 * Handles servers which do not populate the server_addr variable
		 * Returns: @String
		 */
		if(!isset($_SERVER['SERVER_ADDR']))
			$_SERVER['SERVER_ADDR'] = '';
		return ($_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR'] && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	}
	public function delete($id) {
		if(user('id') == $id)
			return ''; // Do we let them delete there own account?
	}
}
?>