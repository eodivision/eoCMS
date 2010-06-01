<?php
/*

CREATE TABLE activation_keys  (
   user_id INTEGER PRIMARY KEY,
   key_number varchar(32)
);

CREATE TABLE articles (
   id INTEGER PRIMARY KEY,
   subject text,
   cat mediumint(8),
   summary text,
   full_article text,
   time_created int(10),
   views int(10),
   comments tinyint(1),
   ratings tinyint(1),
   author_id int(225),
   name_author text
);

CREATE TABLE article_categories (
   id INTEGER PRIMARY KEY,
   name text,
   description text,
   item_order varchar(225),
   visible varchar(225)
);

CREATE TABLE bans  (
   id  INTEGER PRIMARY KEY,
   ip  text,
   ip_range  text,
   time_created  int(11) default '0',
   reason  text,
   created_by  varchar(225)
);

CREATE TABLE board_read  (
   user_id  mediumint(8),
   board_id  mediumint(8)
);

CREATE TABLE board_permissions (
   membergroup_id int(255),
   board_id  int(11),
   variable  text,
   value  tinyint(1)
);

CREATE TABLE comments  (
   id INTEGER PRIMARY KEY,
   comment_type  text,
   type_id  mediumint(8),
   message  text,
   author  varchar(225),
   author_id  mediumint(8),
   ip  varchar(20),
   post_time  int(10)
);

CREATE TABLE emoticons (
   id INTEGER PRIMARY KEY,
   image text,
   alt  varchar(225),
   code text,
   keycode  varchar(225) default ''
);

CREATE TABLE forum_boards  (
   id  INTEGER PRIMARY KEY,
   board_name  varchar(225),
   board_description  varchar(225),
   topics_count  varchar(225) default '0',
   posts_count  varchar(225) default '0',
   visible  varchar(225),
   post_group  varchar(225),
   item_order  varchar(225),
   last_msg  varchar(225),
   cat mediumint(8),
   creation_sticky int(1) default '0',
   creation_lock int(1) default '0'
);

CREATE TABLE forum_categories  (
   id  INTEGER PRIMARY KEY,
   cat_name text NOT NULL,
   item_order varchar(225)
);

CREATE TABLE forum_moderators  (
   id  INTEGER PRIMARY KEY,
   board_id  varchar(225),
   user_id  varchar(225)
);

CREATE TABLE forum_posts  (
   id  INTEGER PRIMARY KEY,
   topic_id  mediumint(8),
   board_id  mediumint(8),
   post_time  int(10),
   author_id  mediumint(8),
   name_author  tinytext,
   subject  tinytext,
   message  longtext,
   ip  tinytext,
   disable_smiley  varchar(1) default '0',
   modified_time int(10),
   modified_name tinytext,
   modified_nameid mediumint(8)
);

CREATE TABLE forum_topics  (
   topic_id  INTEGER PRIMARY KEY,
   board_id  varchar(225) default '',
   thread_title  varchar(225) default '',
   thread_author  varchar(225) default '',
   topic_ip  varchar(15) default '',
   date_created  varchar(225),
   locked  varchar(1) default '0',
   replies  varchar(225) default '0',
   latest_reply  varchar(225),
   sticky  varchar(1) default '0',
   views  int(225) default '0',
   hottopic  varchar(1) default '0'
);

CREATE TABLE membergroups  (
   membergroup_id  INTEGER PRIMARY KEY,
   name  varchar(225),
   image  text,
   colour  varchar(225)

);

CREATE TABLE menu  (
   id  INTEGER PRIMARY KEY,
   name  varchar(225),
   link  varchar(225),
   item_order  varchar(225),
   rank  varchar(225),
   authid  int(1),
   window text,
   height int(5),
   width int(5)
);

CREATE TABLE news  (
   id  INTEGER PRIMARY KEY,
   subject  varchar(200) default '',
   cat  mediumint(8) default '0',
   content  text,
   extended  text,
   time_created  int(10) default '0',
   start_time  int(10) default '0',
   end_time  int(10) default '0',
   visibility  varchar(225) default '0',
   views  int(10) default '0',
   sticky  tinyint(1) default '0',
   comments  tinyint(1) default '1',
   ratings  tinyint(1) default '1',
   created_by  varchar(225)
);

CREATE TABLE news_categories  (
   id  INTEGER PRIMARY KEY,
   name  varchar(225),
   image  varchar(225)
);

CREATE TABLE pages  (
   id  INTEGER PRIMARY KEY,
   pagename  varchar(225) default '',
   content  text,
   comments  tinyint(1) default '0',
   ratings  tinyint(1) default '1'
);

CREATE TABLE panels  (
   id  INTEGER PRIMARY KEY,
   panelname  varchar(225),
   panelcontent  longtext,
   rank  varchar(225),
   side  varchar(225),
   online  varchar(225) default '0',
   file  varchar(225),
   all_pages  tinyint(1) default '0',
   item_order  varchar(225),
   type text
);

CREATE TABLE permissions  (
   id  INTEGER PRIMARY KEY,
   membergroup_id  int(255),
   variable  text,
   value  tinyint(1)
);

CREATE TABLE plugins (
   id int(255) PRIMARY KEY, 
   name varchar(225),
   version varchar(225),
   eocms_version varchar(225),
   layout varchar(225), 
   layout_include text,
   admin_control varchar(225),
   admin_layouts text, 
   active int(1), 
   folder text, 
   author text, 
   author_site text, 
   everypage text, 
   panels text, 
   settings text
);

CREATE TABLE pm  (
   id  INTEGER PRIMARY KEY,
   to_send  varchar(225),
   sender  varchar(225),
   title  varchar(225),
   message  text,
   time_sent  varchar(225),
   mark_read  varchar(1) default '0',
   mark_delete  varchar(1) default '0',
   mark_sent_delete varchar(1) default '0',
   mark_sent varchar(1) default '0'
);

CREATE TABLE settings  (
   id  INTEGER PRIMARY KEY,
   variable  text,
   value  text,
   help_text  mediumtext
);

CREATE TABLE topic_read  (
   user_id  mediumint(8),
   topic_id  mediumint(8)
);

CREATE TABLE theme_settings (
  theme_id tinyint(225),
  variable text,
  value text
);

CREATE TABLE themes (
  theme_id INTEGER PRIMARY KEY,
  theme_name text,
  theme_author text,
  author_site text,
  author_email text,
  theme_version varchar(225),
  folder text,
  theme_visibility varchar(225),
  theme_preview text
);

CREATE TABLE ratings (
   id INTEGER PRIMARY KEY,
   rating int(1),
   user_id int(225),
   type_id int(225),
   type TEXT,
   ip TEXT
);

CREATE TABLE users  (
   id  INTEGER PRIMARY KEY,
   ssid  varchar(225),
   agent  text,
   user  text,
   pass  text,
   email  text,
   ip  text,
   regdate  int(11) default '0',
   lastlogin  text,
   membergroup  int(11) default '1',
   allow_login  int(11) default '1',
   posts  smallint(5) default '0',
   avatar  varchar(225),
   signature  text,
   location  varchar(225),
   birthday  varchar(225),
   show_email  varchar(1) default '1',
   msn  varchar(225),
   icq  varchar(225),
   yim  varchar(225),
   aim  varchar(225),
   offset  varchar(3) default '0',
   topics_page  tinyint(9) default '0',
   posts_topic  tinyint(9) default '0',
   quickreply  varchar(1) default '0',
   theme  varchar(225) default 'default',
   gender varchar(1),
   gavatar  int(1) default '0',
   gavataremail  varchar(225) default '',
   admin_menu text,
   time_online int(1)
);

CREATE TABLE user_online  (
   user_id  varchar(225) default '0',
   time_online  int(15) default '0',
   ip  varchar(40)
);

*/
?>