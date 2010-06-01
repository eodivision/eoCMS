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
function output_maintenance($title = '', $body = '', $head = '') {
	global $error, $error_die;
	if (theme('output_error') != false) {
	    $body = theme('output_error');
	    $title = 'Error';
	    unset($error_die);
	}
	if (errors()) {
		$error = theme('title', 'Error') . theme('start_content').'<div class="errors"><ul>';
		foreach($error as $errors) {
			$error .= '<li>'.$errors.'</li>';
		}
		$error .= '</ul></div>'.theme('end_content');
		unset($error);
	} else
	    $error = '';
	$output = theme('head', stripslashes($title), $head) . '
<body class="thrColLiqHdr">
<div id="maintenance-container">'.theme('start_content').stripslashes($body).theme('end_content').'</div>
  </body>
</html>';
	die($output);
}
?>