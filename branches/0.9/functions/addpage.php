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
function addpage($name, $content, $comment, $rating) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}

	if(empty($content)) {
		$error[] = 'You must specify content for the page';
		return false;
	}
	if(empty($name)) {
		$error[] = 'You must specify a name for the page';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "INSERT INTO pages (pagename, content, comments, ratings) VALUES ('$name', '$content', '$comment', '$rating')");
		if($query)
			return true;
	}
}
?>