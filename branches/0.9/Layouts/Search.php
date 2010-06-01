<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

    Language added - 04/06/09 - Paul Wratt
*/
if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

  $title = $SEARCH_LANG["title"];
  $pageNum = (isset($_GET['page']) ? $_GET['page'] : 1);
  // how many rows to show per page
  $rowsPerPage = 20;
  // counting the offset
  $offset = ($pageNum - 1) * $rowsPerPage;
  $body = theme('title', $SEARCH_LANG["theme_title"]) . theme('start_content');
  $body .= '<form method="get" action=""><table>
<tr><td>'.$SEARCH_LANG["search_for"].' <input type="text" name="search" size="40" onFocus="this.value=''" ' . (isset($_GET['search']) ? 'value="' . $_GET['search'] . '"' : '') . '/></td><td align="right"><select name="method"><option value="all"' . (isset($_GET['method']) && $_GET['method'] == 'all' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_match_all"].'</option><option value="any"' . (isset($_GET['method']) && $_GET['method'] == 'any' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_match_any"].'</option></select></td></tr><tr><td>';
  if (!empty($_GET['search']) & isset($_GET['where'])) {
      $search = explode(" ", $_GET['search']);
      $search2 = '';
      for ($i = 0; $i < count($search); $i++) {
          $search2 .= (isset($_GET['method']) && $_GET['method'] == 'all' && $i != 0 ? 'AND ' : (isset($_GET['method']) && $_GET['method'] == 'any' && $i != 0 ? 'OR ' : '')) . '"%' . $search[$i] . '%" ';
      }
  }
  //set the buttons as an array
  $buttons = array();
  //lets set the resutls string
  $results = '';
  //open the search directory and include all the search files
  $dir = opendir('search');
  while (false !== ($read = readdir($dir))) {
      if ($read != "." && $read != ".." && $read != ".svn") {
          include('search/' . $read);
      }
  }
  closedir($dir);
  $body .= '<table>';
  //right lets start by displaying all the buttons
  foreach ($buttons as $button) {
      $body .= '<tr><td>' . $button . '</td></tr>';
  }
  //output all the search optuons
  $body .= '</table></td><td><table><tr><td align="right">'.$SEARCH_LANG["order_by"].': <select name="order"><option value="old"' . (isset($_GET['order']) && $_GET['order'] == 'old' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_old"].'</option><option value="new"' . (isset($_GET['order']) && $_GET['order'] == 'new' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_new"].'</option></select></td></tr>';
  $body .= '<tr><td align="right">'.$SEARCH_LANG["search"].' <select name="what"><option value="both"' . (isset($_GET['what']) && $_GET['what'] == 'both' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_both"].'</option><option value="message"' . (isset($_GET['what']) && $_GET['what'] == 'message' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_message"].'</option><option value="title"' . (isset($_GET['what']) && $_GET['what'] == 'title' ? ' selected="selected"' : '') . '>'.$SEARCH_LANG["o_title"].'</option></select></td></tr></table></td></tr>
<tr><td align="center" colspan="2"><input type="hidden" name="act" value="search" /><input type="submit" value="'.$SEARCH_LANG["btn_search"].'" /></td></tr></table></form>';
  $body .= theme('end_content');
  //check if there is a search
  if (!empty($_GET['search']) & isset($_GET['where'])) {
  //theres a search so lets show the table
      $body .= theme('title', $SEARCH_LANG["theme_title_results"]) . theme('start_content');
      $sqlnum = call('sql_num_rows', $sql);
      if ($sqlnum == 0) {
     //no results, hate it when that happens
          $body .= $SEARCH_LANG["body_content"].'<br />';
      } else {
     //there are results lets show them
          $body .= $sqlnum . ' '.$SEARCH_LANG["results_found"].': ' . $_GET['search'] . '<br />'.$results;
      }
     //check to make sure there are results
      if ($sqlnum != 0) {
     //display the pages
          $body .= (isset($_GET['search']) ? $pagination : '');
      }
  }
  $body .= theme('end_content');
?>