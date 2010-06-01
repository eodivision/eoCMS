<?php
/*
DROP TABLE IF EXISTS `activation_keys`;
CREATE TABLE `activation_keys` (
  `user_id` int(225) NOT NULL auto_increment,
  `key_number` varchar(32) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(225) NOT NULL auto_increment,
  `subject` text NOT NULL,
  `cat` mediumint(8) NOT NULL,
  `summary` text NOT NULL,
  `full_article` text NOT NULL,
  `time_created` int(10) NOT NULL,
  `views` int(10) NOT NULL,
  `comments` tinyint(1) NOT NULL,
  `ratings` tinyint(1) NOT NULL,
  `author_id` int(225) NOT NULL,
  `name_author` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `articles_categories`;
CREATE TABLE IF NOT EXISTS `article_categories` (
  `id` int(225) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `item_order` varchar(225) NOT NULL,
  `visible` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `id` int(225) NOT NULL auto_increment,
  `ip` text NOT NULL,
  `ip_range` text NOT NULL,
  `time_created` int(11) NOT NULL default '0',
  `reason` text NOT NULL,
  `created_by` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `board_read`;
CREATE TABLE `board_read` (
  `user_id` mediumint(8) NOT NULL,
  `board_id` mediumint(8) NOT NULL,
  KEY `board_id` (`board_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `board_permissions`;
CREATE TABLE `board_permissions` (
  `membergroup_id` int(255) NOT NULL,
  `board_id` int(11) NOT NULL,
  `variable` text NOT NULL,
  `value` tinyint(1) NOT NULL,
  KEY `board_id` (`board_id`),
  KEY `membergroup_id` (`membergroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` mediumint(8) NOT NULL auto_increment,
  `comment_type` text NOT NULL,
  `type_id` mediumint(8) NOT NULL,
  `message` text NOT NULL,
  `author` varchar(225) NOT NULL,
  `author_id` mediumint(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `post_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `emoticons`;
CREATE TABLE `emoticons` (
  `id` INT(25) NOT NULL AUTO_INCREMENT ,
  `image` TEXT NOT NULL ,
  `alt` varchar(225) NOT NULL ,
  `code` TEXT NOT NULL ,
  `keycode` varchar(225) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forum_boards`;
CREATE TABLE `forum_boards` (
  `id` int(225) NOT NULL auto_increment,
  `board_name` varchar(225) NOT NULL,
  `board_description` varchar(225) NOT NULL,
  `topics_count` varchar(225) NOT NULL default '0',
  `posts_count` varchar(225) NOT NULL default '0',
  `visible` varchar(225) NOT NULL,
  `post_group` varchar(225) NOT NULL,
  `item_order` varchar(225) NOT NULL,
  `last_msg` varchar(225) NOT NULL,
  `cat` mediumint(8) NOT NULL,
  `creation_sticky` INT(1) NOT NULL default '0',
  `creation_lock` INT(1) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `last_msg` (`last_msg`),
  KEY `item_order` (`item_order`),
  KEY `posts_count` (`posts_count`),
  KEY `topics_count` (`topics_count`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forum_categories`;
CREATE TABLE `forum_categories` (
  `id` int(11) NOT NULL auto_increment,
  `cat_name` text NOT NULL,
  `item_order` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forum_moderators`;
CREATE TABLE `forum_moderators` (
  `id` tinyint(225) NOT NULL auto_increment,
  `board_id` varchar(225) NOT NULL,
  `user_id` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE `forum_posts` (
  `id` int(225) NOT NULL auto_increment,
  `topic_id` mediumint(8) NOT NULL,
  `board_id` mediumint(8) NOT NULL,
  `post_time` int(10) NOT NULL,
  `author_id` mediumint(8) NOT NULL,
  `name_author` tinytext NOT NULL,
  `subject` tinytext NOT NULL,
  `message` longtext NOT NULL,
  `ip` tinytext NOT NULL,
  `disable_smiley` varchar(1) NOT NULL default '0',
  `modified_time` int(10) NOT NULL,
  `modified_name` tinytext NOT NULL,
  `modified_nameid` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_time` (`post_time`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE `forum_topics` (
  `topic_id` int(225) NOT NULL auto_increment,
  `board_id` varchar(225) NOT NULL default '',
  `thread_title` varchar(225) NOT NULL default '',
  `thread_author` varchar(225) NOT NULL default '',
  `topic_ip` varchar(15) NOT NULL default '',
  `date_created` varchar(225) NOT NULL,
  `locked` varchar(1) NOT NULL default '0',
  `replies` varchar(225) NOT NULL default '0',
  `latest_reply` varchar(225) NOT NULL,
  `sticky` varchar(1) NOT NULL default '0',
  `views` INT( 225 ) NOT NULL default'0',
  `hottopic` varchar(1) NOT NULL default '0',
  PRIMARY KEY (`topic_id`),
  KEY `latest_reply` (`latest_reply`),
  KEY `replies` (`replies`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `membergroups`;
CREATE TABLE `membergroups` (
  `membergroup_id` int(255) NOT NULL auto_increment,
  `name` varchar(225) NOT NULL,
  `image` text NOT NULL,
  `colour` varchar(225) NOT NULL,
  PRIMARY KEY (`membergroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(225) NOT NULL auto_increment,
  `name` varchar(225) NOT NULL,
  `link` varchar(225) NOT NULL,
  `item_order` varchar(225) NOT NULL,
  `rank` varchar(225) NOT NULL,
  `authid` tinyint(1) NOT NULL,
  `window` TEXT NOT NULL,
  `height` INT( 5 ) NOT NULL,
  `width` INT( 5 ) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_order` (`item_order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `subject` varchar(200) NOT NULL default '',
  `cat` mediumint(8) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  `extended` text NOT NULL,
  `time_created` int(10) unsigned NOT NULL default '0',
  `start_time` int(10) NOT NULL default '0',
  `end_time` int(10) NOT NULL default '0',
  `visibility` varchar(225) NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `sticky` tinyint(1) unsigned NOT NULL default '0',
  `comments` tinyint(1) unsigned NOT NULL default '1',
  `ratings` tinyint(1) unsigned NOT NULL default '1',
  `created_by` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time_created` (`time_created`),
  KEY `views` (`views`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `news_categories`;
CREATE TABLE `news_categories` (
  `id` int(225) NOT NULL auto_increment,
  `name` varchar(225) NOT NULL,
  `image` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(225) NOT NULL auto_increment,
  `pagename` varchar(225) NOT NULL default '',
  `content` text NOT NULL,
  `comments` tinyint(1) NOT NULL default '0',
  `ratings` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `panels`;
CREATE TABLE `panels` (
  `id` int(225) NOT NULL auto_increment,
  `panelname` varchar(225) NOT NULL,
  `panelcontent` longtext NOT NULL,
  `rank` varchar(225) NOT NULL,
  `side` varchar(225) NOT NULL,
  `online` varchar(225) NOT NULL default '0',
  `file` varchar(225) NOT NULL,
  `all_pages` tinyint(1) NOT NULL default '0',
  `item_order` varchar(225) NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_order` (`item_order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL auto_increment,
  `membergroup_id` int(225) NOT NULL,
  `variable` text NOT NULL,
  `value` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` ( 
  `id` int(255) AUTO_INCREMENT, 
  `name` varchar(225), 
  `version` varchar(225), 
  `eocms_version` varchar(225), 
  `layout` varchar(225), 
  `layout_include` text, 
  `admin_control` varchar(225), 
  `admin_layouts` text, 
  `active` int(1), 
  `folder` text, 
  `author` text, 
  `author_site` text, 
  `everypage` text, 
  `panels` text, 
  `settings` text, 
  PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pm`;
CREATE TABLE `pm` (
  `id` int(225) NOT NULL auto_increment,
  `to_send` varchar(225) NOT NULL,
  `sender` varchar(225) NOT NULL,
  `title` varchar(225) NOT NULL,
  `message` text NOT NULL,
  `time_sent` varchar(225) NOT NULL,
  `mark_read` varchar(1) NOT NULL default '0',
  `mark_delete` varchar(1) NOT NULL default '0',
  `mark_sent_delete` varchar(1) NOT NULL default '0',
  `mark_sent` varchar(1) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `time_sent` (`time_sent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` tinyint(225) NOT NULL auto_increment,
  `variable` text NOT NULL,
  `value` text NOT NULL,
  `help_text` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `theme_settings`;
CREATE TABLE `theme_settings` (
  `theme_id` tinyint(225) NOT NULL,
  `variable` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `theme_id` int(255) NOT NULL AUTO_INCREMENT,
  `theme_name` text NOT NULL,
  `theme_author` text NOT NULL,
  `author_site` text NOT NULL,
  `author_email` text NOT NULL,
  `theme_version` varchar(225) NOT NULL,
  `folder` text NOT NULL,
  `theme_visibility` varchar(225) NOT NULL,
  `theme_preview` text NOT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `topic_read`;
CREATE TABLE `topic_read` (
  `user_id` mediumint(8) NOT NULL,
  `topic_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `id` int(225) NOT NULL auto_increment,
  `rating` int(1) NOT NULL,
  `user_id` int(225) NOT NULL,
  `type_id` int(225) NOT NULL,
  `type` TEXT NOT NULL,
  `ip` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(225) NOT NULL auto_increment,
  `ssid` varchar(225) NOT NULL,
  `agent` text NOT NULL,
  `user` text NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `ip` text NOT NULL,
  `regdate` int(11) NOT NULL default '0',
  `lastlogin` text NOT NULL,
  `membergroup` int(11) NOT NULL default '1',
  `allow_login` int(11) NOT NULL default '1',
  `posts` smallint(5) NOT NULL default '0',
  `avatar` varchar(225) NOT NULL,
  `signature` text NOT NULL,
  `location` varchar(225) NOT NULL,
  `birthday` varchar(225) NOT NULL,
  `show_email` varchar(1) NOT NULL default '1',
  `msn` varchar(225) NOT NULL,
  `icq` varchar(225) NOT NULL,
  `yim` varchar(225) NOT NULL,
  `aim` varchar(225) NOT NULL,
  `offset` varchar(3) NOT NULL default '0',
  `topics_page` tinyint(9) NOT NULL default '0',
  `posts_topic` tinyint(9) NOT NULL default '0',
  `quickreply` varchar(1) NOT NULL default '0',
  `theme` varchar(225) NOT NULL default 'default',
  `gender` varchar(1) NOT NULL,
  `gavatar` int(1) NOT NULL default '0',
  `gavataremail` varchar(225) NOT NULL default '',
  `admin_menu` TEXT NOT NULL,
  `time_online` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `user_online`;
CREATE TABLE `user_online` (
  `user_id` varchar(225) NOT NULL default '0',
  `time_online` int(15) NOT NULL default '0',
  `ip` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


*/
?>