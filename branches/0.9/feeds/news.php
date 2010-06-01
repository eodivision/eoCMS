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
  if (!(defined("IN_ECMS"))) {
      die("Hacking Attempt...");
  }
  
  if (!isset($_GET['limit']) || $_GET['limit'] == '' || is_numeric($_GET['limit']) == false) {
      $_GET['limit'] = '10';
  }
  if (isset($_GET['cat']) && preg_match('/^,/', $_GET['cat'])) {
      $replace = str_replace(',', ' OR ', $_GET['cat']);
      $cat = 'AND n.cat = (' . $replace . ')';
  } else {
      $cat = isset($_GET['cat']) ? 'AND n.cat = "' . $_GET['cat'] . '"' : '';
  }
  $query = call('sql_query', "SELECT n.id, n.subject, n.content, nc.name, n.time_created, n.visibility FROM news AS n LEFT OUTER JOIN news_categories AS nc ON n.cat=nc.id WHERE (n.start_time='0' || n.start_time<=" . time() . ") AND (n.end_time='0' || n.end_time>=" . time() . ")" . $cat . " ORDER BY n.time_created DESC LIMIT " . $_GET['limit'] . "");
  while ($fetch = call('sql_fetch_array', $query)) {
      $feeds[] = array('link' => $settings['site_url'] . '/index.php?act=news&readmore=' . $fetch[0], 'title' => html_entity_decode(htmlspecialchars_decode($fetch[1], ENT_QUOTES), ENT_QUOTES), 'description' => $fetch[2], 'category' => $fetch[3], 'date' => $fetch[4], 'guid' => $settings['site_url'] . '/index.php?act=news&readmore=' . $fetch[0]);
  }
  $atom_link = $settings['site_url'] . '/index.php?act=feeds&amp;type=news';
?>