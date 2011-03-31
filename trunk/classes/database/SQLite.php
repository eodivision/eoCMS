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
class SQLite extends SQL {
	public function connect($info) {
		$this -> connection = sqlite_open($info, 0666, $SQLiteerror);
	}
	public function error($status = '') {
		$error = sqlite_last_error($this -> connection);
		if($status = 'show')
			return sqlite_error_string($error);
		elseif($error != 0)
			return true;
		else
			return false;
	}
	public function query($query, $cache = '') {
		self::cache_check($query);
		if(empty($cache)) {
			$this->last_resource = sqlite_query($this -> connection, $query);
			++$this -> query_count;
			return $this->last_resoruce;
		} else
			return self::cache($query);
	}
	public function fetch_array($resource = '') {
		return sqlite_fetch_array(self::resource($resource));
	}
	public function fetch_assoc($resource = '') {
		return sqlite_fetch_array(self::resource($resource), SQLITE_ASSOC);
	}
	public function fetch_object($resource = '') {
		return sqlite_fetch_object(self::resource($resource));
	}
	public function fetch_row($resource = '') {
		return sqlite_fetch_array(self::resource($resource), SQLITE_NUM);
	}
	public function affected_rows() {
		return sqlite_changes($this -> connection);
	}
	public function insert_id() {
		return sqlite_last_insert_rowid($this -> connection);
	}
	public function num_rows($resource = '') {
		return sqlite_num_rows(self::resource($resource));
	}
	public function escape($string) {
		return sqlite_escape_string($string);	
	}
}
?>