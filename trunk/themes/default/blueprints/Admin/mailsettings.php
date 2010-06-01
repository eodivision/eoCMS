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

$theme['title'] = 'Mail Settings';
$settingquery = call('sql_query', "SELECT variable, help_text FROM settings");
while($settingrow = call('sql_fetch_array',$settingquery)) {
	$settingshelp[$settingrow['variable']] = $settingrow['help_text'];
}
$theme['head_js'] = '
	$(document).ready(function() {
		$("#'.$theme['window_id'].' .smtp").css("display", "none");
	});';
$theme['head'] = '
<script type="text/javascript">
	function mailtype() {
		if($("#'.$theme['window_id'].' .mail option:selected").val()=="smtp") {
			$("#'.$theme['window_id'].' .smtp").slideToggle();
		} else {
			$("#'.$theme['window_id'].' .width").removeClass("required digit");
			$("#'.$theme['window_id'].' .height").removeClass("required digit");
			if($("#'.$theme['window_id'].' .popup").css("display") != "none")
				$("#'.$theme['window_id'].' .popup").slideToggle();
		}
	}
</script>';
$theme['body'] = '
	<form action="" method="post">
		'.theme('start_content').'
			<table class="admin-table">
				<tr>
					<td>
						Type:<span class="help" title="'.$settingshelp['mail'].'"></span>
					</td>
					<td>
						<select name="mail" class="mail">
							<option value="sendmail"'.((errors() && $_POST['mail'] == 'sendmail') || $settings['mail'] =='sendmail' ? ' selected="selected"' : '').'>SendMail</option>
							<option value="smtp"'.((errors() && $_POST['mail'] =='smtp') || $settings['mail'] == 'smtp' ? ' selected="selected"' : '').'>SMTP</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Email:
					</td>
					<td>
						<input type="text" name="email" value="'.(errors() ? $_POST['email'] : $settings['email']).'" />
					</td>
				</tr>
				<tr class="smtp">
					<td colspan="2" class="admin-subtitlebg">
						SMTP
					</td>
				</tr>
				<tr class="smtp">
					<td>
						Host:
					</td>
					<td>
						<input type="text" name="host" value="'.(errors() ? $_POST['host'] : $settings['smtp_host']).'" />
					</td>
				</tr>
				<tr class="smtp">
					<td>
						Username:
					</td>
					<td>
						<input type="text" name="username" value="'.(errors() ? $_POST['username'] : $settings['smtp_username']).'" />
					</td>
				</tr>
				<tr class="smtp">
					<td>
						Password:
					</td>
					<td>
						<input type="password" name="password" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="Save" /></td>
				</tr>
			</table>
		'.theme('end_content').'
	</form>';
?>