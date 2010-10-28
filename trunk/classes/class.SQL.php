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
	public abstract function connect($info);
	public abstract function query($query);
	public abstract function fetch_array($resource);
	public abstract function fetch_assoc($resource);
	public abstract function fetch_object($resource);
	public abstract function fetch_row($resource);
	public abstract function affected_rows();
	public abstract function insert_id();
	public abstract function num_rows($resource);
	public function resource($resource) {
		if(empty($resource))
			return $this->last_resource;
		else
			return $resource;
	}
	public abstract function error($show);
}
require('database/'.DB_TYPE.'.php');
?>