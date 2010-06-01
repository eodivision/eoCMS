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
if(defined('INSTALLER')) {
	$time = explode(' ', microtime());
	$time = $time[1] + $time[0]; # return array
	$begintime = $time; # define begin time
}
session_start();
  /************************
   ** Clean superglobals **
   ************************/
function Sanitize($data_to_sanitize) {
	//checks to see if magic quotes is on, only lazy people have it on :P
	if (get_magic_quotes_gpc())
		$data_to_sanitize = stripslashes($data_to_sanitize); //if it is we reverse it
	//Now we encode ', ", &, <, > to prevent HTML and SQL injection
	$data_to_sanitize = htmlspecialchars($data_to_sanitize, ENT_QUOTES);
	//now we add slashes just as a backup if for some crazy reason the quotes are not encoded
	$data_to_sanitize = addslashes($data_to_sanitize);
	return $data_to_sanitize;
}
function Sanitize_Array($value) {
	$value = is_array($value) ? array_map('Sanitize_Array', $value) : Sanitize($value);
	return $value;
}
$_POST = array_map('Sanitize_Array', $_POST);
$_GET = array_map('Sanitize_Array', $_GET);
$_COOKIE = array_map('Sanitize_Array', $_COOKIE);
$_REQUEST = array_map('Sanitize_Array', $_REQUEST);
$_SERVER = array_map('Sanitize_Array', $_SERVER);
$_FILES = array_map('Sanitize_Array', $_FILES);
# Snippet from php.net by bohwaz
# below function kills register globals
# to remove any possible security threats if it is on
function unregister_globals(){
#checks if register globals is on
	if (!ini_get('register_globals')){
		return false;
	}
	foreach (func_get_args() as $name){
		foreach ($GLOBALS[$name] as $key=>$value){
			if (isset($GLOBALS[$key]))
				unset($GLOBALS[$key]);
		}
	}
}
unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', '_SESSION');
# Snippet from php.net by Steve
# below function is created if the host does not have the JSON Functions installed
if(!function_exists('json_encode')) {
	function json_encode($a=false) {
    	if(is_null($a))
			return 'null';
    	if($a === false)
			return 'false';
    	if($a === true)
			return 'true';
		if(is_scalar($a)) {
      		if(is_float($a))
        		return floatval(str_replace(",", ".", strval($a))); // Always use "." for floats.
			if(is_string($a)) {
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			} else
				return $a;
		}
		$isList = true;
    	for($i = 0, reset($a); $i < count($a); $i++, next($a)) {
			if(key($a) !== $i) {
				$isList = false;
				break;
			}
	    }
    	$result = array();
    	if($isList) {
			foreach($a as $v) $result[] = json_encode($v);
				return '[' . join(',', $result) . ']';
		} else {
			foreach($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
				return '{' . join(',', $result) . '}';
		}
	}
}
if(!defined('INSTALLER')) {
	require('./config.php');
	//get the cached tables
	$tables = unserialize(file_get_contents('./'.CACHE.'/tables.php', NULL, NULL, 16));
}
/*************************************************************
 **  call('function file name', additional arguments after  **
 *************************************************************/ 	
function call() {
	$arg = func_get_args();
	$function = array_shift($arg); // The function name ;)
	include_once IN_PATH.'functions/'.$function.'.php';
	// Below based on snippet from php.net/call_user_func_array by james@gogo.co.nz
	// Note because the keys in $arg might be strings
	// we do this in a slightly round about way.
	$argumentString = array();
	$argumentKeys = array_keys($arg);
	foreach($argumentKeys as $argK) {
		$argumentString[] = "\$arg[$argumentKeys[$argK]]";
	}
	$argumentString = implode(', ', $argumentString);
	// Note also that eval doesn't return references, so we
	// work around it in this way...   
	eval("\$result =& {$function}({$argumentString});");
	return $result;
}
/********************************************************************************
 **  plugin('function file name', 'plugin folder', additional arguments after  **
 ********************************************************************************/ 
function plugin() {
	$arg = func_get_args();
	$function = array_shift($arg); // The function name ;)
	$folder = array_shift($arg); // plugin folder
	include_once IN_PATH.'plugins/'.$folder.'/functions/'.$function.'.php';
	$argumentString = array();
	$argumentKeys = array_keys($arg);
	foreach($argumentKeys as $argK) {
		$argumentString[] = "\$arg[$argumentKeys[$argK]]";
	}
	$argumentString = implode(', ', $argumentString);
	eval("\$result =& {$function}({$argumentString});");
	return $result;
}
function form($id, $options = array(), $fields) {
	global $form, $theme, $error, $settings;
	$non_input = array('textarea', 'select', 'captcha', 'checkbox');
	$regex = array(
		'email' => "/^([a-z0-9._-](\+[a-z0-9])*)+@[a-z0-9.-]+\.[a-z]{2,6}$/i"
	);
	if(!isset($form[$id])) { // hmm not yet been created, lets do that now! ^_^
		$defaultoptions = array( // default options, we merge them in a bit
			'submit' => 'Submit',
			'action' => '',
			'method' => 'post',
			'encytype' => '',
			'extra' => '', // if you want any other stuff to add into <form> tag
			'callback' => '',
			'success' => function() {
				$theme['body'] = 'Form Submitted Successfully!';
			}
		);
		$options = array_merge($defaultoptions, $options);
		$form[$id]['html'] =  ' action="'.$options['method'].'" method="'.$options['method'].'" id="'.$id.'"'.$options['extra'];
	}
	$html = '';
	$rules = array();
	$messages = array();
	foreach($fields as $name => $detail) {
		if(!is_array($detail)) {
			// oh dear
		} else {
			if((isset($detail['show']) && $detail['show'] === true) || !isset($detail['show'])) {
				if(!in_array($detail['type'], $non_input) && !isset($detail['html'])) {
					$input = '<input type="'.$detail['type'].'" name="'.$name.'"';
					if(isset($detail['disabled']) && $detail['disabled'])
							$input .= ' disabled="disabled"';
					if(isset($detail['validation'])) {
						$validation = true;
						$input .= ' class="required';
						if($detail['type'] == 'email')
							$input .= ' email';
						$input .= '"';
						if(isset($detail['min']) && is_numeric($detail['min']))
							$input .= ' minlength="'.$detail['min'].'"';
						if(isset($detail['max']) && is_numeric($detail['max']))
							$input .= ' maxlength="'.$detail['max'].'"';
						if(isset($_POST[$name]))
							$input .= ' value="'.$_POST[$name].'"';
						if(isset($detail['validation']['remote'])) {
							$rules[$name] = (!isset($rules[$name]) ? array() : $rules[$name]);
							$rules[$name][] .= 'remote:"'.$detail['validation']['remote']['url'].'"';
							$messages[$name] = (!isset($messages[$name]) ? array() : $messages[$name]);
							$messages[$name][] .= 'remote: "'.$detail['validation']['remote']['failure'].'"';
						}
						if(isset($detail['validation']['equalto'])) {
							$rules[$name] = (!isset($rules[$name]) ? array() : $rules[$name]);
							$rules[$name][] .= 'equalTo: "#'.$id.' input[name='.$detail['validation']['equalto'].']"';
						}
					}
					$input .= ' />';
					$html .= theme('field', $detail['label'], $input);
				} elseif(isset($detail['html']))
					$html .= $detail['html'];
				else {
					if($detail['type'] == 'captcha') {
						$secure = call('form_protection', true);
						$html .= theme('captcha', $id, $name, $secure['captcha_encode'], $secure['token']);
					} 
					if(!isset($detail['token']) || (isset($detail['token']) && $detail['token'])) {
						$secure = (!isset($secure) ? call('form_protection') : $secure);
						$html .= '<input type="hidden" name="token" value="'.$secure['token'].'" />';
					}
				}
			} else
				unset($fields[$name], $detail['validation']); // dont validate them if they arent shown!
		}
		if(isset($_POST[$id])) {
			if(!isset($detail['token']) || (isset($detail['token']) && $detail['token']))
				call('checktoken', $_POST['token']);
			if(isset($detail['validation'])) {
				$v = $detail['validation'];
				$p = (isset($v['decode']) && $v['decode'] ?  html_entity_decode($_POST[$name], ENT_QUOTES) : $_POST[$name]);
				if(isset($v['min']) && isset($v['max']) && (strlen($p) < $v['min'] || strlen($p) > $v['max']))
					$error[] = $detail['label'].' must be between '.$v['min'].' and '.$v['max'].' characters';
				elseif(isset($v['min']) && strlen($p) < $v['min'])
					$error[] = $detail['label'].' must be '.$v['min'].' or more characters';
				elseif(isset($v['max']) && strlen($p) > $v['max'])
					$error[] = $detail['label'].' must be '.$v['max'].' or less characters';
				if(isset($v['equalto']) && $p != $_POST[$v['equalto']])
					$error[] = $detail['label'].' does not euqal '.$fields[$v['equalto']]['label'];
				if(isset($v['disallow']) && strpos($p, $v['disallow']) !== false)
					$error[] = $detail['label'].' does not allow '.$v['disallow'];
				if($detail['type'] == 'email' && !preg_match($regex['email'], $p))
					$error[] = 'The email address entered is not valid';
				if(isset($v['sql'])) {
					$v['sql']['query'] = preg_replace('/<this\.(.*?)>/msie', "''.preg_quote(\$_POST['\\1']).''", $v['sql']['query']);
					$sql = call('sql_query', $v['sql']['query']);
					$num = call('sql_num_rows', $sql);
					if(isset($v['sql']['minrows']) && isset($v['sql']['maxrows']) && ($num < $v['min'] || $num > $v['sql']['maxrows']))
						$error[] = $v['sql']['failure'];
					elseif(isset($v['sql']['minrows']) && $num < $v['sql']['minrows'])
						$error[] = $v['sql']['failure'];
					elseif(isset($v['sql']['maxrows']) && $num > $v['sql']['maxrows'])
						$error[] = $v['sql']['failure'];
				}
			}
			if($detail['type'] == 'captcha') {
				$captcha = call('captchacheck', $p);
				if(!$captcha)
					$error[] = 'The text entered for the image verification does not match';
			}
		}
	}
	if(isset($validation) && $validation) {
		$theme['head'] .= '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#'.$id.'").validate({rules: {';
		foreach($rules as $field => $rule) {
			$theme['head'] .= $field.':{';
			foreach($rule as $r)
				$theme['head'] .= $r.',';
			$theme['head'] = substr($theme['head'], 0, -1);
			$theme['head'] .= '},';
		}
		$theme['head'] = substr($theme['head'], 0, -1);
		$theme['head'] .= '}, messages: {';
		foreach($messages as $field => $message) {
			$theme['head'] .= $field.':{';
			foreach($message as $m)
				$theme['head'] .= $m.',';
			$theme['head'] = substr($theme['head'], 0, -1);
			$theme['head'] .= '},';
		}
		$theme['head'] = substr($theme['head'], 0, -1);
		$theme['head'] .= '}});
			});
		</script>';
	}
	if(isset($_POST[$id]) && !errors() && is_array($options['callback'])) {
		$func[] = $options['callback']['function'];
		foreach($fields as $key => $value) {
			if(isset($_POST[$key]))
				$func[] = $_POST[$key];
		}
		if(isset($options['callback']['args']))
			$func = array_merge($func, $callback['callback']['args']);
		$check = call_user_func_array('call', $func);
		if($check)
			$options['success']();
	} else
		$theme['body'] .= theme('formlayout', $form[$id]['html'], $html.theme('submit', $id, $options['submit']));
}
/****************
 **  PHPMAILER **
 ****************/
@include(IN_PATH.'functions/class.phpmailer.php');
/****************************
 ** Settings And User Info **
 ****************************/
if(!defined('INSTALLER')) {
     $settings = call('getsettings');
     $user = call('getuserinfo');
}
/******************
 **  SECUREIMAGE **
 ******************/
@include(IN_PATH.'functions/securimage/securimage.php');
/*************************
 ** Load Theme Settings **
 *************************/
# use user defined theme, or default site theme if not available
if(isset($_GET['theme']) && file_exists('themes/'.$_GET['theme'].'/theme-info.php'))
	$settings['site_theme'] = $_GET['theme'];
elseif(isset($user['theme']) && file_exists('themes/' . $user['theme'] . '/theme-info.php'))
	$settings['site_theme'] = $user['theme'];
elseif(isset($settings['site_theme']) && file_exists('themes/' . $settings['site_theme'] . '/theme-info.php'))
	$settings['site_theme'] = $settings['site_theme'];
else
	$settings['site_theme'] = 'default';
if(!defined('INSTALLER'))
	call('getthemesettings'); # set the theme settings
#grab all the panels and process as much PHP as possible
if(!defined('INSTALLER'))
	call('panel_init');
#include the main theme file
include(IN_PATH.'themes/'.$settings['site_theme'].'/blueprints/main.tpl.php');
/*******************************************************************************************************************************
 **  theme('theme function file name')             Below needs updating!        											  **
 **  includes functions within current theme/functions/ if it exists, else it will include the function in functions/themes   **
 *******************************************************************************************************************************/
function theme() {
	global $settings, $theme;
	$arg = func_get_args();
	$function = array_shift($arg); // The function name ;)
	if(!function_exists($function) && array_key_exists($function, $theme['functions']) && file_exists(IN_PATH.'themes/'.$settings['site_theme'].'/blueprints/'.$theme['functions'][$function]))
		include_once IN_PATH.'themes/'.$settings['site_theme'].'/blueprints/'.$theme['functions'][$function];
	$argumentString = array();
	$argumentKeys = array_keys($arg);
	foreach($argumentKeys as $argK) {
		$argumentString[] = "\$arg[$argumentKeys[$argK]]";
	}
	$argumentString = implode(', ', $argumentString);  
	eval("\$result =& {$function}({$argumentString});");
	return $result;
}
function errors() {
	global $error, $error_die;
	if((isset($error) && !empty($error)) || (isset($error_die) && !empty($error_die)))
		return true;
	else
		return false;
}
if(!defined('INSTALLER') && !$user['guest']) {
	define('AUTHID', substr($user['pass'], 10, 32));
  	$authid = 'authid='.AUTHID;
}
?>