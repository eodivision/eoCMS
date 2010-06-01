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

function listoutpms($uid) {
# how many rows to show per page
	$rowsPerPage = 20;
# by default we show first page
	$pageNum = 1;
# if $_GET['page'] defined, use it as page number
	if(isset($_GET['page'])) {
		$pageNum = $_GET['page'];
	}
# counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
	$sql = call('sql_query', "SELECT * FROM pm WHERE sender = '$uid' AND mark_sent_delete = '0' AND mark_sent = '1' ORDER BY time_sent DESC LIMIT $offset, $rowsPerPage");
	if (call('sql_num_rows', $sql) == 0) {
		return false;
	} else {
		while ($row = call('sql_fetch_array',$sql)) {
			$fetch[] = $row;
		}
		return $fetch;
	}
}
?>