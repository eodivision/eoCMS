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
  function createcache($query, $expire = '')
  {
      $file = md5($query) . '.php';
      $location = './'.CACHE.'/';
      if (!empty($expire) && is_numeric($expire)) {
          if (file_exists($location . $file) && filemtime($location . $file) > (time() - $expire)) {
              $destroy = false;
          }
      } elseif (file_exists($location . $file)) {
          $destroy = false;
      }
      if (!isset($destroy) || $destroy != false) {
	  	  $data = array();
          $sql = call('sql_query', $query);
		  while($fetch = call('sql_fetch_array', $sql)) {
		  $data[] = $fetch;
		  }
		  $OUTPUT = "<?php die(); ?>\n".serialize($data);
    	  $fp = fopen($location . $file,"w");
          fputs($fp, $OUTPUT);
          fclose($fp); 
      }
  }
?>