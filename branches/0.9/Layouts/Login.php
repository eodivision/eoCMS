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
		header("Location: ".$location);
	} else {
		header("Location: index.php");
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
?>