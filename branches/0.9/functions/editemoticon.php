<?php
/*  eoCMS � 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
function editemoticon($code, $image, $text, $id) {
	global $user, $error, $error_die;   
	if(!$user['admin_panel'])  {
		$error_die[] = 'You do not have permission to do this'; //No one should be able to actually use this function except an admin, its there just to be safe ;)
		return false;
	}
	if(empty($code)) {
		$error[] = 'You must specify the code';
		return false;
	}
	if(empty($image)) {
		$error[] = 'You must specify an image';
		return false;
	}
	if(empty($text)) {
		$error[] = 'You must specify text';
		return false;
	}
	if(!errors()) {
		$query = call('sql_query', "UPDATE emoticons SET code='$code', image='$image', alt='$text' WHERE id='$id'");
		if($query)
			return true;
	}
}
?>