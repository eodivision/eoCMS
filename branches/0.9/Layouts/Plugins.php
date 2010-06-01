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
if(!(defined("IN_ECMS"))) die("Hacking Attempt...");
$pluginsql = call('sql_query', "SELECT layout, folder FROM plugins WHERE active = '1' AND id = '" . $_GET['id'] . "'");
if(call('sql_num_rows', $pluginsql) != 0) {
	while($pluginfetch = call('sql_fetch_array',$pluginsql)) {
		if(file_exists('Plugins/' . $pluginfetch['folder'] . '/Layouts/' . $pluginfetch['layout']))
			include('Plugins/' . $pluginfetch['folder'] . '/Layouts/' . $pluginfetch['layout']);
		else
			header("Location: index.php");
	}
} else //plugin doesnt exist, redirect back to the index
	header("Location: index.php");
?>