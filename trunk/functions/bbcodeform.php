<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
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