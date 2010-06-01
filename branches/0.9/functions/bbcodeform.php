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
function bbcodeform($ta) {
	global $head, $settings;
	$head.='<script type="text/javascript" src="' . $settings['site_url'] . '/js/bbcode.js"></script>
			<script type="text/javascript" src="' . $settings['site_url'] . '/js/markitup/jquery.markitup.js"></script>
			<script type="text/javascript" src="' . $settings['site_url'] . '/js/markitup/sets/default/set.js"></script>
			<script type="text/javascript" >
				$(document).ready(function() {
					$("#'.$ta.'").markItUp(mySettings);
				});
</script>';
	$display='<table><tr><td>Smileys:</td><td>';
	$sql = call('sql_query', "SELECT * FROM emoticons");
	while($fetch = call('sql_fetch_array', $sql)) {
		$display.='<a onclick="bbcode_ins(\''.$ta.'\', \''.$fetch['code'].'\')" style="cursor: pointer;"><img src="'.$settings['site_url'].'/images/emoticons/'.$fetch['image'].'" alt="'.$fetch['alt'].'" title="'.$fetch['alt'].'"/></a> ';
	}
	$display.='</td></tr></table>';
	return $display;
}
?>