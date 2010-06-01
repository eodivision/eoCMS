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

function sendemail($to, $subject, $body, $from = $settings['email'], $fromName = $settings['site_name'], $type = $settings['mail']) {
	global $settings, $error;
	$mail = new PHPMailer();
	if($type == 'sendmail')
		$mail->IsMail();
	elseif($type == 'smtp') {
		$mail->IsSMTP();
		$mail->Host = $settings['smtp_host'];
		$mail->SMTPAuth = true;
		$mail->Username = $settings['smtp_username'];
		$mail->Password = $settings['smtp_password'];
	}
	$mail->FromName = $fromName;
	$mail->From = $from;
	$mail->AddAddress($to);
	$mail->Subject = $subject;
	$mail->Body = $body;
	if(!$mail->Send()) {
		$error[] = "Error sending email: " . $mail->ErrorInfo;
		return false;
	} else
		return true;
}
?>