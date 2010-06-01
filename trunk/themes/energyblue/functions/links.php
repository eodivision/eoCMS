<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons 
	Attribution-Share Alike 3.0 United States License. 
	To view a copy of this license, visit 
	http://creativecommons.org/licenses/by-sa/3.0/us/
	or send a letter to Creative Commons, 171 Second Street, 
	Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/

function links()
{
	global $user, $authid, $settings;
	$body = '
<div class="navbar">
  <ul class="jcarousel-container">';
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