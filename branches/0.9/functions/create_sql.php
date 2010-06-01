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
function create_sql($array) {
	//some checks
	if(!is_array($array)) {
		//looks like the tables is not created properly
		//DO SOMTHING HERE UPON FAIL!
	} else {
		foreach($array as $table => $value) {
			if(!array_key_exists('columns', $array[$table]) || !array_key_exists('types', $array[$table])) {
			//no columns or types set, cant build the query without them
			//DO SOMETHING HERE UPON FAIL!
			} elseif(is_array($array) && count($array[$table]['columns']) != count($array[$table]['types'])) {
			//number of types and columns does not match
			//DO SOMETHING HERE UPON FAIL!
			} else {
				$query = '';
				//check if the engine is set MySQL only
				if(!isset($array[$table]['engine']))
				$engine = 'MyISAM'; //set the default to MyISAM
				//check if charset is set MySQL only
				if(!isset($array[$table]['charset']))
				$charset = 'latin1'; //set default as latin1
				//assign the column as key and type as value
				$merged = array_combine($array[$table]['columns'], $array[$table]['types']);
				$count = count($merged); //count the columns so we know when to stop adding ,
				$currentcount = 1;
				//go through the $merged and begin to build the query
				switch(DB_TYPE) {
					case'mysql';
						$query .= 'CREATE TABLE `'.$table.'` ( ';
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
						$query .= ' ) ENGINE='.$engine.' DEFAULT CHARSET='.$charset.';';
						$return[] = $query;
					break;
					case'sqlite';
						$query .= 'CREATE TABLE '.$table.' ( ';
						foreach($merged as $column => $type) {
							$query .= ''.$column.' ';
							if(isset($array[$table]['keys']['auto_increment']) && $array[$table]['keys']['auto_increment'] == $column)
								$query .= 'INTEGER PRIMARY KEY';
							else
								$query.= $type;
							if($currentcount < $count) {
								$query .= ', ';
								++$currentcount;
							}
						}
						$query .= ');';
						$return[] = $query;
					break;
					case 'mssql';
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
					break;
				}
			}
		}
		return $return;
	}
}
?>