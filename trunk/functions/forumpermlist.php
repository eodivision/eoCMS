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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

// these are the default permissions for use with boards which the system uses
// in a function file so easier to add new ones rather than hard coding it in
// every single file
function forumpermlist() {
	$perm = array(
					   'create_topics' => array('1' => '1', '2' => '2', '3' => '2', '4' => '2'),
					   'delete_own_posts' => array('1' => '1', '2' => '2', '3' => '2', '4' => '2'),
					   'delete_any_posts' => array('1' => '1', '2' => '2', '3' => '2', '4' => '2'),
					   'delete_own_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'delete_any_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'lock_own_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'lock_any_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'modify_own_posts' => array('1' => '2', '2' => '2', '3' => '2', '4' => '2'),
					   'modify_any_posts' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'move_own_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'), 
					   'move_any_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'multi-moderate' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'reply_own_topic' => array('1' => '2', '2' => '2', '3' => '2', '4' => '2'),
					   'reply_any_topic' => array('1' => '1', '2' => '2', '3' => '2', '4' => '2'),
					   'sticky_own_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'sticky_any_topic' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'track_ip' => array('1' => '1', '2' => '1', '3' => '2', '4' => '2'),
					   'view' => array('1' => '2', '2' => '2', '3' => '2', '4' => '2')
					   );
	return $perm;
}
?>