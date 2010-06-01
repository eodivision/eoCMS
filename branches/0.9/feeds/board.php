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
  if (isset($_GET['board']) && is_numeric($_GET['board']) && preg_match('/^,/', $_GET['board'])) {
      $cat = str_replace(',', ' OR ', $_GET['board']);
      $cat = 'AND b.id = ("' . $cat . '")';
  } else {
      $cat = isset($_GET['board']) && is_numeric($_GET['board']) ? 'WHERE b.id = "' . $_GET['board'] . '"' : '';
  }
  $sql = call('sql_query', "SELECT b.board_name, t.replies, t.topic_id, p.post_time, p.name_author, p.author_id, p.id, p.subject, p.message, b.visible FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply " . $cat . " GROUP BY t.topic_id ORDER BY p.post_time DESC LIMIT " . $_GET['limit']);
  if (call('sql_num_rows', $sql) == 0) {
      $error_die[] = 'Either this board does not exist or you do not have permission to view it';
      header("Location: index.php");
      exit;
  } else {
      while ($fetch = call('sql_fetch_array', $sql)) {
          if (call('visiblecheck', $user['membergroup_id'], $fetch[9])) {
              if (strlen($fetch[8]) >= 225) {
                  $message = substr($fetch[8], 0, 225) . '...';
              } else {
                  $message = $fetch[8];
              }
              $replies = ceil($fetch[1] / $settings['posts_topic']);
              if ($replies > 1) {
                  $stpages = $settings['site_url'] . '/index.php?act=viewtopic&id=' . $fetch[2] . '&page=' . $replies . '#' . $fetch[6];
              } else {
                  $stpages = $settings['site_url'] . '/index.php?act=viewtopic&id=' . $fetch[2] . '#' . $fetch[6];
              }
              $feeds[] = array('link' => trim($stpages), 'title' => html_entity_decode(htmlspecialchars_decode($fetch[7], ENT_QUOTES), ENT_QUOTES), 'description' => call('bbcode', $message), 'category' => $fetch[0], 'date' => $fetch[3], 'guid' => trim($stpages));
          }
      }
  }
  $atom_link = $settings['site_url'] . '/index.php?act=feeds&amp;type=board';
?>