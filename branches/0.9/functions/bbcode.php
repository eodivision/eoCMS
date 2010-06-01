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
function bbcode($text, $noemoticons='') {
	global $settings, $bbcode_cache;
	$bbcode = array('/\[b\](.*?)\[\/b\]/ms',	
					'/\[i\](.*?)\[\/i\]/ms',
					'/\[u\](.*?)\[\/u\]/ms',
					'/\[s\](.*?)\[\/s\]/ms',
					'/\[img\](.*?)\[\/img\]/ms',
					'/\[email\](.*?)\[\/email\]/msie',
					'/\[size=([1-2]?[0-9])\](.*?)\[\/size\]/ms',
					'/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
					'/\[list](.*?)\[\/list\]/ms',
					'/\[list=1](.*?)\[\/list\]/ms',
					'/\[\*\]\s?(.*?)\[\/\*\]/ms',
					'/\[hr\]/ms',
					'/\[table](.*?)\[\/table\]/ms',
					'/\[tr](.*?)\[\/tr\]/ms',
					'/\[td](.*?)\[\/td\]/ms',
					'/\[dot\]/ms',
					'/\[left\](.*?)\[\/left\]/ms',
					'/\[center\](.*?)\[\/center\]/ms',
					'/\[right\](.*?)\[\/right\]/ms');

	$html = array('<strong>\1</strong>',
				 '<em>\1</em>',
				 '<u>\1</u>',
				 '<del>\1</del>',
				 '<img src="\1" alt="\1" />',
				 "''. call('hide_email', '\\1') .''",
				 '<span style="font-size: \1pt">\2</span>',
				 '<span style="color: \1">\2</span>',
				 '<ul class="bbcode_list">\1</ul>',
				 '<ol class="bbcode_list">\1</ol>',
				 '<li>\1</li>',
				 '<hr>',
				 '<table class="bbcode_table">\1</table>',
				 '<tr class="bbcode_tr">\1</tr>',
				 '<td class="bbcode_td">\1</td>',
				 '&bull;',
				 '<div align="left">\1</div>',
				 '<div align="center">\1</div>',
				 '<div align="right">\1</div>');
	//check if the emoticon has been cached within the function so it doesnt need to be constantly called
	if(!isset($bbcode_cache)) {
		//now to query the emoticons table and put it in an array and cache the emoticons so we only need to query the database once in a blue moon
		$bbcode_cache = call('sql_query', "SELECT * FROM emoticons", 'cache');
	}
	if($noemoticons!='on') {
		foreach($bbcode_cache as $emoticon) {
			$text = str_replace(' '.$emoticon['code'], ' <img src="'.$settings['site_url'].'/images/emoticons/'.$emoticon['image'].'" alt="'.$emoticon['alt'].'"/>', $text);
		}
	}
			 //function to unparse the smilies, probably a better way to do stop smilies parsing inside [code] tags etc
	if (!function_exists('unparsesmileys')) {
		function unparsesmileys($code) {
			global $bbcode_cache, $settings;
			foreach($bbcode_cache as $emoticon) {
				$code = str_replace('<img src="'.$settings['site_url'].'/images/emoticons/'.$emoticon['image'].'" alt="'.$emoticon['alt'].'"/>', $emoticon['code'], $code);
			}
			return $code;
		}
	}
	//go through the quotes to allow for double quoting
	$quotecount = substr_count($text, '[quote');
	for ($i=0;$i < $quotecount;$i++) {
		if(strstr($text, '[quote]') !== false) {
			$bbcode[] = '/\[quote](.*?)\[\/quote\]/ms';
			$html[] = '<div class="bbcode_quote"><div class="bbcode_quote_head">Quote:</div><div class="bbcode_quote_body">\1</div></div>';
		} 
		if(strstr($text, '[quote name=') !== false) {
			$bbcode[] = '/\[quote name\="?(.*?)"?\ link\="?(.*?)"?\ date\="?(.*?)"?\](.*?)\[\/quote\]/msie';
			$html[] = "'<div class=\"bbcode_quote\"><div class=\"bbcode_quote_head\"><a href=\"".$settings['site_url']."/index.php?act=viewtopic&amp;\\2\" target=\"_blank\">\\1 wrote on ' . trim(call('dateformat', '\\3')) . ':</a></div><div class=\"bbcode_quote_body\">'.stripslashes('\\4').'</div></div>'";
		}
	}
	//function to stop bbcode from parseing inside the [code] tag
	if (!function_exists('escapecode')) {
		function escapecode($s) {
			global $text;
			$code = $s[1];
			$code = str_replace("[", "&#91;", $code);
			$code = str_replace("]", "&#93;", $code);
			$code = unparsesmileys($code);
			return '<div class="bbcode_code"><div class="bbcode_code_head">Code:</div><div class="bbcode_code_body">'.$code.'</div></div>';
		}	
	}
	//parse the [code] tag so it does not parse other bbcode tags within it
	$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escapecode", $text);
	//function to stop javascript from being used inside [utl] tag
	if (!function_exists('javascriptcheck')) {
		function javascriptcheck($s) {
			global $bbcode_cache, $settings;
			$code = $s[1];
			//stupid hackers, why cant they go away?!
			$code = str_ireplace('javascript:', "nojavascriptallowed:", $code);
			$code = str_ireplace('vbscript:', "novbscriptallowed:", $code);
			$code = str_ireplace('script:', "noscriptallowed", $code);
			$code = str_ireplace('expression(', "noexpressionallowed:", $code);
			$code = str_ireplace('behaviour(', "nobehaviourallowed:", $code);
			if(!isset($s[2]))
				$s[2] = $s[1];
			$code = unparsesmileys($code);
			return '<a href="'.$code.'" target="_blank">'.$s[2].'</a>';
		}
	}
	//parse the [url] tag so it does not parse javascript:
	$text = preg_replace_callback('/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms', "javascriptcheck", $text);
	//format spaces and tabs
	$text = str_replace("  ", "&nbsp; ", $text);
	$text = str_replace("  ", " &nbsp;", $text);
	$text = str_replace("\t", "&nbsp; &nbsp;", $text);
	$text = preg_replace_callback('/\[url\](.*?)\[\/url\]/ms', "javascriptcheck", $text);
	$text = preg_replace($bbcode, $html, $text);
	$text = nl2br($text);
	return $text;
}
?>