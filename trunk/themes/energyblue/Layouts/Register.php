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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if(!$user['guest'])
	$error_die[] = $REGISTER_LANG["error_die"];
$title = $REGISTER_LANG["title"];
if($settings['registration'] != 'on')
	$error_die[] = $REGISTER_LANG["error_die_no_reg"];
if(isset($_POST['username']))
	$check = call('register', $_POST['username'], md5($_POST['password']), md5($_POST['vpassword']), $_POST['email'], $_POST['token'], $_POST['captcha'], $_POST['tos']);
else
	$check = false;
if($check==true && !errors()) {
	if($settings['register_approval'] == 'email') {
		$body = theme('title', $REGISTER_LANG["theme_title"]).theme('start_content').$REGISTER_LANG["body_content_email"].theme('end_content');
	}
	elseif($settings['register_approval'] == 'admin') {
		$body = theme('title', $REGISTER_LANG["theme_title"]).theme('start_content').$REGISTER_LANG["body_content_admin"].theme('end_content');
	}
	elseif($settings['register_approval'] == 'none') {
		$body = theme('title', $REGISTER_LANG["theme_title"]).theme('start_content').$REGISTER_LANG["body_content"].theme('end_content');
	}
} else {
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#register").validate({rules: {
								username: {
									required: true,
									minlength: 2,
									remote: "index.php?act=ajax&m=usernamecheck"
								},
								vpassword: {
									required: true,
									equalTo: "#password"
								}
							}, messages: {
								username: {
									remote: jQuery.format("{0} is already in use")
								}
							}
							});
});</script>';
	$body =  theme('title', $REGISTER_LANG["btn_register"]) .  theme('start_content') . '<form action="" method="post" id="register">
    <table>
      <tr><td>'.$REGISTER_LANG["username"].':</td><td><input name="username" type="text" maxlength="16" value="'.(isset($_POST['username']) ? $_POST['username'] : '').'" class="required username"/></td></tr>
      <tr><td>'.$REGISTER_LANG["password"].':</td><td><input name="password" id="password" type="password" class="required" minlength="6"/></td></tr>
      <tr><td>'.$REGISTER_LANG["verify_password"].':</td><td><input name="vpassword" id="vpassword" type="password" class="required vpassword" minlength="6"/></td></tr>
      <tr><td>'.$REGISTER_LANG["email_address"].':</td><td><input name="email" type="text" value="'.(isset($_POST['email']) ? $_POST['email'] : '').'" class="required email"/></td></tr>';
	if($settings['register_captcha'] =='on')
		$body .= call('show_captcha');
	if(!empty($settings['tos'])) {
		$body .= '<tr class="subtitlebg">
		<td align="center" colspan="2">'.$REGISTER_LANG["tos"].'</td>
		</tr><tr>
			<td colspan="3"><div style="width: 100%; overflow: auto; height: 10em;">'.htmlspecialchars_decode($settings['tos']).'</div></td>
		</tr>
		<tr>
			<td align="center" colspan="2">'.$REGISTER_LANG["tos_agree"].' <input type="checkbox" name="tos" /></td>
		</tr>';	
	}
	$body.=call('form_token') . '
      <tr><td colspan="2"><input name="register" type="submit" value="'.$REGISTER_LANG["btn_register"].'"/></td></tr>
    </table>
  </form>' . theme('end_content');
}
?>