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

    QuickSearch Panel - 04/06/09 - Paul Wratt
*/

# for language and css includes
$panel['prefix']   = 'qa';
# title = $PANELS_LANG['qa_title']

# for $head, contains includes
$panel['include'] = <<<HTML
<style type="text/css">
.sidebar {
	padding-top: 5px;
}
.panel {
	margin: 0 1em 1em 0;
}
.panel-header .ui-icon {
	float: right;
}
.ui-sortable-placeholder {
	border: 1px dotted black;
	visibility: visible !important;
	height: 50px !important;
}
.ui-sortable-placeholder * {
	visibility: hidden;
}
</style>
<script type="text/javascript" src="panels/quick_admin/jquery.ui.js"></script>
<script type="text/javascript" src="panels/quick_admin/quickadmin.js"></script>
HTML;

# example HTML of whole panel
$panel['example'] = <<<HTML
<div id="managepanels">
<a style="cursor: pointer" onclick="panels()">Move Panels</a><br />
<a style="cursor: pointer" onclick="location.href="?act=admin&amp;&opt=users"">Permissions</a><br />
</div>
HTML;

# actual HTML of panel
$panel['body'] = <<<HTML
<div id="managepanels">{link}</div>
HTML;

# actual HTML of panel
$panel['item']['link'] = <<<HTML
<a style="cursor: pointer" onclick="{onclick}">{text}</a><br />
HTML;

# panel options
$panel['options'] = array(
	'_defaults'   => array(
		'onclick' => 'panels();',
		'text'    => 'qa_move_panels'),
	'_requires'   => array(
		'item'    => 'link',
		'replace' => array('onclick','text')),
	'add_links'   => array('!choose-link'),
	'delete_link' => array('!delete-link')
);

?>