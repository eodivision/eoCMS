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

   Added Language - 02/06/09 - Paul Wratt
*/
function updatesitetheme($site, $overwrites) {
	$sql = call('sql_query', "UPDATE settings SET value = '$site' WHERE variable = 'site_theme'");
	if(!empty($overwrites)) {
		$overwrites = call('sql_query', "UPDATE users SET theme = '$overwrites'");
	}
	if($sql)
		return true;
}
?>