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

function NEWadminlinks() {
	global $ADMIN_MENU_LANG, $a_action;
# should be combined with a_action array in Admin
# image names should be reversed and use "key" instead of "title"
#   that way the menu could be "eval'd" from the action array "key"
#   allowing only one place for menu changes to be made, including order
# menu order changed by "moving array order"
# actual menu plugin can still re-order by key, or choose individual entries
	$links = array(
		'forum'        => array('title'=>$ADMIN_MENU_LANG["forum"],        'image'=>'Forums_Admin.png'),
		'links'        => array('title'=>$ADMIN_MENU_LANG["links"],        'image'=>'Links_Admin.png'),
		'users'        => array('title'=>$ADMIN_MENU_LANG["users"],        'image'=>'Members_Admin.png'),
		'news'         => array('title'=>$ADMIN_MENU_LANG["news"],         'image'=>'News_Admin.png'),
		'pages'        => array('title'=>$ADMIN_MENU_LANG["pages"],        'image'=>'Pages_Admin.png'),
		'panels'       => array('title'=>$ADMIN_MENU_LANG["panels"],       'image'=>'Panels_Admin.png'),
		'bans'         => array('title'=>$ADMIN_MENU_LANG["bans"],         'image'=>'Bans_Admin.png'),
		'sitesettings' => array('title'=>$ADMIN_MENU_LANG["sitesettings"], 'image'=>'Settings_Admin.png'),
		'themes'       => array('title'=>$ADMIN_MENU_LANG["themes"],       'image'=>'Themes_Admin.png'),
		'plugins'      => array('title'=>$ADMIN_MENU_LANG["plugins"],      'image'=>'Plugins_Admin.png'),
		'permissions'  => array('title'=>$ADMIN_MENU_LANG["permissions"],  'image'=>'Permissions_Admin.png'),
		'emoticons'    => array('title'=>$ADMIN_MENU_LANG["emoticons"],    'image'=>'Smiley_Admin.png'),
		'articles'     => array('title'=>$ADMIN_MENU_LANG["articles"],     'image'=>'Pages_Admin.png')
	);
# preliminary eval routine
# needs new image names & themes need reworking before using
	$menu_end = end($a_action);
	foreach($a_action as $key=>$value){
		$eval_menu .= "'" . $key . "'=>array('title'=>\"" . $ADMIN_MENU_LANG[$key] . "\",'image'=>'Admin_" . $key . ".png')" . ($value==$menu_end?'':',');
	}
	eval('$links=array(' . $eval_menu . ');');

	return $links;
}
function adminlinks() {
	global $ADMIN_MENU_LANG;
	$links = array(
		$ADMIN_MENU_LANG["forum"]          => 'forum',
		$ADMIN_MENU_LANG["links"]          => 'links',
		$ADMIN_MENU_LANG["users"]          => 'users',
		$ADMIN_MENU_LANG["news"]           => 'news',
		$ADMIN_MENU_LANG["pages"]          => 'pages',
		$ADMIN_MENU_LANG["panels"]         => 'panels',
		$ADMIN_MENU_LANG["bans"]           => 'bans',
		$ADMIN_MENU_LANG["sitesettings"]   => 'sitesettings',
		$ADMIN_MENU_LANG["themes"]         => 'themes',
		$ADMIN_MENU_LANG["plugins"]        => 'plugins',
		$ADMIN_MENU_LANG["permissions"]    => 'permissions',
		$ADMIN_MENU_LANG["emoticons"]      => 'emoticons',
		$ADMIN_MENU_LANG["articles"]       => 'articles',
		'images'=>array(
			$ADMIN_MENU_LANG["forum"]          => 'Forums_Admin.png',
			$ADMIN_MENU_LANG["links"]          => 'Links_Admin.png',
			$ADMIN_MENU_LANG["users"]          => 'Members_Admin.png',
			$ADMIN_MENU_LANG["news"]           => 'News_Admin.png',
			$ADMIN_MENU_LANG["pages"]          => 'Pages_Admin.png',
			$ADMIN_MENU_LANG["panels"]         => 'Panels_Admin.png',
			$ADMIN_MENU_LANG["bans"]           => 'Bans_Admin.png',
			$ADMIN_MENU_LANG["sitesettings"]   => 'Settings_Admin.png',
			$ADMIN_MENU_LANG["themes"]         => 'Themes_Admin.png',
			$ADMIN_MENU_LANG["plugins"]        => 'Plugins_Admin.png',
			$ADMIN_MENU_LANG["permissions"]    => 'Permissions_Admin.png',
			$ADMIN_MENU_LANG["emoticons"]      => 'Smiley_Admin.png',
			$ADMIN_MENU_LANG["articles"]       => 'Pages_Admin.png'
		)
	);
	return $links;
}
function ORIGadminlinks() {
	$links = array(
		'Forum'          => 'forum',
		'Links'          => 'links',
		'Members'        => 'users',
		'News'           => 'news',
		'Pages'          => 'pages',
		'Panels'         => 'panels',
		'Bans'           => 'bans',
		'Site Settings'  => 'sitesettings',
		'Themes' => 'themes',
		'Plugins'        => 'plugins',
		'Permissions'    => 'permissions',
		'Emoticons'      => 'emoticons',
		'Articles'       => 'articles',
		'images'=>array(
			'Forum'          => 'Forums_Admin.png',
			'Links'          => 'Links_Admin.png',
			'Members'        => 'Members_Admin.png',
			'News'           => 'News_Admin.png',
			'Pages'          => 'Pages_Admin.png',
			'Panels'         => 'Panels_Admin.png',
			'Bans'           => 'Bans_Admin.png',
			'Site Settings'  => 'Settings_Admin.png',
			'Themes' => 'Themes_Admin.png',
			'Plugins'        => 'Plugins_Admin.png',
			'Permissions'    => 'Permissions_Admin.png',
			'Emoticons'      => 'Smiley_Admin.png',
			'Articles'       => 'Pages_Admin.png'
		)
	);
	return $links;
}
?>