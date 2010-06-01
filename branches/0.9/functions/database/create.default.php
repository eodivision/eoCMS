<?php
/*
INSERT INTO {PREFIX}users (user,pass,email,ip,regdate,lastlogin, membergroup) VALUES('{USERNAME}', '{PASSWORD}', '{EMAIL}', '{VISTOR_IP}', '{TIME}', 'Never', '4');

INSERT INTO {PREFIX}membergroups (membergroup_id, name, image) VALUES (1, '{guest}', '');
INSERT INTO {PREFIX}membergroups (membergroup_id, name, image) VALUES (2, '{member}', 'member.gif');
INSERT INTO {PREFIX}membergroups (membergroup_id, name, image) VALUES (3, '{global_moderator}', 'gmod.gif');
INSERT INTO {PREFIX}membergroups (membergroup_id, name, image) VALUES (4, '{admin}', 'admin.gif');

INSERT INTO {PREFIX}menu (id, name, link, item_order, rank) VALUES (1, '{forum}', '?act=forum', '1', '1,2,3,4');
INSERT INTO {PREFIX}menu (id, name, link, item_order, rank, authid) VALUES (2, '{admin}', '?act=admin', '2', '4', '1');

INSERT INTO {PREFIX}pages (id, pagename, content) VALUES (1, '{home}', '{home_content}');

INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'all_permissions', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'modify_own_posts', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'modify_any_posts', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'delete_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'lock_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'lock_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'move_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'move_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'sticky_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'sticky_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'edit_profile', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'have_signature', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'have_avatar', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'read_pms', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'send_pms', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'admin_panel', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'javascript', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'maintenance_access', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'all_permissions', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'modify_own_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'modify_any_posts', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'delete_own_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'delete_any_posts', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'delete_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'delete_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'lock_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'lock_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'move_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'move_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'sticky_own_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'sticky_any_topic', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'edit_profile', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'have_signature', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'have_avatar', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'read_pms', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'send_pms', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'admin_panel', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'javascript', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'maintenance_access', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'all_permissions', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'modify_own_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'modify_any_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'delete_own_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'delete_any_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'delete_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'delete_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'lock_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'lock_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'move_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'move_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'sticky_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'sticky_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'edit_profile', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'have_signature', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'have_avatar', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'read_pms', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'send_pms', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'admin_panel', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'javascript', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'maintenance_access', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'read_pms', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'have_avatar', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'have_signature', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'edit_profile', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'sticky_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'sticky_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'move_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'move_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'lock_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'lock_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'delete_any_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'delete_own_topic', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'delete_any_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'delete_own_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'modify_any_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'modify_own_posts', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'all_permissions', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'maintenance_access', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'javascript', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'admin_panel', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'send_pms', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'modify_own_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'modify_any_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'delete_any_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'delete_own_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'post_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'modify_own_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'modify_any_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'delete_any_comment', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'delete_own_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'post_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'modify_own_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'modify_any_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'delete_any_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'delete_own_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'post_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'modify_own_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'modify_any_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'delete_any_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'delete_own_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'post_comment', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'post_rating', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'post_rating', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'post_rating', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'post_rating', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'track_ip', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'track_ip', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'track_ip', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'track_ip', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (1, 'multi-moderate', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (2, 'multi-moderate', 0);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (3, 'multi-moderate', 1);
INSERT INTO {PREFIX}permissions (membergroup_id, variable, value) VALUES (4, 'multi-moderate', 1);

INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (1, 'registration', 'on', '{h_reg}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (4, 'site_theme', 'default', '');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (5, 'site_name', '{SITENAME}', '{h_sitename}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (6, 'site_url', '{URL}', '{h_url}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (7, 'register_approval', 'email', '{h_approval}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (8, 'mail', '', '{h_test_email}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (9, 'smtp_host', '', '');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (10, 'smtp_username', '', '');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (11, 'smtp_password', '', '');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (12, 'email', '', '{h_email}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (22, 'maintenance_mode', '0', '{h_maintenance}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (23, 'maintenance_message', '{maint_message}', '{h_maint_message}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (24, 'topics_page', '20', '{h_topics}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (25, 'posts_topic', '20', '{h_posts}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (26, 'avatar_height', '200', '{h_avatar_height}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (27, 'avatar_width', '200', '{h_avatar_width}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (28, 'version', '0.9.0', '{h_version}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (29, 'register_captcha', 'on', '{h_captcha}');
INSERT INTO {PREFIX}settings (id, variable, value, help_text) VALUES (30, 'tos', '', '');

INSERT INTO `themes` (`theme_id`, `theme_name`, `theme_author`, `author_site`, `author_email`, `theme_version`, `folder`, `theme_visibility`, `theme_preview`) VALUES
(1, 'default', 'confuser', 'http://eocms.com', '', '1', 'default', '1,2,3,4', 'default_preview.jpg');

INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(1, 'cross.png', 'Cross', ':cross:');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(2, 'sad.png', 'Sad', ':(');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(3, 'happy.png', 'Happy', ':&#039;)');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(4, 'cry.png', 'Cry', ':&#039;(');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(5, 'mad.png', 'Mad', '-.-');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(6, 'tongue.png', 'Tongue', ':P');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(7, 'smile.png', 'Smile', ':)');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(8, 'surprised.png', 'Surprised', ':O');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(9, 'biggrin.png', 'Grin', ':D');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(10, 'confused.png', 'Confused', ':confused:');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(11, 'cool.png', 'Cool', '8)');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(12, 'tick.png', 'Tick', ':tick:');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(13, 'angry.png', 'Angry', ':@');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(14, 'sarcasticsmile.png', 'Sarcastic Smile', ':sarcastic:');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(15, 'ssmile.png', ':S', ':S');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(16, 'rolleyes.png', 'Roll Eyes', '8-)');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(17, 'mrgreen.png', 'Mr Green', ':mrgreen:');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(18, 'twisted.png', 'Twisted', ':twisted:');
INSERT INTO {PREFIX}emoticons (id, image, alt, code) VALUES(19, 'wink.png', 'Wink', ';)');

*/
?>