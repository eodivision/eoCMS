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

function userprofilelink($userid, $colour='') {
	global $userprofilelink, $settings;
	if(!$userprofilelink) {
		$userprofilelink = array();
		$sql = call('sql_query', "SELECT u.id, u.user, m.colour FROM users u LEFT JOIN membergroups m ON u.membergroup=m.membergroup_id");
		while($p = call('sql_fetch_array', $sql)) {
			$userprofilelink[] = array('id' => $p[0],'user' => $p[1], 'colour' => $p[2]);
		}
	}
	foreach($userprofilelink as $fetch) {
		if((is_numeric($userid) && $fetch['id'] == $userid) || (!is_numeric($userid) && $fetch['user']==$userid)) {
			$profilelink = '<a href="'.$settings['site_url'].'/index.php?act=profile&amp;id=' . $fetch['id'] . '" title="View profile of ' . $fetch['user'] . '">';
			//check if colour is wanted
			if($colour != '' && !empty($fetch['colour'])) {
				//add a span class to make it that colour
				$profilelink.='<span style="color: #'.$fetch['colour'].'">';
			}
			$profilelink.= $fetch['user'];
			if($colour != '' && !empty($fetch['colour'])) {
				//close the span tag
				$profilelink.='</span>';
			}
			$profilelink.='</a>';
			return $profilelink;
		}
	}
	return false;
}
?>