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
function sql_query($data, $cache='') {
	global $_QUERIES, $tables, $con;
	if(defined('DB_TYPE')) {	
		if('' != $tables && (strpos($data, 'UPDATE') !== false || strpos($data, 'INSERT') !== false || strpos($data, 'DELETE') !== false)) {
			foreach($tables as $query => $table) {
				if(is_array($table)) {
					foreach($table as $t) {
						if(strpos($data, $t))
							call('destroycache', $query);
					}
				} else {
					if(strpos($data, $table))
						call('destroycache', $query);
				}
			}
		}
		if(!empty($cache)) {
			if(!($tables))
				call('addcache', $data);
			elseif(!array_key_exists($data, $tables))
				call('addcache', $data);
			call('createcache', $data);
			$sql = call('getcache', $data);
			if($sql === false) {
				//uh oh looks like it cant read the file, weird server set up
				while($fetch = call('sql_fetch_array', call('sql_query', $data))) {
					//Just go through the query like normal but output it as though it was from the cache
		  			$data[] = $fetch;
				}
				//Return the query data in an arary
				$sql = $data;
			}
		} else {
			++$_QUERIES;
			if(defined('DB_TYPE'))
				include(IN_PATH.'functions/database/'.DB_TYPE.'/sql_query.php');
		}
		return $sql;
	}
}
?>