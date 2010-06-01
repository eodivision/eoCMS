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
function output_error() {
	global $error_die;
	if(isset($error_die) && !empty($error_die)) {
		$error = theme('title', 'Error') . theme('start_content').'<div class="error"><ul>';
		foreach($error_die as $errors) {
			$error .= '<li>'.$errors.'</li>';
		}
		$error .= '</ul></div>'.theme('end_content');
		return $error;
	} else
		return false;
}
?>