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