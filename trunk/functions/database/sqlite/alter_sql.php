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
$currentcount = 1;
//SQLite does not fully support ALTER syntax so we have to create a temporary table first
$query = 'CREATE TEMPORARY TABLE '.$table.'2(';
//get the column names
$sql = call('sql_query', "SELECT * FROM $table LIMIT 1");
$cols = call('sql_fetch_column_types', $table);
$colcount = count($cols);
foreach($cols as $column => $type) {
	if(!in_array($column, $columns)) {
		$query.= $column;
		if($currentcount < $colcount) {
			$query .= ', ';
			++$currentcount;
		}
	}
}
$query.=')';
$return[] = $query;
//reset currentcount
$currentcount = 1;
//create the insert query
$query = 'INSERT INTO '.$table.'2 SELECT ';
foreach($cols as $column => $type) {
	if(!in_array($column, $columns)) {
		$query.= $column;
		if($currentcount < $colcount) {
			$query .= ', ';
			++$currentcount;
		}
	}
}
$return[] = $query;
//drop the old table
$return[] = 'DROP TABLE '.$table;
//reset currentcount
$currentcount = 1;
//create the new table
$query = 'CREATE TABLE '.$table.' ( ';
foreach($cols as $column => $type) {
	$query .= ''.$column.' '.$type;
	if(isset($array[$table]['keys']['add_columns']['auto_increment']) && $array[$table]['add_columns']['keys']['auto_increment'] == $column)
		$query .= ' PRIMARY KEY';
	if($currentcount < $colcount) {
		$query .= ', ';
		++$currentcount;
	}
}
$return[] = $query;
//reset currentcount
$currentcount = 1;
//create the insert query
$query = 'INSERT INTO '.$table.' SELECT ';
foreach($cols as $column => $type) {
	if(!in_array($column, $columns)) {
		$query.= $column;
		if($currentcount < $colcount) {
			$query .= ', ';
			++$currentcount;
		}
	}
}
$return[] = $query;
?>