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
function editarticle($subject, $summary, $full_article, $cat, $rating, $comment, $id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel']) {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($subject)) {
		$error[] = 'You must specify a subject';
		return false;
	}
	if(empty($summary)) {
		$error[] = 'You must specify a summary for your article';
		return false;
	}
	$comment = (!isset($comment) ? 0 : 1);
	$rating = (!isset($rating) ? 0 : 1);
	if(!errors()) {
		$query = call('sql_query', "UPDATE articles SET subject = '$subject', summary = '$summary', full_article = '$full_article', cat = '$cat', ratings = '$rating', comments = '$comment' WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>