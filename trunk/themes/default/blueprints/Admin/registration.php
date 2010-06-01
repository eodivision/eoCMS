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
if(isset($_POST['submit'])) {
	$check = call('sitesettings', array('registration' => $_POST['registration'], 'register_approval' => $_POST['reg_approval'], 'register_captcha' => $_POST['captcha'], 'tos' => $_POST['tos']));
	if($check === true)
		$theme['success'] = theme('start_content').'Settings Successfully Updated'.theme('end_content');
} else {
	$theme['title'] = 'Registration';
	$settingquery = call('sql_query', "SELECT variable, help_text FROM settings");
	while($settingrow = call('sql_fetch_array',$settingquery)) {
		$settingshelp[$settingrow['variable']] = $settingrow['help_text'];
	}
	$theme['head'] = '
					<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>';
	$theme['head_js'] = '
					$(function() {
						$("#'.$theme['window_id'].' .registration").validate();
					});';
	$theme['body'] = '
	<form action="" method="post" class="registration">
		'.theme('start_content').'
			<table class="admin-table">
				<tr>
					<td>
						Registration:
					</td>
					<td>
						<select name="registration">
							<option value="on"'.(errors() && $_POST['registration'] =='on' || $settings['registration'] =='on' ? ' selected="selected"' : '').'>
								On
							</option>
							<option value="off"'.(errors() && $_POST['registration'] =='off' || $settings['registration'] =='off' ? ' selected="selected"' : '').'>
								Off
							</option>
					</select>
					</td>
				</tr>
				<tr>
					<td>
						Approval Type:<span class="help" title="' . $settingshelp['register_approval'] . '"></span>
					</td>
					<td>
						<select name="reg_approval">
							<option value="admin"'.(errors() && $_POST['reg_approval'] =='admin' || $settings['register_approval'] =='admin' ? ' selected="selected"' : '').'>
								Admin Activation
							</option>
				  			<option value="email"'.(errors() && $_POST['reg_approval'] =='email' || $settings['register_approval'] =='email' ? ' selected="selected"' : '').'>
								Email Activation
							</option>
				  			<option value="none"'.(errors() && $_POST['reg_approval'] =='none' || $settings['register_approval'] =='none' ? ' selected="selected"' : '').'>
								None
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Captcha:<span class="help" title="'.$settingshelp['register_captcha'].'"></span>
					</td>
					<td>
						<select name="captcha">
							<option value="on"'.(errors() && $_POST['captcha'] =='on' || $settings['register_captcha'] =='on' ? ' selected="selected"' : '').'>
								On
							</option>
							<option value="off"'.(errors() && $_POST['captcha'] =='off' || $settings['register_captcha'] =='off' ? 'selected="selected"' : '').'>
								Off
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Terms of Service:
					</td>
					<td>
						<textarea name="tos" rows="5" cols="30">'.(errors() ? $_POST['tos'] : $settings['tos']).'</textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" value="Save" name="submit" />
					</td>
				</tr>
			</table>
		'.theme('end_content').'
	</form>';
}
?>