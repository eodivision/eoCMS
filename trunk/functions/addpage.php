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
		$comment = (!isset($comment) ? 0 : 1);
		$rating = (!isset($rating) ? 0 : 1);
		$query = call('sql_query', "INSERT INTO pages (pagename, content, comments, ratings) VALUES ('$name', '$content', '$comment', '$rating')");
		if($query)
			return true;
	}
}
?>