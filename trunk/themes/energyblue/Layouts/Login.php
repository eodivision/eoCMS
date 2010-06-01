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

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$title = $LOGIN_LANG["title"];
$check = false;
if($_POST){
	$check = call('login', $_POST['username'], md5($_POST['password']), $_POST['remember']);
}
if($check==true && !errors()) {
# check the referer
	if(preg_match('/'.preg_quote($settings['site_url'], '/').'/', $_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''.$settings['site_url'].'/index.php?act=login' && $_SERVER['HTTP_REFERER'] != ''.$settings['site_url'].'/index.php?act=register') {
# lets decode some values to prevent any errors with the redirect url
		$location = str_replace('&#38;', '&', $_SERVER['HTTP_REFERER']);
# looks like they came from a page on the site, lets take them back to that page :)
		call('redirect', $location);
	} else {
		call('redirect', 'index.php');
	}
} else {
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#login").validate();
});
</script>';
	$body = theme('title', $LOGIN_LANG["theme_title"]) . theme('start_content') . '<form action="" method="post" id="login" class="frm login-form">
<table class="login-area">
  <tr>
    <td class="login-user">' . $LOGIN_LANG["username"] . ':</td>
    <td><input name="username" type="text" size="8" class="frm-inp login-inp login-user-inp required" value="' . (isset($_POST['username']) ? $_POST['username'] : '') . '"/></td>
  </tr>
  <tr>
    <td class="login-pass">' . $LOGIN_LANG["password"] . ':</td>
    <td><input name="password" type="password" size="8" class="frm-inp login-inp login-pass-inp required"/></td>
  </tr>
  <tr>
    <td colspan="2" class="login-remember">' . $LOGIN_LANG["remember_me"] . '? <input type="checkbox" class="frm-inp frm-chk login-inp login-remember-chk"  name="remember" checked="checked" /></td>
  </tr>
  <tr>
    <td colspan="2" class="login-submit"><input name="login" type="submit" value=" ' . $LOGIN_LANG["btn_login"] . ' " class="form-btn login-btn login-submit-btn"/></td>
  </tr>
 <tr>
    <td colspan="2" class="login-links">';
    if($settings['registration'] == 'on')
       $body .= '[ <a id="registerLink" class="text-link login-link login-register-link" href="'.$settings['site_url'].'/index.php?act=register">' . $LOGIN_LANG["register"] . '</a> ] ';
    $body .= '[ <a id="forgotLink" class="text-link login-link login-forgot-link" href="'.$settings['site_url'].'/index.php?act=forgotpassword">' . $LOGIN_LANG["forgot_password"] . '?</a> ] </td>
  </tr>
</table>
</form>' . theme('end_content');
}
#brute force protection based on plugin for v0.9
//set how long we want them to have to wait after 5 wrong attempts
$time = 1800; //make them wait 30 mins
$sql = call('sql_query', "SELECT * FROM login_attempts WHERE ip = '".call('visitor_ip')."'");
$numrows = call('sql_num_rows', $sql);
if($numrows != 0) {
	$p = call('sql_fetch_array', $sql);
	if($p['attempts'] >= 5) {
		$error_die[] = 'You have reached your maxiumum attempts. Please try again later';
		//delete any timed outs
		$sql = call('sql_query', "DELETE FROM login_attempts WHERE ip = '".call('visitor_ip')."' AND time < ".(time() - $time)."");
	} elseif($p['attempts'] <= 5 && isset($_POST['login'])) {
		$sql = call('sql_query', "UPDATE login_attempts SET attempts = attempts+1, time = '".time()."' WHERE ip = '".call('visitor_ip')."'");
	}
}
if(isset($_POST['login']) && $check === false && $numrows == 0) {
	//login failed lets add them in as a failed attempt
	$sql = call('sql_query', "INSERT INTO login_attempts (ip, time, attempts) VALUES ('".call('visitor_ip')."', '".time()."', '1')");
}
?>