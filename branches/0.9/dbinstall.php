<?php
if(defined('COOKIE_LANG')) 
	include_once('language/'.COOKIE_LANG.'/dbdefaults.php');
if(isset($_REQUEST['lang'])) 
	include_once('language/'.$_REQUEST['lang'].'/dbdefaults.php');
if(!isset($dbDEFAULTS_LANG) || !is_array($dbDEFAULTS_LANG)) 
	include_once('language/en/dbdefaults.php');
if(!function_exists('replaceTree')) {
	function replaceTree($search="", $replace="", $array=false, $keys_too=false) { 
		if (!is_array($array)) {
			// Regular replace
			return str_replace($search, $replace, $array);
		}
		$newArr = array();
		foreach ($array as $k=>$v) {
			// Replace keys as well?
			$add_key = $k;
			if ($keys_too) {
				$add_key = str_replace($search, $replace, $k);
			}	
			// Recurse
			$newArr[$add_key] = replaceTree($search, $replace, $v, $keys_too);
		}
		return $newArr;
	}
}
# language replace routine
if(!function_exists('replaceLang')) {
	function replaceLang($_LANG, $db_string) {
		if(!$db_string)
			return "";
		else {
			foreach($_LANG as $key => $value) {
				$db_string = replaceTree('{'.$key.'}', Sanitize($value), $db_string);	
			}
			return $db_string;
		}
	}
}
$tables = array('activation_keys',
				'articles',
				'article_categories',
				'bans', 
				'board_permissions', 
				'board_read', 
				'comments', 
				'emoticons', 
				'forum_boards', 
				'forum_categories', 
				'forum_moderators', 
				'forum_posts', 
				'forum_topics', 
				'membergroups', 
				'menu',
				'news', 
				'news_categories', 
				'pages', 
				'panels', 
				'permissions', 
				'plugins', 
				'pm', 
				'ratings', 
				'settings', 
				'theme_settings', 
				'themes', 
				'topic_read',
				'users',
				'user_online');
$sql = array('activation_keys' => array(
	'columns' => array('user_id', 'key_number'),
	'types' => array('int(255)', 'varchar(32)'),
	'keys' => array(
		'primary' => 'user_id',
		'auto_increment' => 'user_id'
	)
),'articles' => array(
	'columns' => array('id', 'subject', 'cat', 'summary', 'full_article', 'time_created', 'views', 'comments', 'ratings', 'author_id', 'name_author'),
	'types' => array('int(255)', 'text', 'mediumint(8)', 'text', 'text', 'int(10)', 'int(10) default \'0\'', 'int(1) default \'0\'', 'int(1) default \'0\'', 'int(255)', 'text'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'article_categories' => array(
	'columns' => array('id', 'name', 'description', 'item_order', 'visible'),
	'types' => array('int(255)', 'text', 'text', 'int(225)', 'varchar(225)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'bans' => array(
	'columns' => array('id', 'ip', 'ip_range', 'time_created', 'reason', 'created_by'),
	'types' => array('int(255)', 'text', 'text', 'int(11)', 'text', 'varchar(225)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'board_read' => array(
	'columns' => array('user_id', 'board_id'),
	'types' => array('int(255)', 'mediumint(8)'),
	'keys' => array(
		'key' => 'user_id'
	)
),'board_permissions' => array(
	'columns' => array('membergroup_id', 'board_id', 'variable', 'value'),
	'types' => array('int(255)', 'int(11)', 'text', 'tinyint(1)'),
	'keys' => array(
		'key' => array('board_id', 'value')
	)
),'comments' => array(
	'columns' => array('id', 'comment_type', 'type_id', 'message', 'author', 'author_id', 'ip', 'post_time'),
	'types' => array('int(255)', 'text', 'mediumint(8)', 'text', 'varchar(225)', 'int(255)', 'varchar(20)', 'int(10)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => 'id'
	)
),'emoticons' => array(
	'columns' => array('id', 'image', 'alt', 'code', 'keycode'),
	'types' => array('int(25)', 'text', 'varchar(225)', 'text', 'varchar(225)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'forum_boards' => array(
	'columns' => array('id', 'board_name', 'board_description', 'topics_count', 'posts_count', 'visible', 'post_group', 'item_order', 'last_msg', 'cat', 'creation_sticky', 'creation_lock'),
	'types' => array('int(255)', 'text', 'text', 'int(255) default \'0\'', 'int(255) default \'0\'', 'varchar(225)', 'varchar(225)', 'int(225)', 'varchar(225)', 'mediumint(8)', 'int(1) default \'0\'', 'int(1) default \'0\''),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => array('last_msg', 'item_order', 'posts_count', 'topics_count')
	)
),'forum_categories' => array(
	'columns' => array('id', 'cat_name', 'item_order'),
	'types' => array('int(11)', 'text', 'int(225)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'forum_moderators' => array(
	'columns' => array('id', 'board_id', 'user_id'),
	'types' => array('int(255)', 'int(255)', 'int(255)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'forum_posts' => array(
	'columns' => array('id', 'topic_id', 'board_id', 'post_time', 'author_id', 'name_author', 'subject', 'message', 'ip', 'disable_smiley', 'modified_time', 'modified_name', 'modified_nameid'),
	'types' => array('int(255)', 'int(255)', 'mediumint(8)', 'int(10)', 'int(255)', 'tinytext', 'tinytext', 'longtext', 'tinytext', 'int(1) default \'0\'', 'int(10)', 'tinytext', 'int(255)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => array('post_time', 'topic_id')
	)
),'forum_topics' => array(
	'columns' => array('topic_id', 'board_id', 'thread_title', 'thread_author', 'topic_ip', 'date_created', 'locked', 'replies', 'latest_reply', 'sticky', 'views', 'hottopic'),
	'types' => array('int(255)', 'int(255)', 'varchar(225)', 'varchar(225)', 'varchar(15)', 'varchar(225)', 'int(1) default \'0\'', 'int(255) default \'0\'', 'varchar(225) default \'0\'', 'int(1) default \'0\'', 'int(255) default \'0\'', 'int(1) default \'0\''),
	'keys' => array(
		'primary' => 'topic_id',
		'auto_increment' => 'topic_id',
		'key' => array('latest_reply', 'replies')
	)
),'membergroups' => array(
	'columns' => array('membergroup_id', 'name', 'image', 'colour'),
	'types' => array('int(255)', 'varchar(225)', 'text', 'varchar(225)'),
	'keys' => array(
		'primary' => 'membergroup_id',
		'auto_increment' => 'membergroup_id'
	)
),'menu' => array(
	'columns' => array('id', 'name', 'link', 'item_order', 'rank', 'authid', 'window', 'height', 'width'),
	'types' => array('int(255)', 'varchar(225)', 'varchar(225)', 'int(225)', 'varchar(225)', 'tinyint(1)', 'text', 'int(5)', 'int(5)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => 'item_order'
	)
), 'news' => array(
	'columns' => array('id', 'subject', 'cat', 'content', 'extended', 'time_created', 'start_time', 'end_time', 'visibility', 'views', 'sticky', 'comments', 'ratings', 'created_by'),
	'types' => array('int(255)', 'varchar(225)', 'mediumint(8)', 'text', 'text', 'int(10)', 'int(10) default \'0\'', 'int(10) default \'0\'', 'varchar(225)', 'int(255) default \'0\'', 'tinyint(1) default \'0\'', 'tinyint(1) default \'0\'', 'tinyint(1)', 'varchar(225)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => array('time_created', 'views')
	)
),'news_categories' => array(
	'columns' => array('id', 'name', 'image'),
	'types' => array('int(255)', 'varchar(225)', 'varchar(225)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'pages' => array(
	'columns' => array('id', 'pagename', 'content', 'comments', 'ratings'),
	'types' => array('int(255)', 'varchar(225)', 'text', 'tinyint(1) default \'0\'', 'tinyint(1) default \'0\''),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'panels' => array(
	'columns' => array('id', 'panelname', 'panelcontent', 'rank', 'side', 'online', 'file', 'all_pages', 'item_order', 'type'),
	'types' => array('int(255)', 'varchar(225)', 'longtext', 'varchar(225)', 'varchar(225)', 'tinyint(1) default \'0\'', 'varchar(225)', 'tinyint(1) default \'0\'', 'int(255)', 'text'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => 'item_order'
	)
),'permissions' => array(
	'columns' => array('id', 'membergroup_id', 'variable', 'value'),
	'types' => array('int(255)', 'int(255)', 'text', 'tinyint(1)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'plugins' => array(
	'columns' => array('id', 'name', 'version', 'eocms_version', 'layout', 'layout_include', 'admin_control', 'admin_layouts', 'active', 'folder', 'author', 'author_site', 'everypage', 'panels', 'settings'),
	'types' => array('int(255)', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'text', 'varchar(225)', 'text', 'int(1)', 'text', 'text', 'text', 'text', 'text', 'text'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'pm' => array(
	'columns' => array('id', 'to_send', 'sender', 'title', 'message', 'time_sent', 'mark_read', 'mark_delete', 'mark_sent_delete', 'mark_sent'),
	'types' => array('int(255)', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'text', 'int(10)', 'int(1) default \'0\'', 'int(1) default \'0\'', 'int(1) default \'0\'', 'int(1) default \'0\''),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id',
		'key' => 'time_sent'
	)
),'ratings' => array(
	'columns' => array('id', 'rating', 'user_id', 'type_id', 'type', 'ip'),
	'types' => array('int(255)', 'int(1)', 'int(255)', 'int(255)', 'text', 'text'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'settings' => array(
	'columns' => array('id', 'variable', 'value', 'help_text'),
	'types' => array('int(255)', 'text', 'text', 'text'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'theme_settings' => array(
	'columns' => array('theme_id', 'variable', 'value'),
	'types' => array('int(255)', 'text', 'text')
),'themes' => array(
	'columns' => array('theme_id', 'theme_name', 'theme_author', 'author_site', 'author_email', 'theme_version', 'folder', 'theme_visibility', 'theme_preview'),
	'types' => array('int(255)', 'text', 'text', 'text', 'text', 'varchar(225)', 'text', 'varchar(225)', 'text'),
	'keys' => array(
		'primary' => 'theme_id',
		'auto_increment' => 'theme_id'
	)
),'topic_read' => array(
	'columns' => array('user_id', 'topic_id'),
	'types' => array('mediumint(8)', 'mediumint(8)'),
	'keys' => array(
		'key' => 'topic_id'
	)
),'users' => array(
	'columns' => array('id', 'ssid', 'agent', 'user', 'pass', 'email', 'ip', 'regdate', 'lastlogin', 'membergroup', 'allow_login', 'posts', 'avatar', 'signature', 'location', 'birthday', 'show_email', 'msn', 'icq', 'yim', 'aim', 'offset', 'topics_page', 'posts_topic', 'quickreply', 'theme', 'gender', 'admin_menu', 'time_online'),
	'types' => array('int(255)', 'varchar(225)', 'text', 'text', 'text', 'text', 'text', 'int(11)', 'text', 'int(11) default "1"', 'int(11) default "1"', 'int(255)', 'text', 'text', 'varchar(225)', 'varchar(225)', 'varchar(1) default \'1\'', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'varchar(225)', 'varchar(3)', 'tinyint(9)', 'tinyint(9)', 'tinyint(1)', 'varchar(225)', 'tinyint(1)', 'text', 'int(11)'),
	'keys' => array(
		'primary' => 'id',
		'auto_increment' => 'id'
	)
),'user_online' => array(
	'columns' => array('user_id', 'time_online', 'ip'),
	'types' => array('int(255)', 'int(15)', 'varchar(40)')
	)
);
$inserts = array('membergroups' => array("INSERT INTO membergroups (membergroup_id, name, image) VALUES (1, '{guest}', '');",
"INSERT INTO membergroups (membergroup_id, name, image) VALUES (2, '{member}', 'member.png');", 
"INSERT INTO membergroups (membergroup_id, name, image) VALUES (3, '{global_moderator}', 'gmod.png');", 
"INSERT INTO membergroups (membergroup_id, name, image) VALUES (4, '{admin}', 'admin.png');"),

'menu' => array("INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (1, '{home}', '".$settings['site_url']."/index.php', '1', '1,2,3,4', 0, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (2, '{forum}', '".$settings['site_url']."/index.php?act=forum', '2', '1,2,3,4', 0, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (3, '{messages}', '".$settings['site_url']."/index.php?act=pm', '3', '2,3,4', 0, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (4, '{logout}', '".$settings['site_url']."/index.php?act=logout', '4', '2,3,4', 1, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (5, '{login}', '".$settings['site_url']."/index.php?act=login', '5', '1', 0, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (6, '{register}', '".$settings['site_url']."/index.php?act=register', '6', '1', 0, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (7, '{profile}', '".$settings['site_url']."/index.php?act=editprofile', '7', '2,3,4', 0, 'same', 0, 0);",
"INSERT INTO menu (id, name, link, item_order, rank, authid, window, height, width) VALUES (8, '{admin}', '".$settings['site_url']."/index.php?act=admin', '8', '4', 1, 'same', 0, 0);"),

'pages' => array("INSERT INTO pages (id, pagename, content) VALUES (1, '{home}', '{home_content}');"), 

'permissions' => array("INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'all_permissions', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'modify_own_posts', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'modify_any_posts', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'delete_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'lock_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'lock_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'move_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'move_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'sticky_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'sticky_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'edit_profile', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'have_signature', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'have_avatar', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'read_pms', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'send_pms', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'admin_panel', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'javascript', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'maintenance_access', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'all_permissions', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'modify_own_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'modify_any_posts', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'delete_own_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'delete_any_posts', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'delete_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'delete_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'lock_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'lock_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'move_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'move_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'sticky_own_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'sticky_any_topic', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'edit_profile', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'have_signature', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'have_avatar', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'read_pms', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'send_pms', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'admin_panel', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'javascript', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'maintenance_access', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'all_permissions', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'modify_own_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'modify_any_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'delete_own_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'delete_any_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'delete_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'delete_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'lock_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'lock_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'move_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'move_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'sticky_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'sticky_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'edit_profile', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'have_signature', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'have_avatar', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'read_pms', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'send_pms', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'admin_panel', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'javascript', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'maintenance_access', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'read_pms', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'have_avatar', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'have_signature', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'edit_profile', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'sticky_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'sticky_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'move_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'move_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'lock_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'lock_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'delete_any_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'delete_own_topic', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'delete_any_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'delete_own_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'modify_any_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'modify_own_posts', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'all_permissions', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'maintenance_access', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'javascript', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'admin_panel', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'send_pms', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'modify_own_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'modify_any_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'delete_any_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'delete_own_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'post_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'modify_own_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'modify_any_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'delete_any_comment', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'delete_own_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'post_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'modify_own_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'modify_any_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'delete_any_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'delete_own_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'post_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'modify_own_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'modify_any_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'delete_any_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'delete_own_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'post_comment', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'post_rating', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'post_rating', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'post_rating', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'post_rating', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'track_ip', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'track_ip', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'track_ip', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'track_ip', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (1, 'multi-moderate', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (2, 'multi-moderate', 0);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (3, 'multi-moderate', 1);", 
"INSERT INTO permissions (membergroup_id, variable, value) VALUES (4, 'multi-moderate', 1);"), 

'themes' => array("INSERT INTO themes (theme_id, theme_name, theme_author, author_site, author_email, theme_version, folder, theme_visibility, theme_preview) VALUES
(1, 'default', 'confuser', 'http://eocms.com', '', '1', 'default', '1,2,3,4', 'default_preview.jpg');",
"INSERT INTO theme_settings VALUES(1, 'footer', '');",
"INSERT INTO theme_settings VALUES(1, 'logo', 'images/logo.png');",
"INSERT INTO theme_settings VALUES(1, 'exclude_left', 'forum, viewtopic, viewboard, newtopic, reply, editpost, pm');",
"INSERT INTO theme_settings VALUES(1, 'exclude_right', 'login, sendpm, pm');",
"INSERT INTO theme_settings VALUES(1, 'exclude_upper', 'forum');",
"INSERT INTO theme_settings VALUES(1, 'exclude_lower', 'forum');"), 

'settings' => array("INSERT INTO settings (id, variable, value, help_text) VALUES (1, 'registration', 'on', '{h_reg}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (4, 'site_theme', 'default', '');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (7, 'register_approval', 'none', '{h_approval}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (8, 'mail', '', '{h_test_email}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (9, 'smtp_host', '', '');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (10, 'smtp_username', '', '');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (11, 'smtp_password', '', '');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (12, 'email', '', '{h_email}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (22, 'maintenance_mode', '0', '{h_maintenance}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (23, 'maintenance_message', '{maint_message}', '{h_maint_message}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (24, 'topics_page', '20', '{h_topics}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (25, 'posts_topic', '20', '{h_posts}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (26, 'avatar_height', '200', '{h_avatar_height}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (27, 'avatar_width', '200', '{h_avatar_width}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (28, 'version', '0.9.03', '{h_version}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (29, 'register_captcha', 'on', '{h_captcha}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (30, 'tos', '', '');"),

'emoticons' => array("INSERT INTO emoticons (id, image, alt, code) VALUES(1, 'cross.png', 'Cross', ':cross:');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(2, 'sad.png', 'Sad', ':(');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(3, 'happy.png', 'Happy', ':&#039;)');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(4, 'cry.png', 'Cry', ':&#039;(');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(5, 'mad.png', 'Mad', '-.-');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(6, 'tongue.png', 'Tongue', ':P');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(7, 'smile.png', 'Smile', ':)');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(8, 'surprised.png', 'Surprised', ':O');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(9, 'biggrin.png', 'Grin', ':D');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(10, 'confused.png', 'Confused', ':confused:');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(11, 'cool.png', 'Cool', '8)');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(12, 'tick.png', 'Tick', ':tick:');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(13, 'angry.png', 'Angry', ':@');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(14, 'sarcasticsmile.png', 'Sarcastic Smile', ':sarcastic:');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(15, 'ssmile.png', ':S', ':S');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(16, 'rolleyes.png', 'Roll Eyes', '8-)');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(17, 'mrgreen.png', 'Mr Green', ':mrgreen:');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(18, 'twisted.png', 'Twisted', ':twisted:');", 
"INSERT INTO emoticons (id, image, alt, code) VALUES(19, 'wink.png', 'Wink', ';)');"));
?>