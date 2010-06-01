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