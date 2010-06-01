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
	$check = call('sitesettings', array('maintenance_mode' => $_POST['mode'], 'maintenance_message' => $_POST['message']));
	if($check === true)
		$theme['success'] = theme('start_content').'Settings Successfully Updated'.theme('end_content');
} else {
	$theme['title'] = 'Maintenance';
	$settingquery = call('sql_query', "SELECT variable, help_text FROM settings");
	while($settingrow = call('sql_fetch_array',$settingquery)) {
		$settingshelp[$settingrow['variable']] = $settingrow['help_text'];
	}
	$theme['head'] = '
					<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>';
	$theme['head_js'] = '
					$(function() {
						$("#'.$theme['window_id'].' .maintenance").validate();
						$.validator.addMethod("dependsOn", function (value, el, params) {
							if($(params.el).val() == params.being && $("textarea[name=message]").val() != "")
								return true;
							else if($(params.el).val() != params.being)
								return true;
							else
								return false;
							}, "This field is required");
						$.validator.addClassRules({
							message: {
								dependsOn: {
									el: "select[name=mode]",
									being: "on"
									},
								required: false
							}
						});
					});';
	$theme['body'] = '
	<form action="" method="post" class="maintenance">
		'.theme('start_content').'
			<table class="admin-table">
				<tr>
					<td>
						Mode:<span class="help" title="'. $settingshelp['maintenance_mode'].'"></span>
					</td>
					<td>
						<select name="mode">
							<option value="off"'.(errors() && $_POST['mode'] =='off' || $settings['maintenance_mode'] == 'off' ? ' selected="selected"' : '').'>
								Off
							</option>
						 	<option value="on"'.(errors() && $_POST['mode'] =='on' || $settings['maintenance_mode'] == 'on' ? ' selected="selected"' : '').'>
								On
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="admin-subtitlebg">
						Message
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea name="message" rows="5" cols="30" class="message">'.(errors() ? $_POST['message'] : $settings['maintenance_message']).'</textarea>
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