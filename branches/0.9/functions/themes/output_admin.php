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

function output_admin($title = '', $body = '', $head = '', $update = '') {
	global $settings, $authid, $user, $error;
	if(errors()) {
		$errors = theme('start_content') . '<p><h2>Error:</h2></p><div class="errors"><ul>';
		foreach($error as $error1) {
			$errors .= '<li>'.$error1.'</li>';
		}
		$errors .= '</ul></div>'.theme('end_content');
		unset($error);
	} else
	    $errors = '';
	if (isset($_SESSION['update'])) {
	    $update = '<div class="content" align="center" id="update" style="display: block;">'.$_SESSION['update'].'</div>';
	    unset($_SESSION['update']);
	} else
	    $update = '<div align="center" id="update"></div>';
		  if (empty($user['admin_menu']))
			$menu = 'left.php';
		  else
			$menu = $user['admin_menu'];
	if (file_exists('themes/' . $settings['site_theme'] . '/Layouts/Admin/Menu/' . $menu)) {
		include('themes/' . $settings['site_theme'] . '/Layouts/Admin/Menu/' . $menu);
	} elseif (file_exists('themes/_Admin/' . $menu . '/theme-info.php')) {
		$theme_path = 'themes/_Admin/' . $menu . '/';
		include($theme_path . 'theme-info.php');
		include($theme_path . $theme_default);
	} else
		include('Layouts/Admin/Menu/' . $menu);
	$output = theme('head', $title, $head . '<style type="text/css">
.thrColLiqHdr #mainContent {
  margin-bottom: 0;
  margin-left: 1%;
  margin-top: 0;
  margin-right: 1%;
}
</style>') . '
<body class="thrColLiqHdr">
<div id="container">
' . theme('top') . theme('links') . '
  <div id="mainContent">';
	if (theme('output_error') == false)
	    $output.= $menu;
	else
	    $output .= theme('output_error');
	$output .= theme('footer');
	die($output);
}
?>