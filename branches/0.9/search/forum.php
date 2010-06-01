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
  $boards = sql_query("SELECT * FROM forum_boards", 'cache');
  $option = '';
  foreach ($boards as $fetch) {
      if (call('visiblecheck', $user['membergroup_id'], $fetch['visible']))
          $option .= '<option value="' . $fetch['id'] . '"' . (isset($_GET['forum']) && $_GET['forum'] == $fetch['id'] ? ' selected="selected"' : '') . '>' . $fetch['board_name'] . '</option>';
  }
  $buttons[] = '<input type="radio" name="where" id="forum" value="forum"' . ((isset($_GET['where']) && $_GET['where'] == 'forum') || !isset($_GET['where']) ? ' checked="checked"' : '') . ' /><label for="forum">Forums:</label> <select name="forum"><option value="all"' . (isset($_GET['forum']) && $_GET['forum'] == 'all' ? ' selected="selected"' : '') . '>All Boards</option>' . $option . '</select>';
  if (isset($search2) && trim($search2 !== '')) {
    if (isset($_GET['where']) && $_GET['where'] == 'forum') {
        $sql = sql_query("SELECT p.id AS id, p.topic_id AS topic_id, p.subject AS subject, p.message AS message, b.visible AS visible, p.author_id AS author_id, p.post_time AS post_time, b.id AS board_id, b.board_name AS board_name, t.replies AS replies FROM forum_posts p LEFT JOIN forum_boards b ON b.id = p.board_id LEFT JOIN forum_topics t ON p.topic_id = t.topic_id " . (isset($_GET['what']) && $_GET['what'] == 'message' ? "WHERE p.message LIKE " . $search2 . "" : (isset($_GET['what']) && $_GET['what'] == 'title' ? "WHERE p.subject LIKE " . $search2 . "" : isset($_GET['what']) && $_GET['what'] == 'both' ? "WHERE p.message LIKE " . $search2 . " OR p.subject LIKE " . $search2 . "" : isset($_GET['what']) && $_GET['what'] == 'message' ? "WHERE p.message LIKE " . $search2 . "" : '')) . " " . ($_GET['forum'] != 'all' && is_numeric($_GET['forum']) ? "AND b.id = " . $_GET['forum'] . "" : '') . " " . ($_GET['order'] == 'old' ? "ORDER BY p.post_time ASC" : "ORDER BY p.post_time DESC") . " LIMIT $offset, $rowsPerPage");
   $sqlnum2 = "SELECT COUNT(p.id) AS numrows FROM forum_posts p LEFT JOIN forum_boards b ON b.id = p.board_id LEFT JOIN forum_topics t ON p.topic_id = t.topic_id " . (isset($_GET['what']) && $_GET['what'] == 'message' ? "WHERE p.message LIKE " . $search2 . "" : (isset($_GET['what']) && $_GET['what'] == 'title' ? "WHERE p.subject LIKE " . $search2 . "" : "WHERE p.message LIKE " . $search2 . " OR p.message LIKE " . $search2 . "")) . " " . ($_GET['forum'] != 'all' && is_numeric($_GET['forum']) ? "AND b.id = " . $_GET['forum'] . "" : '');
        while ($fetch = call('sql_fetch_array', $sql)) {
            if (call('visiblecheck', $user['membergroup_id'], $fetch['visible'])) {
                $results .= '<div class="bbcode_quote"><div class="bbcode_quote_head"><a href="'.$settings['site_url'].'/index.php?act=viewtopic&id=' . $fetch['topic_id'] . '" target="_blank">' . ($_GET['what'] == 'both' || $_GET['what'] == 'title' ? call('highlight_words', $_GET['search'], $fetch['subject']) : $fetch['subject']) . '</a> by ' . call('userprofilelink', $fetch['author_id']) . ' on ' . call('dateformat', $fetch['post_time']) . '<br /><span class="small-text">in <a href="'.$settings['site_url'].'/index.php?act=viewboard&id=' . $fetch['board_id'] . '" target="_blank">' . $fetch['board_name'] . '</a></span><br /></div><div class="bbcode_quote_body">' . ($_GET['what'] == 'both' || $_GET['what'] == 'message' ? call('highlight_words', $_GET['search'], call('strip_html_bbcode', substr($fetch['message'], 0, 50))) . "..." : call('strip_html_bbcode', substr($fetch['message'], 0, 50))) . '</div></div>';
            }
        }
    }
    $pagination = call('pagination', $pageNum, $rowsPerPage, (isset($sqlnum2) ? $sqlnum2 : ''), '?act=search&search=' . $_GET['search'] . '&method=' . $_GET['method'] . '&order=' . $_GET['order'] . '&what=' . $_GET['what'] . '&where=' . $_GET['where'] . (isset($_GET['forum']) ? '&forum=' . $_GET['forum'] . '' : '') . '&page=', 3);
}
?>