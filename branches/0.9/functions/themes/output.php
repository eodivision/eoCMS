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

function output($title = '', $body = '', $head = '') {
	global $settings, $authid, $checkleft, $checkright, $head, $error, $error_die;
	if(theme('output_error') != false) {
		$body = theme('output_error');
		$title = 'Error';
		$panels = false;
		$lowerpanel = false;
		$panel = '';
		unset($error_die);
	} else
		$panels = true;
	//display panels
	if($panels != false) {
		$panel =  theme('displaypanels');
		$lowerpanel = theme('displaylowerpanel');
	}
	if(isset($error) && !empty($error)) {
	    $errors = '<br />'.theme('title', 'Error') . theme('start_content').'<div class="errors"><ul>';
		foreach($error as $error1) {
			$errors .= '<li>'.$error1.'</li>';
		}
		$errors .= '</ul></div>'.theme('end_content');
		unset($error);
	} else
	    $errors = '';
	if(isset($_GET['page']) && $_GET['page'] > 1)
		$title = $title.' - Page '.$_GET['page'];
	$output = theme('head', stripslashes($title), $head) . '<body>';
	if($settings['maintenance_mode'] == 'on')
	    $output .= '<div class="titlebg">WARNING: Maintenance Mode is on</div>';
	$output .= '<div id="container">
	' . theme('top') . theme('links');
	$output .= $panel;
	//display the data
	$output .= $errors. '<br />' . stripslashes($body);
	$output .= $lowerpanel . theme('footer');
	//SEO Friendly Links
	include(IN_PATH.'/functions/seofriendlyurls.php');
	//Check if the tidy library is installed
	if(extension_loaded('tidy')) {
		//yay it is, lets clean up all the HTML, so it looks all nice in View Source in your browser :)
		$options = array("indent" => true, 'wrap' => 0); 
		$output = tidy_parse_string($output, $options);
		tidy_clean_repair($output);
	}
	die($output);
}
?>