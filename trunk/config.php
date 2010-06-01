<?php
define('COOKIE_LANG',   'en');
define('COOKIE_NAME',   'eocms');
define('COOKIE_CHILD',  '');
define('CACHE','cache');
$_QUERIES=0;
define('DB_TYPE', 'mysql');
$db['host'] = 'localhost';
$db['user'] = 'root';
$db['pass'] = '';
$db['name'] = 'eocms';
$con = mysql_connect($db['host'], $db['user'], $db['pass']);
if(!$con)
	 die('eoCMS was unable to connect to the database');
$select = mysql_select_db($db['name']);
if(!$select)
	die('eoCMS was unable to select to the database'.$db['name']);
unset($db);
?>