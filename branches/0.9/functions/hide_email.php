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

# Javascript email encoder by Tyler Akins
#  http://rumkin.com/tools/mailto_encoder/

function hide_email($email, $subject='', $class='', $image=false) {
	global $settings;
	$email = explode('@', $email);
	$user = $email[0];
	$host = $email[1];
	$MailLink = '<a id="emailLink" class="text-link ' . $class . ' email-link" href="mailto:' . $user . '@' . $host;
	if ($subject != '')
		$MailLink .= '?subject=' . urlencode($subject);
	$MailLink .= '">';
	if($image === false)
		$MailLink .= $user . '@' . $host;
	else
		$MailLink .= '<img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/email.png" title="'.$user.'@'.$host.'" />';
	$MailLink .= '</a>';
	$MailLetters = '';
	for ($i=0; $i<strlen($MailLink); $i++) {
		$l = substr($MailLink, $i, 1);
		if (strpos($MailLetters, $l)===false) {
			$p = rand(0, strlen($MailLetters));
			$MailLetters = substr($MailLetters, 0, $p) .
			$l . substr($MailLetters, $p, strlen($MailLetters));
		}
	}

	$MailLettersEnc = str_replace("\\", "\\\\", $MailLetters);
	$MailLettersEnc = str_replace("\"", "\\\"", $MailLettersEnc);

	$MailIndexes = '';
	for ($i=0; $i<strlen($MailLink); $i++) {
		$index = strpos($MailLetters, substr($MailLink, $i, 1));
		$index += 48;
		$MailIndexes .= chr($index);
	}
	$MailIndexes = str_replace("\\", "\\\\", $MailIndexes);
	$MailIndexes = str_replace("\"", "\\\"", $MailIndexes);
	$res = "<script type=\"text/javascript\">
<!--
ML='".$MailLettersEnc."';
MI='".$MailIndexes."';
ML=ML.replace(/xxxx/g, '<');
MI=MI.replace(/xxxx/g, '<');
OT=\"\";
for(j=0;j < MI.length;j++){
	OT+=ML.charAt(MI.charCodeAt(j)-48);
}
document.write(OT);
// -->
</script>";
	return $res;
}
?>