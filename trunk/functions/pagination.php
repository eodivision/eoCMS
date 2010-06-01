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
function pagination($pageNum, $rowsPerPage, $query, $pagetype, $range, $visibility='') {
	global $user, $settings;
	//add the site url to make link absolute instead of relative
	$pagetype = $settings['site_url'].'/index.php'.$pagetype;
	if(strpos($query, 'SELECT') !== false) {
		$query = call('sql_query', $query);
		if(!empty($visibility)) {
			$total = 0;
			while($fetch = call('sql_fetch_array', $query))
				if(call('visiblecheck', $user['membergroup_id'], $fetch[''.$visibility.''])) {
					$total++;
			}
		} else {
			$fetch = call('sql_fetch_array',$query);
			$total = $fetch['numrows'];
		}
	} else
		$total = $query;
	$pagecount = ceil($total / $rowsPerPage);
	if ($pagecount <= 1)
		return 'Page 1 Of 1';
	$back = $pageNum - $rowsPerPage;
	$next = $pageNum + $rowsPerPage;
	$currentpage = (isset($_GET['page']) ? $_GET['page'] : 1);
	if($pagecount == 0)
		$pagecount = 1;
	$pagination = "Page ".$currentpage." Of ".$pagecount.": ";
	if($back <= 0) {
		if($currentpage > ($range + 1))
			$pagination .= "<a href='".$pagetype."1'>1</a>...";
	}
	$first = max($currentpage - $range, 1);
	$last = min($currentpage + $range, $pagecount);
	if ($range == 0) {
		$first = 1;
		$last = $pagecount;
	}
	for ($i = $first; $i <= $last; $i++) {
		if ($i == $currentpage)
			$pagination .= "<span><strong> [".$i."] </strong></span>";
		else
			$pagination .= "<a href='".$pagetype . $i."'> ".$i." </a>";
	}
	if ($next < $total) {
		if ($currentpage < ($pagecount - $range))
			$pagination .= "...<a href='".$pagetype.$pagecount."'>".$pagecount."</a>";
	}
	return $pagination;
}
?>