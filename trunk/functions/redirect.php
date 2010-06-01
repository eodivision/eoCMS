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
function redirect($location, $type = 'header') {
	global $head;
	//remove any &amp; in the url to prevent any problems
	$location = str_replace('&amp;', '&', $location);
	//unset any $_POSTs to prevent reposting of data
	unset($_POST);
	switch($type) {
		case 'header';
			header("Location: $location");
			//kill the script from running and output a link for browsers which enable turning off header redirects *cough Opera cough* :P
			exit('<a href="'.$location.'">If you are not redirected click here</a>');
		break;
		case 'js';
			$head .= '<script type="text/javascript">document.location.href="'.$location.'"</script>';
		break;
		case 'meta';
			$head .= '<meta http-equiv="refresh" content="0;URL='.$location.'">';
		break;
	}
}
?>