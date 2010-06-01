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
$query .= 'CREATE TABLE ['.$table.'] ( ';
foreach($merged as $column => $type) {
	$query .= '`'.$column.'` '.$type;
	if(isset($array[$table]['keys']['auto_increment']) && $array[$table]['keys']['auto_increment'] == $column)
		$query .= ' AUTO_INCREMENT';
	if($currentcount < $count) {
		$query .= ', ';
		++$currentcount;
	}
}
if(isset($array[$table]['keys']['primary']))
	$query .= ', PRIMARY KEY (`'.$array[$table]['keys']['primary'].'`)';
if(isset($array[$table]['keys']['key'])) {
	if(is_array($array[$table]['keys']['key'])) {
		foreach($array[$table]['keys']['key'] as $key) {
			$query .= ', KEY `'.$key.'` (`'.$key.'`)';
		}
	} else
		$query .= ', KEY `'.$array[$table]['keys']['key'].'` (`'.$array[$table]['keys']['key'].'`)';
}
$query .= ' );';
$return[] = $query;
?>