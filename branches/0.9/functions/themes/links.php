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

function links()
{
	global $user, $authid, $settings;
	$body = '
<div class="navbar">
  <ul>';
	$headerlinkquery = call('sql_query', "SELECT * FROM menu ORDER BY item_order ASC", 'cache');
	foreach ($headerlinkquery as $headerlinkrow) {
	    if (call('visiblecheck', $user['membergroup_id'], $headerlinkrow['rank'])) {
		  if (empty($headerlinkrow['window']) || $headerlinkrow['window'] != 'popup') {
			$body .= '
	<li><a href="' . $headerlinkrow['link'] . ($headerlinkrow['authid'] == '1' ? '&amp;' . $authid : '') . '"' . ($headerlinkrow['window'] == 'new' ? ' target="_blank"' : '') . '>' . $headerlinkrow['name'] . '</a></li>';
		  } elseif ($headerlinkrow['window'] == 'popup') {
			$body .= '<li><a href="javascript:;" onclick="window.open(\'' . $headerlinkrow['link'] . ($headerlinkrow['authid'] == '1' ? '&amp;' . $authid : '') . '\',\'\',\'width=' . $headerlinkrow['width'] . ',height=' . $headerlinkrow['height'] . '\')">' . $headerlinkrow['name'] . '</a></li>
	  ';
		  }
	    }
	}
	$body .= '
	    </ul>
	  </div>';
	return $body;
}
?>