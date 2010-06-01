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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$theme['title'] = 'Link Generator';
$theme['head'] = '
<script type="text/javascript">
	$(function() {
	var gen = $("#generatortype");
		gen.change(function() {
			if(gen.val() == "pages") {
				$("#pages-list").slideDown();
				$("#pages-list li").click(function() {
					$("#link").val($(this).attr("link"));
				});
			}
		});
	});
</script>';
$pages = call('sql_query', "SELECT * FROM pages", 'cache');
$theme['body'] = theme('start_content').'
Type: <select id="generatortype">
		<option value=""></option>
		<option value="pages">Pages</option>
	</select>
<div id="generator-pages">
<input type="text" id="link" />
	<ul id="pages-list" style="max-height: 100px; overflow: auto; display: none;">';
foreach($pages as $page) {
	$theme['body'] .= '<li link="'.$settings['site_url'].'/index.php?act=pages&id='.$page['id'].'">'.$page['pagename'].'</li>';
}
$theme['body'] .= '
	</ul>
	<ul>
		
	</ul>
</div>
'.theme('end_content');
?>