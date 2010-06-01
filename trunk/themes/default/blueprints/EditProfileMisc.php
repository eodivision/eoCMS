<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if($user['guest']) {
$error_die[] = 'You must be logged in to use this feature';
}
$check = false;
$theme['title'] = 'Edit Miscellaneous Settings';
if($_POST)
{
$check = call('updateprofile2', $user['id'], md5($_POST['currentpassword']), $_POST['avatar'], $_POST['signature'], $_POST['token'], $_POST['location'], $_POST['bday1'], $_POST['bday2'], $_POST['bday3'], $_POST['msn'], $_POST['icq'], $_POST['yim'], $_POST['aim'], $_POST['offset'], $_POST['gender']);
}
if($check==true && !errors())
	call('redirect', 'index.php?act=editmiscellaneous');
else {
$birthd = explode("/", $user['birthday']);
if(!isset($birthd[0]))
	$birthd[0] = '';
if(!isset($birthd[1]))
	$birthd[1] = '';
	if(!isset($birthd[2]))
	$birthd[2] = '';
$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
countfield.value = maxlimit - field.value.length;
}
function AutoDetectOffset() { 
var TimeofLocal = new Date();
var TimeofServer = new Date("' . date("F j, Y, g:i A") . '");
var daydifference = Math.round((TimeofLocal.getTime() - TimeofServer.getTime())/3600000);
daydifference %= 24;
$("#offset").val(daydifference);
}
$(document).ready(function() {
	$("#editprofile").validate();
});
</script>';
$theme['body'] = theme('title', "Update Profile") . theme('start_content') . '<form action="" method="post" id="editprofile">
    <table>
		<tr>
			<td colspan="2">'.theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=editprofile">Edit Profile</a>', '<a href="'.$settings['site_url'].'/index.php?act=editmiscellaneous">Miscellaneous Settings</a>')).'</td>
		</tr>';
	if(!empty($user['avatar'])) {
	$theme['body'] .= '<tr><td>Current Avatar:</td><td><img src="' . $user['avatar'] . '" /></td></tr>';
	}
	$theme['body'] .= '<tr><td>Url to Avatar: <br /><span class="small-text">Must include http://</span></td><td><input name="avatar" type="text" class="url" value="'; if(!errors()) { $theme['body'] .= $user['avatar']; } else { $theme['body'] .= $_POST['avatar']; }
	$theme['body'] .= '"/></td></tr>';
	$theme['body'].='<tr><td>Signature:</td><td><textarea name="signature" id="signature" style="width: 100%;" rows="3" onKeyDown="textCounter(this.form.signature,this.form.characters,300);" onKeyUp="textCounter(this.form.signature,this.form.characters,300);">'; if(!errors()) { $theme['body'] .= $user['signature']; } else { $theme['body'] .= $_POST['signature']; } $theme['body'] .= '</textarea><br>Characters Left:<input readonly type=text name=characters size="3" maxlength="3" value="300"></td></tr>';
	$theme['body'].='<tr><td>Birthday Day (DD) - Month (MM) - Year (YYYY):</td><td><input type="text" value="' . $birthd[0] . '" maxlength="2" size="2" name="bday1" class="digits" minlength="2"/> - <input type="text" value="' . $birthd[1] . '" maxlength="2" size="2" name="bday2" class="digits" minlength="2"/> - <input type="text" value="' . $birthd[2] . '" maxlength="4" size="4" name="bday3" class="digits" minlength="4"/></td></tr>
	  <tr><td>Location:</td><td><input name="location" type="text" class="input" value="' . $user['location'] . '"/></td></tr>
	  <tr><td><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_msn.gif"/>MSN <br /><span class="small-text">This is your MSN email address</span></td><td><input type="text" value="' . $user['msn'] . '" name="msn"/></td></tr>
	  <tr><td><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_icq.gif"/>ICQ <br /><span class="small-text">Your ICQ number</span></td><td><input type="text" value="' . $user['icq'] . '" name="icq"/></td></tr>
	  <tr><td><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_yahoo.gif"/>YIM <br /><span class="small-text">Your Yahoo Instant Messenger nickname</span></td><td><input type="text" value="' . $user['yim'] . '" name="yim"/></td></tr>
	  <tr><td><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_aim.gif"/>AIM <br /><span class="small-text">Your AOL Instant Messenger nickname</span></td><td><input type="text" value="' . $user['aim'] . '" name="aim"/></td></tr>
	  <tr><td>Time Offset: <br /><span class="small-text"></span></td><td><input type="text" value="' . $user['offset'] . '" maxlength="5" size="5" name="offset" id="offset"/> <a onclick="AutoDetectOffset();" style="cursor: pointer;">Automatically Detect</a><br /><span class="small-text">Current Server Time: ' . date("F j, Y, g:i A") . '</span></td></tr>
	  <tr><td>Gender: </td><td><select name="gender"><option value="0"'; if(!errors() && ($user['gender'] == '0' || empty($user['gender']))) { $theme['body'] .= ' selected="selected"'; } $theme['body'].='></option><option value="1"'; if(!errors() && $user['gender'] == '1') { $theme['body'] .= ' selected="selected"'; } $theme['body'].='>Male</option><option value="2"'; if(!errors() && $user['gender'] == '2') { $theme['body'] .= ' selected="selected"'; } $theme['body'].='>Female</option></select></td></tr>
	  <tr><td>Current Password: <br /><span class="small-text">Required for security to update your profile</span></td><td><input name="currentpassword" type="password" class="required"/></td></tr>
    <tr><td colsapn="2">' . call('form_token') . '<input name="update" type="submit" value="Update"/></td></tr>
    </form>
    </table>' . theme('end_content');

}
?>