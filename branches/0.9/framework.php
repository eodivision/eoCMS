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
if(defined('INSTALLER')) {
	$time = explode(' ', microtime());
	$time = $time[1] + $time[0]; # return array
	$begintime = $time; # define begin time
}
session_start();
# Snippet from php.net by bohwaz
# below function kills register globals
#  to remove any possible security threats if it is on
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
if (!function_exists('json_encode')) {
  function json_encode($a=false) {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}
if(!defined('INSTALLER')) {
	require('./config.php');
	//get the cached tables
	$tables = file_get_contents('./'.CACHE.'/tables.php', NULL, NULL, 16);
	$tables = unserialize($tables);
}
  /************************************************************
   **  call('function file name', additional arguments after **
   ************************************************************/ 	
function call() {
   $arg = func_get_args();
   $num = func_num_args();
   include_once IN_PATH.'functions/' . $arg[0] . '.php';
   switch($num) {
   case'1';
   return $arg[0]();
   break;
   case'2';
      return $arg[0]($arg[1]);
   break;
   case'3';
      return $arg[0]($arg[1], $arg[2]);
   break;
   case'4';
      return $arg[0]($arg[1], $arg[2], $arg[3]);
   break;
   case'5';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4]);
   break;
   case'6';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5]);
   break;
   case'7';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
   break;
      case'7';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
   break;
   case'8';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]);
   break;
   case'9';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8]);
   break;
   case'10';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9]);
   break;
   case'11';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10]);
   break;
   case'12';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11]);
   break;
   case'13';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12]);
   break;
   case'14';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13]);
   break;
   case'15';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14]);
   break;
   case'16';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14], $arg[15]);
   break;
   }
}
  /****************************************************************************
   **  call('function file name', 'plugin folder', additional arguments after**
   ****************************************************************************/ 
function plugin() {
   $arg = func_get_args();
   $num = func_num_args();
   include_once IN_PATH.'Plugins/' . $arg[1] . '/functions/' . $arg[0] . '.php';
   switch($num) {
   case'2';
   return $arg[0]();
   break;
   case'3';
      return $arg[0]($arg[2]);
   break;
   case'4';
      return $arg[0]($arg[2], $arg[3]);
   break;
   case'5';
      return $arg[0]($arg[2], $arg[3], $arg[4]);
   break;
   case'6';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5]);
   break;
   case'7';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
   break;
   case'8';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]);
   break;
   case'9';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8]);
   break;
   case'10';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9]);
   break;
   case'11';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10]);
   break;
   case'12';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11]);
   break;
   case'13';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12]);
   break;
   case'14';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13]);
   break;
   case'15';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14]);
   break;
   case'16';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14], $arg[15]);
   break;
   case'17';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14], $arg[15], $arg[16]);
   break;
   case'18';
      return $arg[0]($arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14], $arg[15], $arg[16], $arg[17]);
   break;
   }
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
/*******************************************************************************************************************************
 **  theme('theme function file name')                     																	  **
 **    includes functions within current theme/functions/ if it exists, else it will include the function in functions/themes **
 *******************************************************************************************************************************/
 # use user defined theme, or default site theme if not available
if(isset($_GET['theme']) && file_exists('themes/'.$_GET['theme'].'/theme-info.php'))
	$settings['site_theme'] = $_GET['theme'];
elseif (isset($user['theme']) && file_exists('themes/' . $user['theme'] . '/theme-info.php'))
	$settings['site_theme'] = $user['theme'];
elseif (isset($settings['site_theme']) && file_exists('themes/' . $settings['site_theme'] . '/theme-info.php'))
	$settings['site_theme'] = $settings['site_theme'];
else
	$settings['site_theme'] = 'default';
if(!defined('INSTALLER')) {
	# set the theme settings
	call('getthemesettings');
}
function theme() {
   global $user, $settings;
   $arg = func_get_args();
   $num = func_num_args();
   if(file_exists('themes/' . $settings['site_theme'] . '/functions/'.$arg[0].'.php'))
   	include_once IN_PATH.'themes/' . $settings['site_theme'] . '/functions/'.$arg[0].'.php';
   else
   	include_once IN_PATH.'functions/themes/'.$arg[0].'.php';
   switch($num) {
   case'1';
      return $arg[0]();
   break;
   case'2';
      return $arg[0]($arg[1]);
   break;
   case'3';
      return $arg[0]($arg[1], $arg[2]);
   break;
   case'4';
      return $arg[0]($arg[1], $arg[2], $arg[3]);
   break;
   case'5';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4]);
   break;
   case'6';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5]);
   break;
   case'7';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
   break;
      case'7';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
   break;
   case'8';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]);
   break;
   case'9';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8]);
   break;
   case'10';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9]);
   break;
   case'11';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10]);
   break;
   case'12';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11]);
   break;
   case'13';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12]);
   break;
   case'14';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13]);
   break;
   case'15';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14]);
   break;
   case'16';
      return $arg[0]($arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14], $arg[15]);
   break;
   }
}
function errors() {
	global $error, $error_die;
	if((isset($error) && !empty($error)) || (isset($error_die) && !empty($error_die)))
		return true;
	else
		return false;
}
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
if(!defined('INSTALLER') && !$user['guest']) {
	define('AUTHID', substr($user['pass'], 10, 32));
  	$authid = 'authid='.AUTHID;
}
?>