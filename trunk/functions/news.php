<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
  function news() {
global $user;
	  	  // how many rows to show per page
$rowsPerPage = 5;
// by default we show first page
$pageNum = 1;
// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']) && is_numeric($_GET['page']))
{
    $pageNum = $_GET['page'];
}
// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;
              $sql = call('sql_query', "SELECT n.id, n.subject, n.content, n.cat, n.created_by, n.visibility, nc.name, nc.image, n.views, n.time_created, n.extended, COUNT(c.type_id) FROM news n LEFT OUTER JOIN news_categories nc ON n.cat=nc.id LEFT OUTER JOIN comments c ON n.id = c.type_id AND c.comment_type = 'news' WHERE (n.start_time='0' OR n.start_time<=".time()." ".(DB_TYPE=='sqlite' ? "OR n.start_time=''" : "").") AND (n.end_time='0' OR n.end_time>=".time()." ".(DB_TYPE=='sqlite' ? "OR n.end_time=''" : "").") GROUP BY n.id ORDER BY n.time_created DESC LIMIT $offset, $rowsPerPage");
          if (call('sql_num_rows', $sql) == 0) {
			  return false;
          }
          $fetch = array();
		    while ($row = call('sql_fetch_array',$sql)) {
			if(call('visiblecheck', $user['membergroup_id'], $row[5])) {
              $fetch[] = array('news_id'=>$row[0],
			  				   'subject'=>$row[1],
							   'content'=>$row[2],
							   'cat_id'=>$row[3],
							   'created_by'=>$row[4],
							   'visibility'=>$row[5],
							   'cat_name'=>$row[6],
							   'cat_image'=>$row[7],
							   'views'=>$row[8],
							   'time_created'=>$row[9],
							   'extended'=>$row[10],
							   'comments'=>$row[11]);
							   }
          }
          return $fetch;
}
?>