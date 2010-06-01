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
function article_cat($id) {
	global $user, $error_die;
	if(!isset($id) || empty($id) || !is_numeric($id))
		$error_die[] = 'No category selected';
	$sql = call('sql_query', "SELECT c.visible, COUNT(p.cat), p.subject, p.summary, p.id FROM article_categories c LEFT JOIN articles p ON c.id=p.cat WHERE c.id = '".$id."' GROUP BY p.id");
	if (call('sql_num_rows', $sql) == 0)
		return false;
	$fetch = array();
	while($row = call('sql_fetch_array',$sql)) {
	if(call('visiblecheck', $user['membergroup_id'], $row[0])) {
		$fetch[] = array('articles'=>$row[1],
						 'subject'=>$row[2],
						 'summary'=>$row[3],
						 'id'=>$row[4]);
		}
	}
	return $fetch;
}
?>