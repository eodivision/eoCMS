<?php die(); ?>
a:16:{s:22:"SELECT * FROM settings";a:1:{i:0;s:8:"settings";}s:52:"SELECT theme_id FROM themes WHERE folder = 'default'";a:1:{i:0;s:6:"themes";}s:49:"SELECT * FROM theme_settings WHERE theme_id = '1'";a:1:{i:0;s:14:"theme_settings";}s:61:"SELECT * FROM panels WHERE online="1" ORDER BY item_order ASC";a:1:{i:0;s:6:"panels";}s:72:"SELECT folder, everypage, layout_include FROM plugins WHERE active = '1'";a:1:{i:0;s:7:"plugins";}s:45:"SELECT membergroup_id, name FROM membergroups";a:1:{i:0;s:12:"membergroups";}s:19:"SELECT * FROM pages";a:1:{i:0;s:5:"pages";}s:42:"SELECT * FROM menu ORDER BY item_order ASC";a:1:{i:0;s:4:"menu";}s:52:"SELECT * FROM permissions WHERE membergroup_id = '1'";a:1:{i:0;s:11:"permissions";}s:18:"SELECT * FROM bans";a:1:{i:0;s:4:"bans";}s:54:"SELECT * FROM forum_categories ORDER BY item_order ASC";a:1:{i:0;s:16:"forum_categories";}s:44:"SELECT * FROM panels ORDER BY item_order ASC";a:1:{i:0;s:6:"panels";}s:20:"SELECT * FROM themes";a:1:{i:0;s:6:"themes";}s:566:"SELECT b.id, b.board_name, t.replies, b.post_group, b.visible, t.topic_id, t.topic_title, t.topic_author, t.date_created, t.locked, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, t.views, t.sticky, p.message FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply LEFT OUTER JOIN board_permissions bp ON bp.board_id = b.id WHERE b.id = '1' AND p.id=t.latest_reply AND t.sticky = '1' AND bp.variable = 'view' AND bp.value = '2' GROUP BY t.topic_id ORDER BY p.post_time DESC";a:4:{i:0;s:12:"forum_boards";i:1;s:12:"forum_topics";i:2;s:11:"forum_posts";i:3;s:17:"board_permissions";}s:578:"SELECT b.id, b.board_name, t.replies, b.post_group, b.visible, t.topic_id, t.topic_title, t.topic_author, t.date_created, t.locked, t.latest_reply, p.post_time, p.name_author, p.author_id, p.id, t.views, t.sticky, p.message FROM forum_boards AS b LEFT OUTER JOIN forum_topics AS t ON b.id=t.board_id LEFT OUTER JOIN forum_posts AS p ON p.id = t.latest_reply LEFT OUTER JOIN board_permissions bp ON bp.board_id = b.id WHERE b.id = '1' AND p.id=t.latest_reply AND t.sticky = '0' AND bp.variable = 'view' AND bp.value = '2' GROUP BY t.topic_id ORDER BY p.post_time DESC LIMIT 0, 20";a:4:{i:0;s:12:"forum_boards";i:1;s:12:"forum_topics";i:2;s:11:"forum_posts";i:3;s:17:"board_permissions";}s:198:"SELECT p.topic_id, p.message FROM forum_posts AS p LEFT JOIN forum_topics AS t ON p.topic_id = t.topic_id WHERE p.board_id = '1' AND t.topic_id IN(Array,Array) GROUP BY p.topic_id ORDER BY post_time";a:2:{i:0;s:11:"forum_posts";i:1;s:4:"JOIN";}}