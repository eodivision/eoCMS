<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
function seokeyword($words) {
global $settings;
    $remove = array("a", "i", "who", "what", "when", "why", "where", "how", "for", "from", "as", "an", "by", "at", "with", "that", "there", "their", "don't", "we", "you", "he", "she", "it", "hi", "but", "your", "lol", "will", "was", "and", "&", "won't", "do", "this", "rofl", "to", "is", "they", "the", "if", "can", "will", "noob", "lmao", "brb", "@", "email", "link", "admin", "l33t", "not", "enter", "without", "they're", "it's", "am", "pm", "or", "in", "which", "be", "of");
		  $words = html_entity_decode(htmlspecialchars_decode($words, ENT_QUOTES));
	$words = preg_replace('/<a(.*)<\/a>/', '', $words); //remove urls, we dont want them in our meta tags do we :P
	$words = trim(strip_tags($words)); //remove html tags
	$words = preg_replace('/<br \/>/', ' ', $words); //remove line breaks, kinda pointless if we aint outputting them
	$words = strtolower($words); //make sure they are all lowercase
	$words = preg_split('/\s*[\s+\.|\?|,|(|)|\t|\r|\-+|\"|=|;|&#0215;|\xa0|\$|\/|:|{|}]\s*/i', $words);
	$words = array_count_values($words);
	foreach($remove as $bye) {
	unset($words[$bye]);
	}
	arsort($words, SORT_NUMERIC);
	    $words = implode(' ', array_keys($words));
	    $words = preg_replace('/[0-9]/', ' ', $words); //change numbers into spaces
	    $words = preg_replace('/\s\s+/', ' ', $words); //replace multiple spaces with single spaces
		$words = call('limit_text', $words, strlen($words), 10);
	    $words = str_replace(" ", ", ", trim($words)); //replace spaces with commas
		$meta = '<meta name="keywords" content="' . $words . '" />';
		return $meta;
}
?>