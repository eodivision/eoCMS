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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
$body .= '<div class="panel-header">'.theme('title', 'Latest Articles') . '</div>'. theme('start_content_panel');
 $sql = call('sql_query', "SELECT c.visible, p.subject, p.id FROM article_categories c LEFT JOIN articles p ON c.id=p.cat GROUP BY p.id ORDER BY p.time_created DESC LIMIT 5");
if(call('sql_num_rows', $sql) != 0) {
$fetch = array();
while ($row = call('sql_fetch_array',$sql)) {
			if(call('visiblecheck', $user['membergroup_id'], $row[0]) && !empty($row[2])) {
              $fetch[] = array('subject'=>$row[1],
							   'id'=>$row[2]);
							   }
          }
$body .= '<ul>';
foreach($fetch as $p) {
			$body .= '<li><a href="'.$settings['site_url'].'/index.php?act=articles&amp;sa=article&amp;article='.$p['id'].'">'.call('limit_text', $p['subject'], 23, 5).'</a></li>';
			}
			$body .= '</ul>';
} else {
$body .= 'No articles';
}
$body .= theme('end_content');
?>