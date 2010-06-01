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

$title = 'Edit Account';
$check = false;
if($user['guest'])
	$error_die[] = 'You must be logged in to use this feature';
if(isset($_POST['email']))
	$check = call('updateprofile1', $user['id'], $_POST['email'], md5($_POST['currentpassword']), $_POST['newpassword'], $_POST['vpassword'], $_POST['token'], $_POST['hide_email']);
if($check==true && !errors()) {
	$head = '<META HTTP-EQUIV="refresh" content="3;URL='.$settings['site_url'].'/?act=editaccount">';
	$body = theme('title', "Success") . theme('start_content') . 'Profile Updated!' . theme('end_content');
} else {
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
	$(document).ready(function() {
		$("#editprofile").validate();
	});</script>';
	$body = theme('title', "Edit Account Settings") . theme('start_content') . '<form action="" method="post" id="editprofile">
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
