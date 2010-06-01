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

function displaylowerpanel()
{
	global $settings, $user, $lowerpanel, $lang_path;
	$body = '';
	
	$querystring = '?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');$querystring = Sanitize($querystring);
	
	if (!empty($settings['exclude_lower'])) {
	    $listofexcludeslower = explode(", ", $settings['exclude_lower']);
		if(in_array($querystring, $listofexcludeslower))
			$checkupper = true;
		else
	    	$checklower = in_array($_GET['act'], $listofexcludeslower);
	} else {
		$checkupper = false;
	}
	if ($checklower == false) {
	    $body .= '<div class="sidebar" id="panel_lower">';
	    foreach ($lowerpanel as $panel) {
		  if (call('visiblecheck', $user['membergroup_id'], $panel['rank'])) {
			if ($panel['all_pages'] == '1' || ($_GET['act'] == '' && $panel['all_pages'] == '0')) {
			    if (!empty($panel['file'])) {
				  $body .= '<br />';
				  $body .= '<div class="panel" id="panel_' . $panel['id'] . '">';
				 if(strpos($panel['file'], 'Plugins/') !== false) {
					@include($panel['file'] .'/languages/' . $lang_path . '.php');
				  	include $panel['file'].'/index.php';
				  } else {
					@include('panels/' . $panel['file'] .'/languages/' . $lang_path . '.php');
				  	include 'panels/' . $panel['file'] . '/index.php';
				  }
				  $body .= '</div>';
			    } else {
				  $body .= '<div class="panel" id="panel_' . $panel['id'] . '"><div class="panel-header">' . theme('title', $panel['panelname']) . '</div>' . theme('start_content_panel');
				  if(empty($panel['type']) || $panel['type'] == 'html')
				  	$body .= html_entity_decode($panel['panelcontent']);
				  elseif(!empty($panel['type']) && $panel['type'] == 'php')
				  	eval(html_entity_decode($panel['panelcontent']));
				$body .= theme('end_content') . '</div>';
			    }
			}
		  }
	    }
	    $body .= '</div>';
	}
	return $body;
}
?>