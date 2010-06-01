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
function displaycomment($page, $type){
	global $user, $settings, $head, $authid;
	//check to see if a comment is being added
	if(isset($_POST['comment']))
		$check = call('addcomment', $page, $type, $_POST['message'], $_POST['token']);
	//we need to check what the type is and if comments has been enabled
	$comment = theme('title', 'Comments').theme('start_content');
	$query = call('sql_query', "SELECT * FROM comments WHERE comment_type = '$type' AND type_id = '$page'");
	if(call('sql_num_rows', $query) == 0)
		$comment.='No Comments';
	else {
		$comment .= '<a name="comments" />';
		$i = 0;
		while($c=call('sql_fetch_array', $query)) {
			$i++;
			$comment.='<div class="comment-area'.(($i % 2 == 0) ? '1': '2').'"><div class="comment-subject"><a name="comment-'.$c['id'].'" />' . call('userprofilelink', $c['author_id']) . ' on ' . call('dateformat', $c['post_time']).'</div>';
			if(!$user['guest'] && ($c['author'] == $user['user'] && $user['delete_own_comment']) || ($user['delete_any_comment']))
				$comment.='<div class="comment-delete"><a href="'.$settings['site_url'].'/index.php?act=modcp&amp;opt=deletecomment&amp;'.$authid.'&amp;readmore=' . $page . '&amp;id=' . $c['id'] . '" title="Delete" onclick="return confirm(\'Are you sure you want to delete this comment?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/commentdelete.png" alt="Delete" /></a></div>
				<div class="comment-clear"></div>';
			$comment.='<div id="' . $c['id'] . '" class="comment-message';
			if(!$user['guest'] && ($c['author'] == $user['user'] && $user['modify_own_comment']) || ($user['modify_any_comment']))
				$comment.=' edit';
			$comment.='">' . call('bbcode', $c['message']) . '</div>
			</div>';
		}
	}
	$comment.= theme('end_content');
	if(call('sql_num_rows', $query) != 0 && !$user['guest'] && ($c['author'] == $user['user'] && $user['modify_own_comment']) || ($user['modify_any_comment'])) {
		$head .= '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.jeditable.pack.js"></script>
		<script type="text/javascript">
		<!--
		$(document).ready(function() {
		 	 $(\'.edit\').editable(\''.$settings['site_url'].'/index.php?act=ajax&m=editcomment\', {
			 type: \'textarea\',
			 indicator : \'Saving...\',
			 loadurl: \''.$settings['site_url'].'/index.php?act=ajax&m=getcomment\',
			 tooltip   : \'Click to edit and press Enter to save the edit\',
			 cancel : \'Cancel\',
			 submit: \'Save\'
		 	 })
		});
		// -->
		</script>';
	}
	if($user['post_comment']) {
		$head .= '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$("#newcomment").validate();
		});</script>';
		$comment .= theme('title', 'Post a comment').theme('start_content').'<form id="newcomment" method="post" action=""><table><tr><td>' . call('bbcodeform', 'message') . call('form_token') . '
		<textarea name="message" id="message" class="message-comment required"></textarea>' . call('form_token') . '</td></tr>
		<tr><td><input type="submit" name="comment" value="Post Comment" /></td></tr>
		</table></form>'.theme('end_content');
	}
	return $comment;
}
?>