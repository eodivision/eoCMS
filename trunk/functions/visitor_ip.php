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

function visitor_ip() {
	if(!isset($_SERVER['SERVER_ADDR'])) $_SERVER['SERVER_ADDR'] = '';
	$ip = ($_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR'] && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	if($ip == '::1') //weird localhost bug, set the right IP
		$ip = '127.0.0.1';
	return $ip;
}
?>