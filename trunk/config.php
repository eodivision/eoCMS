<?php
// Set the database type
define('DB_TYPE', 'MySQL');
// Set the cache folder
define('CACHE', 'cache');
// Work around to include the class using a constant
$db['type'] = constant('DB_TYPE');
// Database connection info
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['password'] = '';
$db['database'] = 'eocms';
// Include the database class
require_once(IN_PATH.'classes/database/'.$db['type'].'.php');
// Initialise class
$sql = new $db['type'];
// Connect
$sql -> connect($db);
// Unset the database connection info once established link
unset($db);
?>