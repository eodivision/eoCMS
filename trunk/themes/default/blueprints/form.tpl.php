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
function field($label, $field) {
	$html = '
			<tr>
				<td>
					'.$label.':
				</td>
				<td>
					'.$field.'
				</td>
			</tr>';
	return $html;
}

function submit($name, $value = 'Submit') {
	$html = '
			<tr>
				<td>
					<input type="submit" name="'.$name.'" value="'.$value.'" />
				</td>
			</tr>';
	return $html;
}

function captcha($form, $name, $code, $token) {
	global $settings;
	$html = '
			<tr>
				<td colspan="2">
					<img src="'.$settings['site_url'].'/index.php?act=captcha&code='.$code.'" class="'.$name.'" />
					<br />
					<object type="application/x-shockwave-flash" data="'.$settings['site_url'].'/functions/securimage/securimage_play.swf?audio='.urlencode($settings['site_url'].'/index.php?act=captcha&audio&code='.$code).'" height="19" width="19"><param name="movie" value="'.$settings['site_url'].'/functions/securimage/securimage_play.swf?audio='.$settings['site_url'].'/index.php?act=captcha&#038;audio&#038code='.$code.'" /></object>
					<a href="#" onclick="$(\'#'.$form.' .'.$name.'\').attr(\'src\', \'index.php?act=captcha&token='.$token.'&rand=\' + Math.random()); return false" title="Reload Image">
						<img src="functions/securimage/images/refresh.gif" alt="Reload Image" />
					</a>
				</td>
		   </tr>
		   <tr>
				<td>
					Enter the text in the image above:
				</td>
				<td>
					<input type="text" name="captcha" size="10" maxlength="6" class="required" />
				</td>
		   </tr>';
	return $html;
}

function formlayout($form, $fields) {
	$html = '
	<form '.$form.'>
		<table class="form-table">'.$fields.'
		</table>
	</form>';
	return $html;
}
?>