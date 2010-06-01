<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
function strip_html_bbcode($text) {
	$text = html_entity_decode(htmlspecialchars_decode($text, ENT_QUOTES), ENT_QUOTES);
	$text = preg_replace("[\[(.*?)\]]", "", $text);
	$text = preg_replace("<\<(.*?)\>>", "", $text);
	return $text;
}
?>