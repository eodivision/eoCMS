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
# predefine theme variables, if not already set
if(!isset($theme['head']))
	$theme['head'] = '';
if(!isset($theme['title']))
	$theme['title'] = '';
if(!isset($theme['body']))
	$theme['body'] = '';
//this is the list of all the functions with the files that need included
$theme['functions'] = array('sub_panel_title' => 'panels.tpl.php',
							'sub_panel_start_content' => 'panels.tpl.php',
							'sub_panel_end_content' => 'panels.tpl.php',
							'main_template_admin' => 'admin-main.tpl.php',
							'main_template_admin_ajax' => 'admin-main.tpl.php',
							'admin_child_links' => 'admin-main.tpl.php',
							'field' => 'form.tpl.php',
							'formlayout' => 'form.tpl.php');

//the default output function to use if none are set
$theme['default_output'] = 'main_template';
						
function title($title) {
	$html = '<div class="titlebg-left">
							<div class="titlebg-right">
								<div class="titlebg2">
									'.$title.'
								</div>
							</div>
						</div>';
	return $html;
}

function start_content() {
	$html = '
						<div>
							<div class="content-right">
								<div class="content-left">
									<div class="content">';
	return $html;
}

function end_content() {
	$html = '
									</div>
									<div class="contentbg">
									</div>
								</div>
							</div>
						</div>';
	return $html;
}

function main_template_above() {
	global $lang_path, $theme, $settings, $user;
	// This is everything above the content
	$theme['html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/style.css" type="text/css" />';
		if(file_exists(IN_PATH.'/themes/'.$settings['site_theme'].'/IE7.css')) {
			$theme['html'] .='
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/IE7.css" /><![endif]-->';
		}
	$theme['html'] .= '
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>' . $theme['title']. ' | ' . (isset($settings['site_name']) ? $settings['site_name'] : '') . '</title>
		<script type="text/javascript">
			if(top.location != self.location) { top.location = self.location.href; }
		</script>
		<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.js"></script>
		<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.ui.js"></script>
		<script type="text/javascript">
			var theme = "'.$settings['site_theme'].'";
			var site_url = "'.$settings['site_url'].'";
			var language = "'.$lang_path.'";
		</script>
		'.$theme['head'].'
	</head>
	<body>';
	if($settings['maintenance_mode'] == 'on')
		$theme['html'] .= '
		<div class="titlebg">
			WARNING: Maintenance Mode is on
		</div>';
	$theme['html'] .= '
		<div id="container">
			<div class="topside-l">
				<div class="topside-r">
					<div id="topbar"></div>
					<div id="ajax" class="ajax"></div>
					<div class="header-left">
						<div class="header-right">
							<div class="header">
								<div align="center">
									<img src="'.(isset($settings['logo']) ? $settings['logo'] : 'themes/default/images/logo.png').'" alt="logo" />
								</div>
							</div>
						</div>
					</div>';
}

function main_template_below() {
	global $theme, $settings, $_QUERIES, $begintime;
	// This is everything below the content
	$theme['html'] .= '
				</div>
			</div>
			<br class="clearfloat" />
			<div class="footerbg">
				<div class="footer-right">
					<div class="footer-left">
						<div class="footer">
							'.(isset($settings['footer']) ? html_entity_decode(htmlspecialchars_decode($settings['footer'], ENT_QUOTES), ENT_QUOTES) : '').'
							<p>
								Powered By <a href="http://eocms.com" target="_blank">eoCMS</a> Copyright &copy; 2007-'.date('Y').'
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>';
	if(!defined('FINAL'))
		benchmark();
	$theme['html'] .= '
	</body>
</html>';
}

//this function must be called last otherwise it will give inaccurate results
function benchmark() {
	global $theme, $_QUERIES, $begintime;
	# output timings
	$time = explode(" ", microtime());
	$time = $time[1] + $time[0];
	$endtime = $time; # define end time
	$totaltime = ($endtime - $begintime); # decrease to get total time
	$totaltime = round($totaltime, 2);
	$theme['html'] .= '
			'.$totaltime.' seconds Queries: '.$_QUERIES;
}

function main_template_menu() {
	global $theme, $user, $authid;
	// Obviously this is where the menu will be built
	$theme['html'] .= '
					<div class="navbar">
						<ul>';
						$headerlinkquery = call('sql_query', "SELECT * FROM menu ORDER BY item_order ASC", 'cache');
						foreach($headerlinkquery as $headerlinkrow) {
							if(call('visiblecheck', $user['membergroup_id'], $headerlinkrow['rank'])) {
								if (empty($headerlinkrow['window']) || $headerlinkrow['window'] != 'popup') {
									$theme['html'] .= '
							<li>
								<a href="'.$headerlinkrow['link'].($headerlinkrow['authid'] == '1' ? '&amp;' . $authid : '').'"'.($headerlinkrow['window'] == 'new' ? ' target="_blank"' : '') . '>'.$headerlinkrow['name'].'</a>
							</li>';
								} elseif ($headerlinkrow['window'] == 'popup') {
									$theme['html'] .= '
							<li>
								<a href="javascript:;" onclick="window.open(\''.$headerlinkrow['link'].($headerlinkrow['authid'] == '1' ? '&amp;' . $authid : '').'\',\'\',\'width='.$headerlinkrow['width'].',height='.$headerlinkrow['height'].'\')">'.$headerlinkrow['name'].'</a>
							</li>
						  ';
								}
							}
						}
					$theme['html'] .= '
						</ul>
					</div>';
}

function main_template_left() {
	global $theme, $settings, $user;
	if($theme['panel']['left'] !== false && is_array($theme['panel']['left'])) {
		$theme['html'] .= '
					<div id="sidebar1" class="sidebar">';
		foreach($theme['panel']['left'] as $panel) {
			$theme['html'] .= '
						<div class="panel" id="panel_' . $panel['id'] . '">';
			if($panel['file'] !== false) {
				@include($panel['lang']);
				include($panel['file']);
			} else {
				$theme['html'] .= '
							'.theme('sub_panel_title', $panel['name']).'
								'.theme('sub_start_content_panel').'
									'.$panel['content'].'
								'.theme('sub_panel_end_content');
			}
			$theme['html'] .= '
						</div>'; //close the panel id div
		}
		$theme['html'] .= '
					</div>'; //close the sidebar1 div column
	}
}

function main_template_upper() {
global $theme, $settings, $user;
	if($theme['panel']['upper'] !== false && is_array($theme['panel']['upper'])) {
		$theme['html'] .= '
						<div id="panel_upper" class="sidebar">';
		foreach($theme['panel']['upper'] as $panel) {
			$theme['html'] .= '
							<div class="panel" id="panel_' . $panel['id'] . '">';
			if($panel['file'] !== false) {
				@include($panel['lang']);
				include($panel['file']);
			} else {
				$theme['html'] .= '
								'.theme('sub_panel_title', $panel['name']).'
									'.theme('sub_start_content_panel').'
										'.$panel['content'].'
									'.theme('sub_panel_end_content');
			}
			$theme['html'] .= '
							</div>'; //close the panel id div
		}
		$theme['html'] .= '
						</div>'; //close the panel_upper div column
	}
}

function main_template_middle() {
	global $theme;
	if($theme['panel']['left'] !== false && $theme['panel']['right'] === false)
		$class = 'mainContent-noright';
	elseif($theme['panel']['left'] === false && $theme['panel']['right'] !== false)
		$class = 'mainContent-noleft';
	elseif($theme['panel']['left'] === false && $theme['panel']['right'] === false)
		$class ='mainContent-nosides';
	else
		$class = 'mainContent';
	$theme['html'] .= '
					<div id="'.$class.'">
						'.theme('main_template_upper').'
						'.$theme['body'].'
						'.theme('main_template_lower').'
					</div>';
}

function main_template_lower() {
	global $theme, $settings, $user;
	if($theme['panel']['lower'] !== false && is_array($theme['panel']['lower'])) {
		$theme['html'] .= '
					<div id="panel_lower" class="sidebar">';
		foreach($theme['panel']['lower'] as $panel) {
			$theme['html'] .= '
						<div class="panel" id="panel_' . $panel['id'] . '">';
			if($panel['file'] !== false) {
				@include($panel['lang']);
				include($panel['file']);
			} else {
				$theme['html'] .= '
							'.theme('sub_panel_title', $panel['name']).'
								'.theme('sub_start_content_panel').'
									'.$panel['content'].'
								'.theme('sub_panel_end_content');
			}
			$theme['html'] .= '
						</div>'; //close the panel id div
		}
		$theme['html'] .= '
					</div>'; //close the panel_lower div column
	}
}

function main_template_right() {
	global $theme, $settings, $user;
	if($theme['panel']['right'] !== false && is_array($theme['panel']['right'])) {
		$theme['html'] .= '
					<div id="sidebar2" class="sidebar">';
		foreach($theme['panel']['right'] as $panel) {
			$theme['html'] .= '
						<div class="panel" id="panel_' . $panel['id'] . '">';
			if($panel['file'] !== false) {
				@include($panel['lang']);
				include($panel['file']);
			} else {
				$theme['html'] .= '
							'.theme('sub_panel_title', $panel['name']).'
								'.theme('sub_start_content_panel').'
									'.$panel['content'].'
								'.theme('sub_panel_end_content');
			}
			$theme['html'] .= '
						</div>'; //close the panel id div
		}
		$theme['html'] .= '
					</div>'; //close the sidebar2 div column
	}
}

function breadcrumb($array) {
	$html = '';
	$seperator = ' &gt; ';
	if(is_array($array)) {
		foreach($array as $link) {
			$html .= $link .$seperator;	
		}
		$html = preg_replace('{'.$seperator.'$}', '', $html);
		return $html;
	}
}

function error() {
	global $theme, $error, $error_die;
	if(!errors())
		return false;
	if(isset($error_die) && !empty($error_die)) {
		$theme['title'] = 'Error';
		$theme['body'] = theme('title', 'Error').theme('start_content').'
					<div class="error">
						<ul>';
		foreach($error_die as $errors) {
			$theme['body'] .= '
							<li>
								'.$errors.'
							</li>';
		}
		$theme['body'] .= '
						</ul>
					</div>
				'.theme('end_content');
		unset($error_die);
	} 
	if(isset($error) && !empty($error)) {
		$html = theme('title', 'Error') . theme('start_content').'
					<div class="errors">
						<ul>';
		foreach($error as $errors) {
			$html .= '
							<li>
								'.$errors.'
							</li>';
		}
		$html .= '
						</ul>
					</div>
				'.theme('end_content').'<br />';
		unset($error);
		$theme['body'] = $html.$theme['body'];
	}
}

//this is where everything is put together :D
function main_template() {
	global $theme, $error, $error_die;
	//basically all these functions assign everything to one big variable
	theme('main_template_above'); //contains the <html> <head> etc
	theme('main_template_menu'); //the menu obviously :P
	theme('main_template_left'); //displays the left column
	theme('error'); //outputs any errors and overwrites everything if theres a fatal error
	theme('main_template_middle'); //middle column, basically the blueprints
	theme('main_template_right'); //displays right column
	theme('main_template_below'); //closes all the divs like container etc and shows the footer
	//this is the magic function to make the URLs SEO Friendly :)
	include(IN_PATH.'functions/seofriendlyurls.php');
	//now lets output everything and stop anything loading after it!
	die($theme['html']);
	//FYI its quicker to dump it all in one variable using die than multiple echos, speed is what we want :D
}

//This is the maintenance output page
function main_template_maintanance() {
	global $theme, $error, $error_die;
	theme('error'); //overwrites everything if theres a fatal error
	$theme['html'] = 
theme('main_template_above').'
	<body>
		<div id="maintenance-container">
			'.theme('start_content').$theme['head'].theme('end_content').'
		</div>
	</body>
</html>';
	die($theme['html']);
}
function main_template_popup() {
	global $theme;
	$theme['html'] = theme('main_template_above').
	'
		<body>'
		.theme('start_content').
			$theme['head'].
		theme('end_content').'
	</body>
</html>';
	die($theme['html']);
}
?>