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
function seodescription($words) {
global $settings;
	$words = html_entity_decode(htmlspecialchars_decode($words, ENT_QUOTES), ENT_QUOTES);
	$words = call('strip_html_bbcode', $words);
	$words = preg_replace('/<br \/>/', ' ', $words); //remove line breaks, kinda pointless if we aint outputting them
	$words = substr($words, 0, 150);
	$words = str_replace(array("<", ">", "\\", "\"", "=", "\n"), " ", $words);
		$meta = '<meta name="description" content="' . $words . '" />';
		return $meta;
}
?>