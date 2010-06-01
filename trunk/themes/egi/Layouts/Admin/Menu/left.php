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

if (!(defined("IN_ECMS"))) die("Hacking Attempt...");

/*echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top: 1%;">
    <tr>
      <td valign="top">
      <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Forums_Admin.png" class="adminicon" alt="Forum" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=forum&amp;'.$authid.'" title="Manage the site forum">Forum</a></div>
      <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Links_Admin.png" class="adminicon" alt="Links" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=links&amp;'.$authid.'" title="Manage links">Links</a></div>
            <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Members_Admin.png" class="adminicon" alt="Members" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=users&amp;'.$authid.'" title="Manage users">Members</a></div>
            <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/News_Admin.png" class="adminicon" alt="News" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;'.$authid.'" title="Manage news">News</a></div>
            <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Pages_Admin.png" class="adminicon" alt="Pages" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=pages&amp;'.$authid.'" title="Manage custom pages">Pages</a></div>
            <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Panels_Admin.png" class="adminicon" alt="Panels" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;'.$authid.'" title="Manage panels">Panels</a></div>
            <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Bans_Admin.png" class="adminicon" alt="Bans" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=bans&amp;'.$authid.'" title="Manage bans">Bans</a></div>
                  <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Settings_Admin.png" class="adminicon" alt="Settings" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=sitesettings&amp;'.$authid.'" title="Site Settings">Site Settings</a></div>
      <p align="center"><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;'.$authid.'" title="Themes">Theme Settings</a></p>
    <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Plugins_Admin.png" class="adminicon" alt="Plugins" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=plugins&amp;'.$authid.'" title="Plugins">Plugins</a></div>
    <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Permissions_Admin.png" class="adminicon" alt="Permissions" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;'.$authid.'" title="permissions">Permissions</a></div>
            <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Smiley_Admin.png" class="adminicon" alt="Emoticons" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=emoticons&amp;'.$authid.'" title="emoticons">Emoticons</a></div>
    <div align="center" class="adminbutton">
      <img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/Pages_Admin.png" class="adminicon" alt="Articles" /><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=articles&amp;'.$authid.'" title="articles">Articles</a></div>
      </td>
      <td width="100%" valign="top" style="padding-left: 1%;">';
          echo '<div class="titlebg" align="center">' . $title . '</div>' . $update, $error, '<br />' . stripslashes($body) . '</td>
    </tr>
  </table>';*/
$menu = '
<table border="0" cellpadding="0" cellspacing="0" style="padding-top:15px;">
  <tr>
    <td valign="top">';
$links = call('adminlinks');
foreach($links as $titles => $href) {
	foreach($links['images'] as $name => $image) {
		if($titles == $name) {
			$images = '<img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/' . $image . '" class="adminicon" alt="' . $name . '" border="0" />';
		}
	}
	if($titles!='images') {
		$menu .= '
	<div align="center" class="adminbutton">' . $images . '<a href="'.$settings['site_url'].'/index.php?act=admin&opt=' . $href . '&' . $authid . '" title=" ' . $titles . ' ">' . $titles . '</a></div>';
	}
}
$menu .= '</td>
    <td width="100%" valign="top" style="padding-left: 1%;">' . theme('title', $title) . theme('start_content') .'
	  ' . $update. $error. '<br />
	  ' . stripslashes($body) . theme('end_content') . '</td>
  </tr>
</table>';
?>