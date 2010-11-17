<?php
/*
	eoCMS © 2007 - 2010, a Content Management System
	by James Mortemore
	http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
abstract class SQL {
	var $last_resource;
	var $table_cache;
	function __construct() {
		$this -> table_cache = unserialize(file_get_contents('./'.CACHE.'/tables.php', NULL, NULL, 16));
	}
	
	// Declare abstract functions
	public abstract function connect($info);
	public abstract function query($query, $cache = '');
	public abstract function fetch_array($resource);
	public abstract function fetch_assoc($resource);
	public abstract function fetch_object($resource);
	public abstract function fetch_row($resource);
	public abstract function affected_rows();
	public abstract function insert_id();
	public abstract function num_rows($resource);
	public abstract function error($show);
	public abstract function escape($string);
	
	// Declare builtin functions
	public function resource($resource) {
		if(empty($resource))
			return $this -> last_resource;
		else
			return $resource;
	}
	public function cache_check($query) {
		/**
	 	* Checks to see if query passed is changing data in a table cached by another query
	 	* if it is, it deletes it. Also caches the query if the cache argument given.
		* Returns: @Void
		*/
		// Check if tables.php is'nt empty and that the query contains a statement which will modify the database's data
		if('' != $this -> table_cache && (strpos($query, 'UPDATE') !== false || strpos($query, 'INSERT') !== false || strpos($query, 'DELETE') !== false)) {
			foreach($this -> table_cache as $sql => $table) { // Go through each item within the tables.php array
				if(is_array($table)) { // If its a nested array loop through it
					foreach($table as $t) {
						if(strpos($query, $t)) // Check if the query is within the array, if it is destroy the cache
							self::clear_cache($sql);
					}
				} else { // Same as above but without the looping
					if(strpos($data, $table))
						self::clear_cache($sql);
				}
			}
		}
	}
	
	public function create_cache($query) {
		/**
	 	* Creates the cache file
		* Returns: false if cache file does exist
		*/
		$location = './'.CACHE.'/'.md5($query).'.php'; // The soon to be cache file
		if(!file_exists($location)) { // Check if the file doesn't exist
			$data = array();
			$sql = $this -> query($query); // Run the query
			while($fetch = $this -> fetch_array()) // Loop through the fetched data
				$data[] = $fetch;
			file_put_contents($location, "<?php die(); ?>\n".serialize($data)); // Create the file
		} else
			return false;
	}
	
	public function add_table_cache($query) {
		/**
	 	* Updates the tables.php file with the new cached query
		* Returns: @Void
		*/
		// Split the query up where there are spaces
		$splitquery = explode(" ", $query);
		// Where we store the table names, mainly for LEFT JOINS
		$table_cache = array();
		// Go through each of the now splitted query items
		foreach($splitquery as $num => $tables) {
			if($tables == 'FROM')
				$table_cache[] = $splitquery[$num + 1]; // We found the FROM table
			else if($tables == 'LEFT') { // SQLite only supports LEFT joins :(
				// We need to get the next one and see what it is
				if($splitquery[$num + 1] == 'JOIN')
					$table_cache[] = $splitquery[$num + 1]; // Yay its a normal join
				else if($splitquery[$num + 1] == 'OUTER' || $splitquery[$num + 1] == 'INNER')
					$table_cache[] = $splitquery[$num + 3]; // The 3rd one should be the table, after the word JOIN
					
			}
		}
		$tables = array($query => $table_cache); 
		if(strlen(serialize($this -> table_cache)) <= 15) // If its <= 15 then its empty, meaning no merging needed
			$this -> table_cache = $tables;
		else
			$this -> table_cache = array_merge($tables, $this -> table_cache); // Merge the table_cache in memory with the new cached query 
		file_put_contents('./'.CACHE.'/tables.php', "<?php die(); ?>\n".serialize($this -> table_cache)); // Update tables.php
	}
	
	public function get_cache($query) {
		/**
	 	* Grabs the contents of the cached query file
		* Returns: Array data from the cached query
		*/
		$location = './'.CACHE.'/'.md5($query).'.php';
		if(@file_exists($location) && @is_readable($location))
			return unserialize(file_get_contents($location, NULL, NULL, 16));
		else
			return false;
	}
	public function cache($query) {
		/**
	 	* Caches the query into the system
		* Returns: Database data in array form
		*/
		if(!array_key_exists($query, $this -> table_cache))
			self::add_table_cache($query);
		self::create_cache($query);
		$sql_data = self::get_cache($query);
		if($sql_data === false) {
			// Unable to read the file, somethings gone wrong
			$sql = $this -> query($query); // Run the query anyway
			while($fetch = $this -> fetch_array($query)) // Loop through the data
				$data[] = $fetch;
			$sql_data = $data; // Set the data to return
			// We do this to prevent any errors from no data being returned
		}
		return $sql_data;
	}
	public function clear_cache($query = '') {
		/**
	 	* Destroys all cache files and empties tables.php if $query is empty
		* Otherwise it clears the cache file for that query
		* Returns: @Void
		*/
		// Reset the tables.php
		if($query == '') {
			file_put_contents('./'.CACHE.'/tables.php', "<?php die(); ?>\n");
			// Remove any cache files
			$dir = opendir(CACHE.'/');
				while(($file = readdir($dir)) !==false ) {
					if(strlen($file) == 36 && $file != 'index.php' && $file != 'tables.php')
						unlink('./'.CACHE.'/'.$file);
				}
			closedir($dir);
		} else {
			if(file_exists('./'.CACHE.'/'.md5($query).'.php'))
				unlink('./'.CACHE.'/'.md5($query).'.php');
		}
	}
}
?>
