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
class MySQL extends SQL {
	public function connect($info) {
		mysql_connect($info['host'], $info['user'], $info['password']);
		mysql_select_db($info['database']);
	}
	public function error($status = '') {
		if($status = 'show')
			return mysql_error();
		elseif(is_resource(self::resource($status)))
			return true;
		else
			return false;
	}
	public function query($query, $cache = '') {
		self::cache_check($query);
		if(empty($cache)) {
			global $eocms;
			$this -> last_resource = mysql_query($query);
			++$eocms['query_count'];
			return $this -> last_resource;
		} else
			return self::cache($query);
	}
	public function fetch_array($resource = '') {
		return mysql_fetch_array(self::resource($resource));
	}
	public function fetch_assoc($resource = '') {
		return mysql_fetch_assoc(self::resource($resource));
	}
	public function fetch_object($resource = '') {
		return mysql_fetch_object(self::resource($resource));
	}
	public function fetch_row($resource = '') {
		return mysql_fetch_row(self::resource($resource));
	}
	public function affected_rows() {
		return mysql_affected_rows();
	}
	public function insert_id() {
		return mysql_insert_id();
	}
	public function num_rows($resource = '') {
		if(is_resource(self::resource($resource)))
			return mysql_num_rows(self::resource($resource));
		else
			return count(self::resource($resource));
	}
	public function escape($string) {
		return mysql_real_escape_string($string);	
	}
}
?>