<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

   Clear Cache written by FWishbringer and confuser
*/

function clearcache()
{
   global $user, $FUNCTIONS_LANG, $error, $error_die;
   if(!$user['admin_panel'])  {
      $error_die[] = $FUNCTIONS_LANG["e_permissions"];
      return false;
   }

   // Destroy cached tables.
   $file = './'.CACHE.'/tables.php';
   $OUTPUT = "<?php die(); ?>\n";
   file_put_contents($file, $OUTPUT);

   $dir = opendir(CACHE.'/');
      while (($file=readdir($dir))!==false) {
         if(strlen($file) == 36 && $file != 'index.php' && $file != 'tables.php')
            unlink('./'.CACHE.'/' . $file);
      }
    closedir($dir);
}
?>