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
function admin_child_links($array) {
	global $theme, $tab, $settings;
	$html = '';
	if(is_array($array)) {
		$options = false;
		foreach($array as $child) {
			$html .= '
						'.$tab.'<li class="admin-link'.(isset($child['class']) && !empty($child['class']) ? ' '.$child['class'] : '').'"'.(isset($child['id']) && !empty($child['id']) ? ' id="'.$child['id'].'"' : '').'>';
			//if(isset($child['icon']))
				//$html .= '
					//		'.$tab.'<span class="ui-icon '.$child['icon'].'"></span>';
			$html .= '
							'.$tab.'<a href="'.$child['link'].'" title="'.$child['title'].'">'.$child['title'].'</a>';
			if(isset($child['child'])) {
				$tab .= "\t";
				$html .= theme('admin_child_links', $child['child']);
			} else {
				$html.= '
							'.$tab.'</li>';
			}
		}
			$html .= '
							
							'.$tab.'</li>
						'.$tab.'</ul>';
	}
	$html = '
						'.$tab.'<ul>'.$html;
	return $html;
}

function main_template_admin_error() {
	global $error;
	if(isset($error) && !empty($error)) {
		$html = '<ul class="window-error">';
		foreach($error as $e) {
			$html .= '<li>'.$e.'</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}

function main_template_admin_error_die() {
	global $error_die;
	if(isset($error_die) && !empty($error_die)) {
		$html = '<ul class="window-error-die">';
		foreach($error_die as $error) {
			$html .= '<li>'.$error.'</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}

function main_template_admin_ajax() {
	global $authid, $error, $error_die, $settings, $theme;
	if(!isset($theme['head']))
		$theme['head'] = '';
	if(!isset($theme['title']))
		$theme['title'] = '';
	if(!isset($theme['success']))
		$theme['success'] = null;
	if(!isset($theme['js']))
		$theme['js'] = '';
	die(json_encode(array(
		'title' => $theme['title'],
		'body' => $theme['body'],
		'head' => $theme['head'],
		'js' => $theme['js'],
		'error' => main_template_admin_error(),
		'error_die' => main_template_admin_error_die(),
		'success' => $theme['success']
	)));
}

function main_template_admin() {
	global $settings, $authid, $user, $error, $error_die, $theme, $lang_path;
	$theme['html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/style.css" type="text/css" />';
	if(file_exists(IN_PATH.'/themes/'.$settings['site_theme'].'/IE7.css')) {
		$theme['html'] .='
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/IE7.css" /><![endif]-->';
	}
	$theme['html'] .= '
		<link rel="stylesheet" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/admin.css" type="text/css" />
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
		<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.validate.js"></script>
		<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/js/admin.js"></script>
		'.$theme['head'].'
	</head>
	<body>
		<div id="dialog"></div>
		<div id="container">
			<div class="user-info">
				<table>
					<tr>
						<td>
							'.$user['avatar'].'
						</td>
						<td>
							Welcome '.$user['user'].' | <a href="'.$settings['site_url'].'">Return to site</a>
						</td>
					</tr>
				</table>
			</div>
			<div class="menu ui-widget ui-widget-content ui-corner-all">
				<ul>';
	$theme['links'] = call('adminlinks');
	foreach($theme['links'] as $item => $link) {
		$theme['html'] .= '
					<li class="admin-link'.(isset($link['class']) && !empty($link['class']) ? ' '.$link['class'] : '').'"'.(isset($link['id']) && !empty($link['id']) ? ' id="'.$link['id'].'"' : '').'>
						<a href="'.$link['link'].'" title="'.$link['title'].'">
							<span class="outer">
								<span class="inner" style="background-image: url('.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/'.$link['image'].');">
									'.$link['title'].'
								</span>
							</span>
						</a>
					</li>';
	}
	unset($theme['links']);
	$theme['html'] .= '     
				</ul>
			</div>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">eoCMS News</a></li>
					<li><a href="#tabs-2">HI</a></li>
					<li><a href="#tabs-3">LOL</a></li>
				</ul>
				<div id="tabs-1">HALLOW</div>
				<div id="tabs-2">HALLOW2</div>
				<div id="tabs-3">HALLOW3</div>
			</div>
		</div>
	</body>
</html>';
	die($theme['html']);
}
?>