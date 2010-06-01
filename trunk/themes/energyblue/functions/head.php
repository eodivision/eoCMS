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

function head($title, $head='')
{
	global $settings;
	$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/style.css" type="text/css" />';
if(file_exists(IN_PATH.'/themes/'.$settings['site_theme'].'/IE7.css')) {
	$body.='
	<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/IE7.css" /><![endif]-->';
}
$body.='<link rel="stylesheet" href="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/jquery.jcarousel.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>' . $title . ' | ' . (isset($settings['site_name'])? $settings['site_name'] : '') . '</title>
<script type="text/javascript">
if (top.location != self.location) { top.location = self.location.href; }
</script>
<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.js"></script>
<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.jcarousel.pack.js"></script>
<script type="text/javascript">
function mycarousel_initCallback(carousel) {
jQuery(\'#navbar-next\').bind(\'click\', function() {
        carousel.next();
        return false;
    });

    jQuery(\'#navbar-prev\').bind(\'click\', function() {
        carousel.prev();
        return false;
    });
};

jQuery(document).ready(function() {
    jQuery(\'.jcarousel-container\').jcarousel({
        visible: 8,
		initCallback: mycarousel_initCallback
    });
});
</script>
' . $head . '
</head>';
return $body;
}
?>