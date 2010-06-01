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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if(!$user['guest']) {
	$body .= '<div class="panel-header">'.theme('title', $user['user']) . '</div>'.theme('start_content_panel');
	$sql = call('sql_query', "SELECT * FROM pm WHERE to_send = '" . $user['id'] . "' AND mark_delete = '0'");
	$new = 0;
	while($r = call('sql_fetch_array', $sql)) {
		if($r['mark_read'] =='0') {
		$new++;
		}
	}
	if($new>0 && isset($user['auto_pm']) && $user['auto_pm']=='1') {
		$body .= '<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
if (confirm("You have recieved new messages \\nView them now?"))
	window.open("' . $settings['site_url'] . '?act=pm");
// ]]>
</script>';
	}
	$body .= '
<table width="100%" class="pnl-area pnl-users-info">
  <tr>
    <td class="pnl-users-info-area">';
	if(!empty($user['avatar'])) {
		$body .= '
<br />
<div align="center" class="pnl-users-info-avatar"><img class="pnl-users-info-avatar-image" src="' . $user['avatar'] . '" alt="Avatar" /></div>';
	}
	$body .= '
<br />
<div class="pnl-users-info-msg">'.$PNL_LANG["you_have"].'<strong> <a class="text-link pnl-link pnl-users-info-msg-link" href="'.$settings['site_url'].'/index.php?act=pm" title=" ' . $PNL_LANG["your_messages"] . ' ">' . call('sql_num_rows', $sql).$PNL_LANG["messages"].'</a></strong>'; 
if($new>0) 
	$body .=  ', <strong>' . $new . '</strong> '.$PNL_LANG["are_new"]; 
$body .='</div>
<br />
<div class="pnl-users-info-links"> [&nbsp;<a id="userInfoSendPMLink" class="text-link pnl-link panels-users-info-sendpm-link" href="'.$settings['site_url'].'/index.php?act=sendpm" title=" ' . $PNL_LANG["send_pm"] . ' ">' . str_replace(' ','&nbsp;',$PNL_LANG["send_pm"]) . '</a>&nbsp;] [&nbsp;<a id="userInfoEditLink" class="text-link pnl-link panels-users-info-edit-link" href="'.$settings['site_url'].'/index.php?act=editprofile" title=" ' . $PNL_LANG["edit_profile"] . ' ">' . str_replace(' ','&nbsp;',$PNL_LANG["edit_profile"]) . '</a>&nbsp;] [&nbsp;<a id="userInfoLogoutLink" class="text-link pnl-link pnl-users-info-logout-link" href="'.$settings['site_url'].'/index.php?act=logout&amp;'.$authid.'" title=" ' . $PNL_LANG["logout"] . ' ">' . str_replace(' ','&nbsp;',$PNL_LANG["logout"]) . '</a>&nbsp;] </div></td>
  </tr>
</table>';
}else {
	$PNL_LANG = call("uselangfile","login");
	$body .= '<div class="panel-header">'.theme('title', $PNL_LANG["welcome_guest"]) . '</div>'.theme('start_content_panel');
	$head .=  '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#login").validate();
});
</script>';
	$body .=  '<form class="frm login-form pnl-users-info-login-frm" action="'.$settings['site_url'].'/index.php?act=login" method="post" id="login">
<table width="100%" class="login-area pnl-area pnl-users-info">
  <tr>
    <td class="pnl-text login-user pnl-users-info-login-user">' . $PNL_LANG["username"] . ':</td>
    <td><input class="pnl-inp pnl-users-info-login-user-inp required" name="username" type="text" size="8" value="' . (isset($_POST['username']) ? $_POST['username'] : '') . '"/></td>
  </tr>
  <tr>
    <td class="pnl-text pnl-users-info-login-pass">' . $PNL_LANG["password"] . ':</td>
    <td><input class="frm-inp login-inp pnl-inp pnl-users-info-login-pass-inp required" name="password" type="password" size="8" value=""/></td>
  </tr>
  <tr>
    <td colspan="2" class="pnl-text pnl-users-info-remember">' . str_replace(' ','&nbsp;',$PNL_LANG["remember_me"]) . '? <input class="frm-inp frm-chk login-inp pnl-inp pnl-chk pnl-users-info-remember-chk" type="checkbox" name="remember" checked="checked" /></td>
  </tr>
  <tr>
    <td colspan="2" class="pnl-btns pnl-users-info-login"><input class="form-btn pnl-btn login-btn login-submit-btn pnl-users-info-login-btn" name="login" type="submit" value=" ' . str_replace(' ','&nbsp;',$PNL_LANG["btn_login"]) . ' "/></td>
  </tr>
  <tr>
    <td colspan="2" class="text-links pnl-text login-links pnl-users-info-links"> ';
	if($settings['registration'] == 'on')
		$body.= '[&nbsp;<a class="text-link login-link pnl-link pnl-users-info-link login-register-link" href="'.$settings['site_url'].'/index.php?act=register">' . str_replace(' ','&nbsp;',$PNL_LANG["register"]) . '</a>&nbsp;]';
	$body .= '[&nbsp;<a class="text-link login-link pnl-link pnl-users-info-link login-forgot-link" href="'.$settings['site_url'].'/index.php?act=forgotpassword">' . str_replace(' ','&nbsp;',$PNL_LANG["forgot_password"]) . '?</a>&nbsp;] </td>
  </tr>
</table>
</form>';
}
$body .= theme('end_content');
?>