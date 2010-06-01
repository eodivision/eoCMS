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
function forgotpassword($email) {
	global $settings, $error, $error_die;
	if(empty($email)) {
		$error[] = 'You must enter an email';
		return false;
	}
	$sql = call('sql_query', "SELECT email, user FROM users WHERE email = '$email'");
	if(call('sql_num_rows', $sql) == 0) {
		$error[] = 'This email is not registered to any existing users';
		return false;
	}
	if(!errors()) {
		$mail = new PHPMailer();
		$fetch = call('sql_fetch_array',$sql);
		$newpass = substr(str_shuffle('qwertyuiopasdfghjklmnbvcxz0987612345'), 0, 7);
		$sql = call('sql_query', "UPDATE users SET pass = '" . md5($newpass) . "' WHERE email = '$email'");
		if($settings['mail'] == 'sendmail')
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
		$mail->Subject = 'New Password for ' . $settings['site_name'] . '';
		$mail->Body = "Hi " . $fetch['user'] . ",\n Your new password as requested is:\n" . $newpass;
		if (!$mail->Send()) {
			$error[] = "Error sending: " . $mail->ErrorInfo;
			return false;
		} else
			return true;
	}
}
?>