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