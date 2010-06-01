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
function sitesettings($registration, $reg_approval, $email, $mail, $host, $username, $password, $mode, $message, $captcha, $topics, $posts, $name, $tos) {
	global $user, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(!errors()) {
		$reg = call('sql_query', "UPDATE settings SET value = '$registration' WHERE variable = 'registration'");
		$register_approval = call('sql_query', "UPDATE settings SET value = '$reg_approval' WHERE variable = 'register_approval'");
		$emailq = call('sql_query', "UPDATE settings SET value = '$email' WHERE variable = 'email'");
		$mail_type = call('sql_query', "UPDATE settings SET value = '$mail' WHERE variable = 'mail'");
		$smtp_host = call('sql_query', "UPDATE settings SET value = '$host' WHERE variable = 'smtp_host'");
		$smtp_username = call('sql_query', "UPDATE settings SET value = '$username' WHERE variable = 'smtp_username'");
		$smtp_password = call('sql_query', "UPDATE settings SET value = '$password' WHERE variable = 'smtp_password'");
		$maintenance_mode = call('sql_query', "UPDATE settings SET value = '$mode' WHERE variable = 'maintenance_mode'");
		$maintenance_message = call('sql_query', "UPDATE settings SET value = '$message' WHERE variable = 'maintenance_message'");
		$register_captcha = call('sql_query', "UPDATE settings SET value = '$captcha' WHERE variable = 'register_captcha'");
		$topic_page = call('sql_query', "UPDATE settings SET value = '$topics' WHERE variable = 'topics_page'");  
		$posts_topic = call('sql_query', "UPDATE settings SET value = '$posts' WHERE variable = 'posts_topic'");
		$site_name = call('sql_query', "UPDATE settings SET value = '$name' WHERE variable = 'site_name'");
		$terms = call('sql_query', "UPDATE settings SET value = '$tos' WHERE variable = 'tos'");
		if($reg && $register_approval && $emailq && $mail_type && $smtp_host && $smtp_username && $smtp_password && $maintenance_mode && $maintenance_message && $register_captcha && $topic_page && $posts_topic && $site_name && $terms)
			return true;
	}
}
?>