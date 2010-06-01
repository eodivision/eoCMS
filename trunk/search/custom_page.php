<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
  $buttons[] = '<input type="radio" name="where" id="custom pages" value="custom_page"' . (isset($_GET['where']) && $_GET['where'] == 'custom_page' ? ' checked="checked"' : '') . ' /><label for="custom pages">Custom Pages</label>';
  if (isset($_GET['where']) && $_GET['where'] == 'custom_page') {
      $sql = sql_query("SELECT * FROM pages " . (isset($_GET['what']) && $_GET['what'] == 'message' ? "WHERE content LIKE " . $search2 . "" : (isset($_GET['what']) && $_GET['what'] == 'title' ? "WHERE pagename LIKE " . $search2 . "" : "WHERE pagename LIKE " . $search2 . " OR content LIKE " . $search2 . "")) . " LIMIT $offset, $rowsPerPage");
      $sqlnum2 = "SELECT COUNT(id) AS numrows FROM pages " . (isset($_GET['what']) && $_GET['what'] == 'message' ? "WHERE content LIKE " . $search2 . "" : (isset($_GET['what']) && $_GET['what'] == 'title' ? "WHERE pagename LIKE " . $search2 . "" : "WHERE pagename LIKE " . $search2 . " OR content LIKE " . $search2 . ""));
      while ($fetch = call('sql_fetch_array', $sql)) {
          $results .= '<div class="bbcode_quote"><div class="bbcode_quote_head"><a href="'.$settings['site_url'].'/index.php?act=page&id=' . $fetch['id'] . '" target="_blank">' . ($_GET['what'] == 'both' || $_GET['what'] == 'title' ? call('highlight_words', $_GET['search'], $fetch['pagename']) : $fetch['pagename']) . '</a><br /></div><div class="bbcode_quote_body">' . ($_GET['what'] == 'both' || $_GET['what'] == 'message' ? call('highlight_words', $_GET['search'], substr(call('strip_html_bbcode', $fetch['content']), 0, 50)) . "..." : substr(call('strip_html_bbcode', $fetch['content']), 0, 50)) . "..." . '</div></div>';
      }
  }
  $pagination = call('pagination', $pageNum, $rowsPerPage, $sqlnum2, '?act=search&search='.$_GET['search'].'&method='.$_GET['method'].'&order='.$_GET['order'].'&what='.$_GET['what'].'&where='.$_GET['where'].'&page=', 3);
?>