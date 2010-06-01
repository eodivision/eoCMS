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
function register($username, $password, $vpassword, $email, $token, $captcha, $tos) {
	global $settings, $error, $error_die;
	call('checktoken', $token);
	if(!empty($settings['tos']) && $tos != 'on') {
		$error[] = 'You must agree to the Terms of Service to register';
		return false;
	}
	if($settings['register_captcha'] =='on')
		$captcha = call('captchacheck', $captcha);
	if($settings['register_captcha'] =='on' && $captcha == false)
		return false;
	$sql = call('sql_query', "SELECT user FROM users WHERE user = '$username'");
	if (call('sql_num_rows', $sql) != 0) {
		$error[] = 'Username is already taken';
		return false;
	}
	$sql = call('sql_query', "SELECT email FROM users WHERE email = '$email'");
	if (call('sql_num_rows', $sql) != 0) {
		$error[] = 'Email address is already in use';
		return false;
	}
	if (empty($username)) {
		$error[] = 'You did not enter a username'; //idiot how on earth are people meant to know who u are!
		return false;
	}
	if (strlen($password) < 6) {
		$error[] = 'password must be 6 characters or longer!';
		return false;
	}
	$decodedusername = html_entity_decode($username, ENT_QUOTES);
	if (strlen($decodedusername) > 16) {
		$error[] = 'Your username is too long, it must be below 16 characters';
		return false;
	}
	if ($password != $vpassword) {
		$error[] = 'The passwords entered to do not match';
		return false;
	}
	if (!preg_match("/^([a-z0-9._-](\+[a-z0-9])*)+@[a-z0-9.-]+\.[a-z]{2,6}$/i", $email)) {
		$error[] = 'The email address entered is not valid';
		return false;
	}
	if (strpos($username, ',') !== false) {
		$error[] = 'Commas (,) are not allowed in a username';
		return false;
	}
	if ($settings['register_approval'] == 'none')
		$group = '2';
	else
		$group = '1';
	if(!errors()) {
		$sql = call('sql_query', "INSERT INTO users (user, pass, email, ip, regdate, lastlogin, membergroup, theme) VALUES('$username', '$password', '$email', '" . call('visitor_ip') . "', '" . time() . "', 'Never', '$group', '".$settings['site_theme']."')");
		if($settings['register_approval'] == 'email') {
			$mail = new PHPMailer();
			//last inserted ID
			$id = call('sql_insert_id');
			//Generate MD5 hash key
			$key = call('generate_key', 6);
			//Put together the key string
			$key_string = "key=$key&id=$id";
			//Insert into database
			$query = call('sql_query', "INSERT INTO activation_keys (user_id , key_number) VALUES ('$id', '$key')");
			if ($settings['mail'] == 'sendmail')
				$mail->IsMail();
			elseif ($settings['mail'] == 'smtp') {
				$mail->IsSMTP();
				$mail->Host = $settings['smtp_host'];
				$mail->SMTPAuth = true;
				$mail->Username = $settings['smtp_username'];
				$mail->Password = $settings['smtp_password'];
			}
			$mail->FromName = $settings['site_name'];
			$mail->From = $settings['email'];
			$mail->AddAddress("$email");
			$mail->Subject = 'Account Activation at' . $settings['site_name'] . '';
			$mail->Body = "Please click the following link to activate your account:\n--------\n" . $settings['site_url'] . "/index.php?act=activate&$key_string";
			if (!$mail->Send()) {
				$error[] = "Error sending: " . $mail->ErrorInfo;
				return false;
			}
			else
				return true;
		} else
			return true;
	}
}
?>