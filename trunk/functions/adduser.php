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
function adduser($username, $password, $vpassword, $email) {
	global $settings, $error, $error_die;
	if($settings['register_approval'] == 'none')
		$group = '2';
	else
		$group = '1';
	if(!errors()) {
		//generate the salts
		$salt1 = call('generate_key', 10);
		$salt2 = call('generate_key', 10);
		//salt the password
		$password = md5($salt1.$password.$salt2);
		$sql = call('sql_query', "INSERT INTO users (user, pass, salt1, salt2, email, ip, regdate, lastlogin, membergroup, theme) VALUES('$username', '$password', '$salt1', '$salt2', '$email', '" . call('visitor_ip') . "', '" . time() . "', 'Never', '$group', '".$settings['site_theme']."')");
		if($settings['register_approval'] == 'email') {
			//last inserted ID
			$id = call('sql_insert_id');
			//Generate MD5 hash key
			$key = call('generate_key', 10);
			//Put together the key string
			$key_string = 'key='.$key.'&id='.$id;
			//Insert into database
			$query = call('sql_query', "INSERT INTO activation_keys (user_id , key_number) VALUES ('$id', '$key')");
			if(!call('sendemail', $email, 'Account Activation at '.$settings['site_name'], "Please click the following link to activate your account:\n--------\n".$settings['site_url']."/index.php?act=activate&$key_string", $settings['email']))
				return false;
			else
				return true;
		} elseif($sql)
			return true;
		else
			return false;
	}
}
?>