<?php
/*
	eoCMS © 2007 - 2010, a Content Management System
	by James Mortemore
	http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/

/**
 * Snippet from php.net by bohwaz
 * below function kills register globals
 * to remove any possible security threats if it is on
 */
if(ini_get('register_globals')) {
	function unregister_globals() {
		foreach(func_get_args() as $name) {
			foreach($GLOBALS[$name] as $key => $value) {
				if(isset($GLOBALS[$key]))
					unset($GLOBALS[$key]);
			}
		}
	}
	unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', '_SESSION');
}
if(!function_exists('json_encode')) {
	function json_encode($a = false) {
		/**
     	* This function encodes a PHP array into JSON
		* Function from php.net by Steve
     	* Returns: @JSON
     	*/
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
function random_string($length = 32) {
	/**
	* Generates a random alphanumeric string with a specfied length
	* Returns: @String
	*/
	return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);	
}
define('IN_PATH', realpath('.') . '/'); // This allows us to use absolute urls based on the root of your eoCMS installation

// Class loading functions

$eocms['classes'] = array(); // This variable stores a list of all loaded classes

function __autoload($class_name) {
	/**
	 * This function allows us to automagically load our class files,
	 * should they not already be loaded. It is called automatically 
	 * by PHP.
	 * Returns: @Void
	 */
	global $eocms;
	
    require_once(IN_PATH.'classes/'.$class_name.'.class.php');
    $eocms['classes'][] = $class_name; // Update class array with newly loaded class
}

function class_is_loaded($class_name) {
	/**
	 * This function allows us to check whether a class has been
	 * loaded into the system. 
	 * Returns: @Boolean
	 */
	 global $eocms;
	 
	 if(in_array($class_name, $eocms['classes']))
		 return true;
	 else
		 return false;
}

// Load the config containing database connection and other related installation settings
require(IN_PATH.'config.php');
// Include the database class
require_once(IN_PATH.'classes/database/'.$database['type'].'.php');
// Initialise class
$db = new $database['type'];
// Connect
$db -> connect($database);
// Unset the database connection info once established link
unset($database);
function db() {
	/**
	 * Allows usage of db() instead of $db to eliminate the need to global $db.
	 * For example: db() -> escape() can be used instead of $db -> escape().
	 * Returns: @Object
	 */
	global $db;
	
	return $db;
}
/** 
 * Encodes HTML within below globals, takes into account magic quotes.
 * Note: $_SERVER is not sanitised, be aware of this when using it.
 * Why repeat it twice? Checking magic quotes everytime in a loop is slow and so is any additional if statements ;)
 */
$in = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
if(get_magic_quotes_gpc()) {
	while(list($k, $v) = each($in)) {
		foreach($v as $key => $val) {
			if(!is_array($val)) 
				$in[$k][db() -> escape(htmlspecialchars(stripslashes($key), ENT_QUOTES))] = db() -> escape(htmlspecialchars(stripslashes($val), ENT_QUOTES));
			else
				$in[] =& $in[$k][$key];
		}
	}
} else {
	while(list($k, $v) = each($in)) {
		foreach($v as $key => $val) {
			if(!is_array($val))
				$in[$k][db() -> escape(htmlspecialchars($key, ENT_QUOTES))] = db() -> escape(htmlspecialchars($val, ENT_QUOTES));
			else
				$in[] =& $in[$k][$key];
		}
	}
}

unset($in);

// Create eocms variable with basic info
$eocms = array('query_count' => 0);

// Grab the settings and cache them
$settingsSQL = db() -> query("SELECT * FROM ".PREFIX."settings", 'cache');
foreach($settingSQL as $settingrow)
	$eocms['settings'][$settingrow['variable']] = $settingrow['value'];
// Rather than using $eocms we use functions to eliminate the need for globalising variables in many functions, plus it looks neater :D
function setting($variable, $modify = '') {
	/**
	 * Modifies $variable column in settings table
	 * with the contents of $modify
	 * If modify emtpy it returns the setting data from the table
	 * Returns: Setting data from settings table: @Array
	 */
	global $eocms;
	
	if(empty($modify)) {
		db() -> query("UPDATE ".PREFIX."settings SET value = '$modify' WHERE variable = '$variable'");
		return $modify;
	} else
		return (isset($eocms['settings'][$variable]) ? $eocms['settings'][$variable] : '');
}

// Start the user class
$user = new User_Management();
function user($variable = '', $modify = '') {
	/**
	 * Modifies $variable column in users table with the contents of $modify
	 * If modify emtpy it returns the user data from the table
	 * If neither $variable or $modify are set, this function can be used instead of $user forthe User_Management class
	 * Returns: @String or @Object
	 */
	global $eocms;
	
	if(empty($variable) && empty($modify)) {
		// We assume it is a class call
		global $user;
		
		return $user;
	} else {
		if(!empty($modify)) {
			db() -> query("UPDATE ".PREFIX."users SET $variable = '$modify' WHERE id = '".$eocms['user']['id']."'");
			return $modify;
		} else
			return (isset($eocms['user'][$variable]) ? $eocms['user'][$variable] : '');
	}
}

// Error functions

function error($error) {
	/**
	 * Adds an error to the system
	 * Returns: @Void
	 */
	global $eocms;
	
	$eocms['error'][] = $error;	
}
function fatal_error($error) {
	/**
	 * Adds a fatal error to the system
	 * Displays only error on the page (handled by theme system)
	 * Returns: @Void
	 */
	global $eocms;
	
	$eocms['fatal_errors'][] = $error;
}
function errors() {
	/**
	 * Returns true if errors found, false if none found
	 * Returns: @Boolean
	 */
	global $eocms;
	
	return (isset($eocms['errors']) && !empty($eocms['errors'])) || (isset($eocms['fatal_errors']) && !empty($eocms['fatal_errors']));
}

// Check for error reporting
if(setting('debug')) 
    error_reporting('E_ALL');
else {
    // Disable errors
    error_reporting(0);
    @ini_set('display_errors', 0); // Fallback incase error_reporting(0) fails
}

// Below provides an optional addition to links to prevent other users from creating dangerous links which the user may accidently click
if(!user('guest'))
	define('AUTHID', substr(user('pass'), 10, 32));
?>
