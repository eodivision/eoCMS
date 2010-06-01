<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
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