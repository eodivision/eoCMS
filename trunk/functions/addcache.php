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
function addcache($query, $expire = '') {
	// location of the file where we keep the list of all the cached queries
	$file = './'.CACHE.'/tables.php';
	// only grab data AFTER 16 characters from the file
	$contents = file_get_contents($file, 'FILE_TEXT', NULL, 16);
	// split the query up where there are spaces
	$splitquery = explode(" ", $query);
	// where we store the table names, mainly for LEFT JOINS
	$table_cache = array();
	// go through each of the now splitted query items
	foreach($splitquery as $num => $tables) {
		if($tables == 'FROM')
			$table_cache[] = $splitquery[$num + 1]; // we found the FROM table
		elseif($tables == 'LEFT') { // SQLite only supports LEFT joins :(
			//we need to get the next one and see what it is
			if($splitquery[$num + 1] == 'JOIN')
				$table_cache[] = $splitquery[$num + 1]; // yay its a normal join
			elseif($splitquery[$num + 1] == 'OUTER' || $splitquery[$num + 1] == 'INNER') {
				$table_cache[] = $splitquery[$num + 3]; // the 3rd one should be the table, after the word JOIN
			}
				
		}
	}
	$data2 = '';
	$tables = unserialize($contents);
	$tables2 = array($query => $table_cache);
	if(strlen($contents) <= 15)
		$data = $tables2;
	else
		$data = array_merge($tables, $tables2);
	$OUTPUT = "<?php die(); ?>\n".serialize($data);
	file_put_contents($file, $OUTPUT);
}
?>