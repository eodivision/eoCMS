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
function editnews($subject, $news, $extended, $starttime, $endtime, $visible, $cat, $rating, $comment, $id) {
	global $user, $error, $error_die;   
	if(empty($visible)) {
		$error[] = 'You must state who this board is visible to';
		return false;
	} else {
		foreach($visible as $key => $value) {
			$visible .= $value.',';
		}
		$visible = str_replace('Array', '', $visible);
		$count = strlen($visible);
		$visible = substr($visible, 0, $count - 1);
	}
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($subject)) {
		$error[] = 'You must specify a subject';
		return false;
	}
	if(empty($news)) {
		$error[] = 'You must specify content for your news';
		return false;
	}
	$comment = (!isset($comment) ? 0 : 1);
	$rating = (!isset($rating) ? 0 : 1);
	if(!errors()) {
		$query = call('sql_query', "UPDATE news SET subject = '$subject', content = '$news', extended = '$extended', start_time = '$starttime', end_time = '$endtime', visibility = '$visible', cat = '$cat', ratings = '$rating', comments = '$comment' WHERE id = '$id'");
		if($query)
			return true;
	}
}
?>