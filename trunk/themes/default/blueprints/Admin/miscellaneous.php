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
	$check = call('sitesettings', array('site_name' => $_POST['site_name'], 'site_url' => $_POST['site_url'], 'topics_page' => $_POST['topics_page'], 'posts_topic' => $_POST['posts_topic']));
	if($check === true)
		$theme['success'] = theme('start_content').'Settings Successfully Updated'.theme('end_content');
} else {
	$theme['title'] = 'Miscellaneous';
	$settingquery = call('sql_query', "SELECT variable, help_text FROM settings");
	while($settingrow = call('sql_fetch_array',$settingquery)) {
		$settingshelp[$settingrow['variable']] = $settingrow['help_text'];
	}
	$theme['head'] = '
					<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>';
	$theme['head_js'] = '
					$(function() {
						$("#'.$theme['window_id'].' .miscellaneous").validate();
					});';
	$theme['body'] = '
	<form action="" method="post" class="miscellaneous">
		'.theme('start_content').'
			<table class="admin-table">
				<tr>
					<td>
						Site Name:
					</td>
					<td>
						<input type="text" name="site_name" class="required" value="'.(errors() ? $_POST['site_name'] : $settings['site_name']).'" />
					</td>
				</tr>
				<tr>
					<td>
						Site URL (No trailing /):
					</td>
					<td>
						<input type="text" name="site_url" class="required url" value="'.(errors() ? $_POST['site_url'] : $settings['site_url']).'" />
					</td>
				</tr>
				<tr>
					<td>
						Default number of topics per page in a board:
					</td>
					<td>
						<input type="text" name="topics_page" class="digits" value="'.(errors() ? $_POST['topics_page'] : $settings['topics_page']).'" />
					</td>
				</tr>
				<tr>
					<td>
						Default number of posts per page in a topic:
					</td>
					<td>
						<input type="text" name="posts_topic" class="digits" value="'.(errors() ? $_POST['posts_topic'] : $settings['posts_topic']).'" />
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