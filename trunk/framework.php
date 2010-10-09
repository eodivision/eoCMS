<?php
/*
	eoCMS © 2007 - 2010, a Content Management System
	by James Mortemore, Ryan Matthews
	http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
// Snippet from php.net by bohwaz
// below function kills register globals
// to remove any possible security threats if it is on
if(ini_get('register_globals')) {
	function unregister_globals(){
		foreach(func_get_args() as $name) {
			foreach($GLOBALS[$name] as $key => $value) {
				if (isset($GLOBALS[$key]))
					unset($GLOBALS[$key]);
			}
		}
	}
	unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', '_SESSION');
}
$in = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
if(get_magic_quotes_gpc()) {
	while(list($k, $v) = each($in)) {
		foreach($v as $key => $val) {
			if(!is_array($val)) 
				$in[$k][htmlspecialchars(stripslashes($key), ENT_QUOTES)] = htmlspecialchars(stripslashes($val), ENT_QUOTES);
			else
				$in[] =& $in[$k][$key];
		}
	}
} else {
	while(list($k, $v) = each($in)) {
		foreach($v as $key => $val) {
			if(!is_array($val))
				$in[$k][htmlspecialchars($key, ENT_QUOTES)] = htmlspecialchars($val, ENT_QUOTES);
			else
				$in[] =& $in[$k][$key];
		}
	}
}
unset($in);
// Snippet from php.net by Steve
// below function is created if the host does not have the JSON Functions installed
if(!function_exists('json_encode')) {
	function json_encode($a = false) {
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
			foreach ($a as $v)
				$result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		} else {
			foreach ($a as $k => $v)
				$result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}
define('IN_PATH', realpath('.') . '/'); // this allows us to use absolute urls based on the root of your eoCMS installation
// load the config containing database connection
require(IN_PATH.'config.php');
// this is the auto loader, if the class isn't already defined, it will attempt to load it
function __autoload($class_name) {
    require_once IN_PATH.'functions/class.'.$class_name.'.php';
}
// Check for error reporting
if(defined('DEBUG')) 
    error_reporting('E_ALL');
else {
    // Disable errors
    error_reporting(0);
    @ini_set('display_errors', 0); // Fallback incase error_reporting(0) fails
}
?>