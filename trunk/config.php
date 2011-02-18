<?php
// Set the cache folder
define('CACHE', 'cache');
// Set the database type
define('DB_TYPE', 'MySQL');
// Set the prefix
define('PREFIX', '');
// Work around to include the class using a constant
$database['type'] = constant('DB_TYPE');
// Database connection info
$database['host'] = 'localhost';
$database['user'] = 'root';
$database['password'] = '';
$database['database'] = 'eocms';
?>