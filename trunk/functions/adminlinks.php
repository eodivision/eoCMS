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

function adminlinks() {
	global $ADMIN_MENU_LANG, $authid, $settings;
	$links = array(
		'forum'        => array('title' => $ADMIN_MENU_LANG["forum"],
								'image'=> 'Forums_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=boards&amp;'.$authid,
								'class' => 'admin-forum-icon',
								'child' => array(
												//array('title' => 'Add Category',
													//  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=addforumcat&amp;'.$authid),
												array('title' => 'New',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=addboard&amp;'.$authid,
													  'icon' => 'ui-icon-plus'),
												array('title' => 'Boards List',
													  'link' => '#',
													  'class' => 'admin-boards-icon')
												)
								),
		'links'        => array('title' => $ADMIN_MENU_LANG["links"],
								'image'=> 'Links_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=links&amp;'.$authid,
								'class' => 'admin-links-icon',
								'child' => array(
												array('title' => 'New',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=addlink&amp;'.$authid,
													  'class' => 'admin-add-icon'),
												array('title' => 'Link Generator',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=linkgenerator&amp;'.$authid,
													  'class' => ''),
												array('title' => 'Current Links',
													  'link' => '#',
													  'class' => 'sortable sortabletype-links')
												),
								),
		'members'        => array('title'=> $ADMIN_MENU_LANG["users"],
								'image'=> 'Members_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=members&amp;'.$authid,
								'class' => 'admin-users-icon'),
		'news'         => array('title'=> $ADMIN_MENU_LANG["news"],
								'image'=> 'News_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;'.$authid,
								'class' => 'admin-news-icon'),
		'pages'        => array('title'=> $ADMIN_MENU_LANG["pages"],
								'image'=> 'Pages_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=pages&amp;'.$authid,
								'class' => 'admin-pages-icon'),
		'panels'       => array('title'=> $ADMIN_MENU_LANG["panels"],
								'image'=> 'Panels_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;'.$authid,
								'class' => 'admin-panels-icon'),
		'bans'         => array('title'=> $ADMIN_MENU_LANG["bans"],
								'image'=> 'Bans_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=bans&amp;'.$authid,
								'class' => 'admin-bans-icon'),
		'sitesettings' => array('title'=> 'System',
								'image'=> 'Settings_Admin.png',
								'link' => '#',
								'class' => 'admin-settings-icon',
								'child' => array(
												array('title' => 'Clear Cache',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=clearcache&amp;'.$authid,
													  'class' => ''),
												array('title' => 'Mail',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=mailsettings&amp;'.$authid,
													  'class' => ''),
												array('title' => 'Maintenance',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=maintenance&amp;'.$authid,
													  'class' => ''),
												array('title' => 'Miscellaneous',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=miscellaneous&amp;'.$authid,
													  'class' => ''),
												array('title' => 'Registration',
													  'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=registration&amp;'.$authid,
													  'class' => '')
											)
								),
		'themes'       => array('title'=> $ADMIN_MENU_LANG["themes"],
								'image'=> 'Themes_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=themes&amp;'.$authid,
								'class' => 'admin-themes-icon'),
		'plugins'      => array('title'=> $ADMIN_MENU_LANG["plugins"],
								'image'=> 'Plugins_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=plugins&amp;'.$authid,
								'class' => 'admin-plugins-icon'),
		'permissions'  => array('title' => $ADMIN_MENU_LANG["permissions"],
								'image' =>'Permissions_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;'.$authid,
								'class' => 'admin-permissions-icon'),
		'emoticons'    => array('title' => $ADMIN_MENU_LANG["emoticons"],
								'image' =>'Smiley_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=emoticons&amp;'.$authid,
								'class' => 'admin-emtoicons-icon'),
		'articles'     => array('title' => $ADMIN_MENU_LANG["articles"],
								'image' =>'Pages_Admin.png',
								'link' => $settings['site_url'].'/index.php?act=admin&amp;opt=articles&amp;'.$authid,
								'class' => 'admin-articles-icon')
	);
	return $links;
}
?>