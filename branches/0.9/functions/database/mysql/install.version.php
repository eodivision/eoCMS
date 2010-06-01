<?php
/*
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