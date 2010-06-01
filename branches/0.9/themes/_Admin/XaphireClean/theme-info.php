<?php
/* 
    Xaphire (linux icon set) based admin center
*/

$theme_name = 'Xaphire Clean';
$theme_author = 'Paul Wratt';
$theme_site = 'http://paulwratt.110mb.com/eocms/xaphire/';
$theme_email = 'paul.wratt@gmail.com';
$theme_version = '1.00a';
$theme_preview = 'preview.png';
$theme_default = 'center.php';
$theme_description = 'The first majorly different admin menu layout, based on the linux icon set "Xaphire". It uses 128x128 sized icons, has icon fading, groups menu items into associated groups, and gives a "quick edit" set by default. It is designed as a "Admin Center" more than an "Admin Menu", and as such, also contains a "start here" group for new installations and new admins, and comes with no panels, making it "Xaphire Clean".';
$theme_options = array(
	'icons'           => array('icons/128x128','!choose'),
	'size'            => array(128,64,32,16),
	'style'           => array('default','left','right','sub'),
	'panels'          => array('none','center_lower','from_settings'),
	'sub_menu_size'   => array(32,28,24,20,16,8),
	'sub_menu_style'  => array('top-left','left','bottom-left','top-right','right','bottom-right','top-center','bottom-center','right-click'),
	'sub_menu_panels' => array('none','center_lower','from_settings')
);
?>