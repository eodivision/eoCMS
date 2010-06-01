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
function getforumpermission($boardid = '', $topicid = '') {
	global $user;
	$user['moderator'] = false; // set them not to a moderator by default
	if(!empty($boardid) && !empty($topicid))
		$check = "bp.board_id = '$boardid' AND t.topic_id = '$topicid'";
	elseif(empty($boardid) && !empty($topicid))
		$check = "t.topic_id = '$topicid'";
	elseif(!empty($boardid) && empty($topicid))
		$check = "bp.board_id = '$boardid'";
	$permissionquery = call('sql_query', "SELECT bp.variable AS variable, bp.value AS value, t.topic_id AS topic_id, t.topic_author_id AS author_id, t.topic_author AS author FROM membergroups m LEFT JOIN board_permissions bp ON bp.membergroup_id = m.membergroup_id LEFT JOIN forum_topics t ON t.board_id = bp.board_id WHERE $check AND m.membergroup_id = '".$user['membergroup_id']."'");
	$perms = call('forumpermlist');
	// go through the permissions and tell them what they can and cant do :P
	while($perm = call('sql_fetch_array', $permissionquery)) {
		if(is_array($perms['types'][$perm['variable']])) { // check to see if it splits up into any, own and none
			// looks like it does!
			if($perm['value'] == 3) // they can do anything!
				$user[$perm['variable']] = true;
			elseif($perm['value'] == 1) // they cant do anything, PWNED XD
				$user[$perm['variable']] = false;
			elseif(!$user['guest'] && $perm['author_id'] == $user['id'] && $perm['value'] == 2) // they can only change their own stuff
				$user[$perm['variable']] = true; // this wont work for posts so some permissions will be overwrote in other functions like viewtopic()
			else
				$user[$perm['variable']] = false;
		} else {
			// aww it doesnt :P
			$user[$perm['variable']] = ($perm['value'] == 1 ? false : true); // 1 is a no and 2 is a yes
		}
	}
		/*$permissionquery = call('sql_query', "SELECT bp.variable AS variable, bp.value AS value FROM membergroups m LEFT JOIN board_permissions bp ON bp.membergroup_id = m.membergroup_id WHERE bp.board_id = '$boardid' AND m.membergroup_id = '".$user['membergroup_id']."'", 'cache');
		foreach($permissionquery as $perms) {
			$permissions[$perms['variable']] = $perms['value'];
		}
		if(isset($permissions) && count($permissions) > 0)
			// add the permissions into the $user
			$user = array_merge($user, $permissions);
		// set the moderators stuff now, to prevent any errors later even if board empty
		// check if the user is a registered moderator
		$mod = call('sql_num_rows', call('sql_query', "SELECT * FROM forum_moderators WHERE user_id = '".$user['id']."' AND board_id = '$boardid'"));
		if($mod == 0) {
			// looks like they are for this board so give them moderator permissions!
			$perms = call('forumpermlist');
			foreach($perms['types'] as $perm => $value) {
				$user[$perm] = $perms['default'][$perm][3]; // set it to the Global Moderator group
			}
		} elseif(isset($user['multi-moderate']) && $user['multi-moderate'])
			$user['moderator'] = true;
		return $user;*/
}
?>