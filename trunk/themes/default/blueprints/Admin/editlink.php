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
	if(!isset($_POST['authid']))
		$_POST['authid'] = '';
	$check = call('editlink', $_POST['link_name'], $_POST['url'], $_POST['rank'], $_POST['authid'], $_POST['settings'], $_POST['width'], $_POST['height'], $_GET['id']);
	if($check === true)
		$theme['success'] = theme('start_content').'Link Successfully Updated'.theme('end_content');
} else {
	$sql = call('sql_query', "SELECT * FROM menu WHERE id = '" . $_GET['id'] . "'");
	$link = call('sql_fetch_assoc', $sql);
	$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>';
	$theme['head_js'] = '
	$(document).ready(function() {
		$("#'.$theme['window_id'].' .links").validate();
	});';
	$theme['head'] .= '
<script type="text/javascript">
	function windowcheck() {
		if($("#'.$theme['window_id'].' .settings option:selected").val()=="popup") {
			$("#'.$theme['window_id'].' .popup").slideToggle();
			$("#'.$theme['window_id'].' .width").addClass("required digit");
			$("#'.$theme['window_id'].' .height").addClass("required digit");
		} else {
			$("#'.$theme['window_id'].' .width").removeClass("required digit");
			$("#'.$theme['window_id'].' .height").removeClass("required digit");
			if($("#'.$theme['window_id'].' .popup").css("display") != "none")
				$("#'.$theme['window_id'].' .popup").slideToggle();
		}
	}
</script>';
	$theme['title'] = 'Edit Link - '.$link['name'];
	$theme['body'] = '
	<form method="POST" action="" class="links">
		<div class="admin-panel">
			'.theme('start_content').'
				<table class="admin-table">
	  				<tr>
						<td>
							Name:
						</td>
						<td>
							<input type="text" name="link_name" class="required" value="'.$link['name'].'" />
						</td>
	  				</tr>
	  				<tr>
						<td>
							Url:
						</td>
						<td>
							<input type="text" name="url" class="url required" value="'.$link['link'].'" />
						</td>
					</tr>
					<tr>
						<td class="admin-subtitlebg" colspan="2">
							Viewable by:
						</td>
					</tr>';
	$membergroups = call('sql_query', "SELECT membergroup_id, name FROM membergroups", 'cache');
	foreach($membergroups as $m) {
		$theme['body'].='
					<tr>
						<td>
							<input type="checkbox" value="'.$m['membergroup_id'].'" name="rank[]" id="'.$theme['window_id'].'-visible_'.$m['membergroup_id'].'"'.(call('visiblecheck', $m['membergroup_id'], $link['rank']) ? ' checked="checked"' : '').' class="required" minlength="1" />
						</td>
						<td>
							<label for="'.$theme['window_id'].'-visible_'.$m['membergroup_id'].'">'.$m['name'].'</label>
						</td>
					</tr>';
	}
	$theme['body'].='
					<tr>
						<td>
							Authentication ID:
						</td>
						<td>
							<input type="checkbox" name="authid"'.($link['authid'] == '1' ? ' checked="checked"' : '').'/>
						</td>
				  	</tr>
				  	<tr>
						<td>
							Open Link in:
						</td>
						<td>
							<select name="settings" class="settings" onchange="windowcheck();">
								<option value="same"'.($link['window'] == 'same' ? ' selected="selected"' : '').'>Same Window</option>
								<option value="new"'.($link['window'] == 'new' ? ' selected="selected"' : '').'>New Window</option>
								<option value="popup"'.($link['window'] == 'popup' ? ' selected="selected"' : '').'>Popup</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="popup" style="display: none;">
								Popup Width: <input type="text" name="width" class="width" size="3" value="'.$link['width'].'" />
								Height: <input type="text" name="height" class="height" size="3" value="'.$link['height'].'" />
							</div>
						</td>
				 	</tr>
				 	<tr>
						<td align="center" colspan="2">
							<input type="submit" name="submit" value="Save" />
						</td>
					</tr>
				</table>
			'.theme('end_content').'
		</div>
	</form>';
}
?>