<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons 
	Attribution-Share Alike 3.0 United States License. 
	To view a copy of this license, visit 
	http://creativecommons.org/licenses/by-sa/3.0/us/
	or send a letter to Creative Commons, 171 Second Street, 
	Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/

function listpms($uid) {
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
	$sql = call('sql_query', "SELECT * FROM pm WHERE to_send = '$uid' AND mark_delete = '0' AND mark_sent = '1' ORDER BY time_sent DESC LIMIT $offset, $rowsPerPage");
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