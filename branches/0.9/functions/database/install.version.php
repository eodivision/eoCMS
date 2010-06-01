<?php
/*
   contains actual code that will be evaluated to return an array of text,{_var_} substitution as defined in "install.description.php"
   "$version" is the array that stores the text, including non-language specific descriptions
   the resulting array is output one table row at a time, and "\r" and "\n" will not perform as expected
   all other variables required must start with "$temp", and as such complex output can be achieved, eg:
$tempcon = mysql_connect({HOST},{USER},{PASS});
if($tempcon){
	$version[] = "&bull; server version : ".mysql_get_server_info();
	$tempresult = mysql_query("SHOW ENGINES;");
	if($tempresult){
		$tempout = "<table border=1 id=db_version class=db_version><tr><th>engine<th>supported<th>comment</tr>";
		while ($tempengine=mysql_fetch_row($tempresult)){
			$tempout .= "<tr>";
			foreach($tempengine as $tempdbtype)
				$tempout .= "  <td>".$tempdbtype."</td>";
			$tempout .= "</tr>";
		}
		$tempout .= "</table>";
		$version[] = $tempout;
	}
}

*/
?>