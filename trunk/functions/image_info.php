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
function image_info($file) {
	global $settings, $error, $error_die;
	$image = @getimagesize($file);
	if($image == false) {
		$error[] = 'The avatar entered does not exist';
		return false;
	} else {
		if($image['0'] > $settings['avatar_width']) {
			$error[] = 'The avatar is too wide';
			return false;
		} else
			return true;
		if($image['1'] > $settings['avatar_height']) {
			$error[] = 'The avatar is too wide';
			return false;
		} else
			return true;
	}
}
?>