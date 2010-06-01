<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
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
				switch(DB_TYPE) {
					case'mysql';
						$query = 'ALTER TABLE `'.$table.'` ';
						foreach($merged as $column => $type) {
							$query .= 'ADD COLUMN `'.$column.'` '.$type;
							if($currentcount < $count) {
								$query .= ', ';
								++$currentcount;
							}
						}
						if(isset($array[$table]['add_columns']['keys']['primary']))
							$query .= ', PRIMARY KEY (`'.$array[$table]['add_columns']['keys']['primary'].'`)';
						if(isset($array[$table]['keys']['key']))
							$query .= ', KEY `'.$array[$table]['add_columns']['keys']['key'].'` (`'.$array[$table]['add_columns']['keys']['key'].'`)';
							$return[] = $query;
					break;
					case'sqlite';
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
						//get the column types
						print_r($cols);
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
					break;
				}
			}
		}
	return $return;
	}
}
?>