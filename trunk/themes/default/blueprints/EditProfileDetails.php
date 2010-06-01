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

$theme['title'] = 'Edit Account';
$check = false;
if($user['guest'])
	$error_die[] = 'You must be logged in to use this feature';
if(isset($_POST['email']))
	$check = call('updateprofile1', $user['id'], $_POST['email'], md5($_POST['currentpassword']), $_POST['newpassword'], $_POST['vpassword'], $_POST['token'], $_POST['hide_email']);
if($check==true && !errors()) {
	$theme['head'] = '<META HTTP-EQUIV="refresh" content="3;URL='.$settings['site_url'].'/?act=editaccount">';
	$theme['body'] = theme('title', "Success") . theme('start_content') . 'Profile Updated!' . theme('end_content');
} else {
	$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
	$(document).ready(function() {
		$("#editprofile").validate();
	});</script>';
	$theme['body'] = theme('title', "Edit Account Settings") . theme('start_content') . '<form action="" method="post" id="editprofile">
		<table>
			<tr>
				<td colspan="2">'.theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=editprofile">Edit Profile</a>', '<a href="'.$settings['site_url'].'/index.php?act=editaccount">Account</a>')).'</td>
			</tr>
			<tr>
	<td>Email:<br /> <span class="small-text">This must be filled and valid</span></td><td><input type="text" name="email" value="'.(!errors() ? $user['email'] : $_POST['email']).'" class="required email" /></td>
	</tr>
		<tr><td>New Password:</td><td><input name="newpassword" type="password" class="input" minlength="6"/></td></tr>
		  <tr><td>Confirm New Password:</td><td><input name="vpassword" type="password" minlength="6" /></td></tr>
		  <tr><td>Show Email?</td><td><input type="radio" name="hide_email" value="1"'.($user['show_email'] =='1' ? 'checked' : '').'> Yes <input type="radio" name="hide_email" value="0"'.($user['show_email'] =='0' ? 'checked' : '').'> No</td></tr>
		  <tr><td>Current Password: <br /><span class="small-text">Required for security to update your profile</span></td><td><input name="currentpassword" type="password" class="required"/></td></tr>' . call('form_token') . '
		<tr><td colsapn="2"><input name="update" type="submit" value="Update"/></td></tr>
		</form>
		</table>' . theme('end_content');
}
?>
