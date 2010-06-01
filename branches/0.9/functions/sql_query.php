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
function sql_query($data, $cache='') {
	global $_QUERIES, $tables;
	if(defined('DB_TYPE')) {
		if('' != $tables && (strpos($data, 'UPDATE') !== false || strpos($data, 'INSERT') !== false || strpos($data, 'DELETE') !== false)) {
			foreach($tables as $query => $table) {
				if(strpos($data, $table) !== false)
					call('destroycache', $query);
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
			if(DB_TYPE == 'mysql')
				$sql = mysql_query($data);
			if(DB_TYPE == 'sqlite') {
				global $con;
				$sql = sqlite_query($con, $data);
			}
		}
		return $sql;
	}
}
?>