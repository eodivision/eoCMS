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
function form_protection($captcha = false, $life = 15) {
	if(!is_numeric($life))
		$life = 15; // If this happens, they passed an invalid value, reset it back to 15 mins
	if(is_bool($captcha) === false)
		$captcha = false; // As above, reset if invalid
	$token = md5(uniqid(rand(), TRUE)); // Generate the token!
	$expire = time() + ($life * 60); // Timestamp of when form expires, multiply life by 60 as $life is mins not seconds
	call('sql_query', "DELETE FROM csrf_forms WHERE expire_time < ".time().""); // delete any old rows
	// Insert this all into the database, we insert the useragent too as a precaution
	if($captcha) {
		// Generate the CAPTCHA code
		$code_length = 6;
		$charset = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
		$code = '';
		for($i = 1, $cslen = strlen($charset); $i <= $code_length; ++$i) {
			$code .= $charset{rand(0, $cslen - 1)};
		}
		// encode the code
		// generate salts to hash it with, vary their length as this code will be visible
		$salt1 = call('generate_key', rand(0, 10));
		$salt2 = call('generate_key', rand(0, 10));
		$encode = md5($salt1.$code.$salt2);
		$sql = sql_query("INSERT INTO csrf_forms (token, expire_time, useragent, captcha_code, captcha_encode) VALUES ('$token', '$expire', '".$_SERVER['HTTP_USER_AGENT']."', '$code', '$encode')") or die(mysql_error());
	}
	else
		$sql = sql_query("INSERT INTO csrf_forms (token, expire_time, useragent) VALUES ('$token', '$expire', '".$_SERVER['HTTP_USER_AGENT']."')");
	// Return the token etc
	return array('token' => $token, 'captcha_encode' => $encode);
}
?>