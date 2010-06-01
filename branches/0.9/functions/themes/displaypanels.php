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

function displaypanels()
{
	global $settings, $user, $lowerpanel, $authid, $head, $lang_path, $checkleft, $checkright;
	$querystring = '?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
	$querystring = Sanitize($querystring);
	$body = '';
	$panelquery = call('sql_query', 'SELECT * FROM panels WHERE online="1" ORDER BY item_order ASC', 'cache');
	$leftpanel = array();
	$rightpanel = array();
	$upperpanel = array();
	$lowerpanel = array();
	foreach ($panelquery as $fetch) {
	    $panelstuff = array('id' => $fetch['id'], 'rank' => $fetch['rank'], 'all_pages' => $fetch['all_pages'], 'file' => $fetch['file'], 'panelname' => $fetch['panelname'], 'panelcontent' => html_entity_decode(htmlspecialchars_decode($fetch['panelcontent'], ENT_QUOTES), ENT_QUOTES), 'item_order' => $fetch['item_order'], 'type' => $fetch['type']);
	    if ($fetch['side'] == 'left') {
		  $leftpanel[] = $panelstuff;
	    }
	    if ($fetch['side'] == 'right') {
		  $rightpanel[] = $panelstuff;
	    }
	    if ($fetch['side'] == 'upper') {
		  $upperpanel[] = $panelstuff;
	    }
	    if ($fetch['side'] == 'lower') {
		  $lowerpanel[] = $panelstuff;
	    }
	}
	if (!empty($settings['exclude_left'])) {
	    $listofexcludesleft = explode(", ", $settings['exclude_left']);
		if(in_array($querystring, $listofexcludesleft))
			$checkleft = true;
		else
	    	$checkleft = in_array($_GET['act'], $listofexcludesleft);
	} else {
	    $checkleft = false;
	}
	if ($checkleft == false) {
	    $body .= '<div id="sidebar1" class="sidebar">';
	    foreach ($leftpanel as $panel) {
		  if (call('visiblecheck', $user['membergroup_id'], $panel['rank'])) {
			if ($panel['all_pages'] == '1' || ($_GET['act'] == '' && $panel['all_pages'] == '0')) {
			    if (!empty($panel['file'])) {
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
	if (!empty($settings['exclude_right'])) {
	    $listofexcludesright = explode(", ", $settings['exclude_right']);
		if(in_array($querystring, $listofexcludesright))
			$checkright = true;
		else
	    	$checkright = in_array($_GET['act'], $listofexcludesright);
	} else {
	    $checkright = false;
	}
	if ($checkright == false) {
	    $body .= '<div id="sidebar2" class="sidebar">';
	    foreach ($rightpanel as $panel) {
		  if (call('visiblecheck', $user['membergroup_id'], $panel['rank'])) {
			if ($panel['all_pages'] == '1' || ($_GET['act'] == '' && $panel['all_pages'] == '0')) {
			    if (!empty($panel['file'])) {
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
	$checkupper = false;
	//Extend the mainContent if there are no panels on that page
	if(count($checkleft) == 0)
		$checkleft = true;
	if(count($checkright) == 0)
		$checkright = true;
	$class = 'mainContent';
	if($checkleft == false && $checkright == true) {
		$class = 'mainContent-noright';
	}
	if($checkleft == true && $checkright == false) {
		$class = 'mainContent-noleft';
	}
	if($checkleft == true && $checkright == true) {
		$class ='mainContent-nosides';
	}
	$body .= '<div id="'.$class.'">';
	if (!empty($settings['exclude_upper'])) {
	    $listofexcludesupper = explode(", ", $settings['exclude_upper']);
		if(in_array($querystring, $listofexcludesupper))
			$checkupper = true;
		else
	    	$checkupper = in_array($_GET['act'], $listofexcludesupper);
	} else {
		$checkupper = false;
	}
	if ($checkupper == false) {
	    $body .= '<div class="sidebar" id="panel_upper">';
	    foreach ($upperpanel as $panel) {
		  if (call('visiblecheck', $user['membergroup_id'], $panel['rank'])) {
			if ($panel['all_pages'] == '1' || ($_GET['act'] == '' && $panel['all_pages'] == '0')) {
			    if (!empty($panel['file'])) {
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
				  if(empty($panel['fetch']) || $panel['fetch'] == 'html')
				  	$body .= html_entity_decode($panel['panelcontent']);
				  elseif(!empty($panel['fetch']) && $panel['fetch'] == 'php')
				  	$body .= eval($panel['panelcontent']);
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