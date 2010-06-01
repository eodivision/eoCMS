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
function addarticle($subject, $summary, $full_article, $cat, $rating, $comment) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($subject)) {
		$error[] = 'You must specify a subject';
		return false;
	}
	if(empty($summary)) {
		$error[] = 'You must specify a summary';
		return false;
	}
	if(!isset($comment))
		$comment = 0;
	else
		$comment = 1;
	if(!isset($rating))
		$rating = 0;
	else
		$rating = 1;
	if(empty($error) && empty($error_die)) {
		$query = call('sql_query', "INSERT INTO articles (subject, summary, full_article, time_created, author_id, name_author, cat, views, ratings, comments) VALUES ('$subject', '$summary', '$full_article', '" . time() . "', '".$user['id']."', '".$user['user']."', '$cat', '0', '$rating', '$comment')");
		if($query)
			return true;
	}
}
?>