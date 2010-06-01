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