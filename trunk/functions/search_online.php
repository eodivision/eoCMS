<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
function search_online($user_id) {
    global $settings, $search_online_cache;
	if(!isset($search_online_cache)) {
	$search_online_cache = array();
      //queries the database to see if any data exists with this user
      $sql = call('sql_query', "SELECT user_id, time_online FROM user_online WHERE user_id!=0");
	  while($p = call('sql_fetch_array', $sql)) {
	  $search_online_cache[] = array(
	  'user_id' => $p['user_id'],
	  'time' => $p['time_online']);
	  }
	  }
	  foreach($search_online_cache as $cache) {
	  	if($cache['time'] < (time() - 600)) {
		unset($cache);
		}
	}
	//gah what a shame, you can never get hold of people when u need to :(
	$image = '<a href="'.$settings['site_url'].'/index.php?act=sendpm&amp;userid='.$user_id.'" title="Send a Message"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/offline.png" alt="Offline"/></a> <span class="small-text">Offline</span>';
	  foreach($search_online_cache as $cache) {
      if ($user_id == $cache['user_id']) {
          //Yay online :D
          $image = '<a href="'.$settings['site_url'].'/index.php?act=sendpm&amp;userid='.$user_id.'" title="Send a Message"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/online.png" alt="Online"/></a> <span class="small-text">Online</span>';
      }
	  }
return $image;	  
}
?>