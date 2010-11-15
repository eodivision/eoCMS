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
			// 32 length check as we md5 the contents on its creation and md5 only contains alphanumeric characters
			setcookie(COOKIE_NAME, '', time()-(60*60*24*100), '', $domain); // Delete the cookie
		} elseif(isset($_COOKIE[LOGIN_COOKIE])) {
			// Genuine cookie, lets check the system
			$sql -> query("SELECT permissions.variable AS variable, permissions.value AS value FROM ".PREFIX."membergroups LEFT JOIN ".PREFIX."users ON ".PREFIX."membergroups.membergroup_id = ".PREFIX."users.membergroup LEFT JOIN ".PREFIX."permissions ON ".PREFIX."permissions.membergroup_id = ".PREFIX."membergroups.membergroup_id WHERE ".PREFIX."users.ssid = '".$_COOKIE[COOKIE_NAME]."' AND ".PREFIX."permissions.membergroup_id = membergroup");
			// Yes above looks big query but it only runs if there is a cookie found, and stops the need for more than one query to log them in
			if($sql -> num_rows() != 0) {
				while($userrow = $sql -> fetch_array())
					$eocms['user'][$userrow['variable']] = $userrow['value'];
				$eocms['user']['guest'] = 0; // They are not a guest so make it so :)
			}
		}
		else {
			// No cookie found meaning they are just a guest
			$eocms['user']['membergroup'] = 1; // Set their membergroup to guest
			$guestSQL = $sql -> query("SELECT * FROM permissions WHERE membergroup_id = '1'", 'cache'); // Cache this query as it will be run very often
			foreach($guestSQL as $guest)
				$eocms['user'][$guest['variable']] = $guest['value'];
		}
	}
	public function login($usernme, $password, $remember) {
		
	}
	public function ip() {
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