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
function alter_sql($array) {
	//some checks
	if(!is_array($array)) {
		//looks like the tables is not created properly
		//DO SOMTHING HERE UPON FAIL!
	} else {
		foreach($array as $table => $value) {
			if(!array_key_exists('columns', $array[$table]['add_columns']) || !array_key_exists('types', $array[$table]['add_columns'])) {
				//no columns or types set, cant build the query without them
				//DO SOMETHING HERE UPON FAIL!
			} elseif(is_array($array) && count($array[$table]['add_columns']['columns']) != count($array[$table]['add_columns']['types'])) {
				//number of types and columns does not match
				//DO SOMETHING HERE UPON FAIL!
			} else {
				$columns = $array[$table]['add_columns']['columns'];
				$types = $array[$table]['add_columns']['types'];
				//assign the column as key and type as value
				$merged = array_combine($columns, $types);
				$count = count($columns); //count the columns so we know when to stop adding ,
				$currentcount = 1;
				//go through the $merged and begin to build the query
				include(IN_PATH.'functions/database/'.DB_TYPE.'/alter_sql.php');
			}
		}
	return $return;
	}
}
?>