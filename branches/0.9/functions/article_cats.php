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
function article_cats() {
	global $user;
    $sql = call('sql_query', "SELECT c.id, c.name, c.description, c.visible, COUNT(p.cat) FROM article_categories c LEFT JOIN articles p ON c.id=p.cat GROUP BY c.id ORDER BY c.item_order ASC");
	if (call('sql_num_rows', $sql) == 0)
		return false;
	$fetch = array();
	while ($row = call('sql_fetch_array',$sql)) {
		if(call('visiblecheck', $user['membergroup_id'], $row[3])) {
			$fetch[] = array('cat_id'=>$row[0],
							 'name'=>$row[1],
							 'description'=>$row[2],
							  'articles'=>$row[4]);
		}
	}
	return $fetch;
}
?>