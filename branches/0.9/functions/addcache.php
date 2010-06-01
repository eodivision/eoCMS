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
function addcache($query, $expire = '') {
	$file = './'.CACHE.'/tables.php';
	$contents = file_get_contents($file, 'FILE_TEXT', NULL, 16); 
	$table = explode(" ", $query);
	foreach($table as $num => $tables) {
		if($tables == 'FROM')
			$i = $num+1;
		if(isset($i) && empty($tables))
			$i = $num + 1;
	}
	$data2 = '';
	$tables = unserialize($contents);
	$tables2 = array(''.$query.''=>''.$table[$i].'');
	if(strlen($contents) <= 15)
		$data = $tables2;
	else
		$data = array_merge($tables, $tables2);
	$OUTPUT = "<?php die(); ?>\n".serialize($data);
	file_put_contents($file, $OUTPUT);
}
?>