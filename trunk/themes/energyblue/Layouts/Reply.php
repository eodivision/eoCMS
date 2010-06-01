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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
$topicid = $_GET['topic'];
$topicsearch = call('sql_query', "SELECT * FROM forum_topics WHERE topic_id = '$topicid'");
$topicfetch = call('sql_fetch_array',$topicsearch);
if(!isset($_SESSION['post_time']))
	$_SESSION['post_time'] = time();
if(!isset($topicid))
	$error_die[] = $REPLY_LANG["error_die"];
$title = $REPLY_LANG["title"];
$check = false;
if(!isset($_POST['smiley']))
	$_POST['smiley'] = '';
if(!isset($_POST['subject']))
	$_POST['subject'] = '';
if(isset($_POST['message']))
	$check = call('postreply', $topicid, $_POST['subject'], $_POST['message'], $_POST['token'], $_POST['smiley'], $_SESSION['post_time']);
if($check==true && !errors()) {
	unset($_SESSION['post_time']);
	if($_POST['return']) {
		if(!$user['guest'] && $user['posts_topic'] != '0' && $user['posts_topic'] != '')
			$settings['posts_topic'] = $user['posts_topic'];
	$replies = ceil($topicfetch['replies'] / $settings['posts_topic']);
	$replies = $replies + 1; //include the first post
	if($replies > 1)
		$replies = '&pages='.$replies;
	else
		$replies = '';
	call('redirect', 'index.php?act=viewtopic&id='.$topicid.$replies);
	} else
		call('redirect', 'index.php?act=viewboard&id='.$topicfetch['board_id']);
} else {
$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.populate.pack.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#reply").validate();
});</script>';
if(isset($_GET['quote'])) {
$body = '<body onLoad="InsertQuote(\'' . $_GET['quote'] . '\', \'reply\', \'\', \'post\');">';
}
else { $body =''; }
$body .= theme('title', $REPLY_LANG["theme_title"]) . theme('start_content') . '
				<form action="" method="post" id="reply"><table class="reply">
  <tr>
    <td>'.$REPLY_LANG["subject"].':</td>
    <td><input type="text" name="subject" id="subject" size="60" maxlength="80" value="'; if(isset($_POST['topic_title'])) { $body.=$_POST['topic_title']; } else { $body.='Re: ' . $topicfetch['topic_title']; } $body.='" /></td>
  </tr>
  <tr>
    <td>'.$REPLY_LANG["message"].':</td>
    <td>' . call('bbcodeform', 'message') . '<textarea rows="12" name="message" id="message" class="required">'; if(isset($_POST['message'])) { $body.=$_POST['message']; } $body.='</textarea></td>
  </tr>
  <tr><td>' . call('form_token') . '</td></tr>
  <tr id="disablesmileys">
	  	<td></td>
		<td><label for="smiley"><input type="checkbox" id="smiley" name="smiley"> '.$REPLY_LANG["disable_smileys"].'</label></td>
</tr>
  <tr>
  	<td></td>
	<td><label for="return"><input type="checkbox" id="return" name="return" /> '.$REPLY_LANG["return_to_topic"].'</label></td>
  </tr>
  <tr><td align="center" colspan="2"><input type="submit" value="'.$REPLY_LANG["btn_post_reply"].'" /></td></tr></table>
                </form>' . theme('end_content');
$sql = call('sql_query', "SELECT * FROM forum_posts WHERE topic_id = '$topicid' ORDER BY post_time DESC");
$body .= theme('title', $REPLY_LANG["topic_summary"]) . theme('start_content');
while($fetch = call('sql_fetch_array', $sql)){
$body .= '<table align="center" style="table-layout: fixed;" width="100%">
							<tr>
								<td colspan="2" align="left" class="small-text subtitlebg">
									<div style="float: right;">' . call('dateformat', $fetch['post_time']) . '</div>
									'.$REPLY_LANG["posted_by"].': ' . $fetch['name_author'] . '
								</td>
							</tr><tr>
								<td colspan="2" class="smalltext">
									<div align="right" class="smalltext"><a style="cursor: pointer;" title="'.$REPLY_LANG["title_quote"].'" onClick="InsertQuote(\'' . $fetch['id'] . '\', \'reply\', \'message\', \'post\')">'.$REPLY_LANG["insert_quote"].'</a></div>
									<div style="overflow: auto;">' . call('bbcode', $fetch['message']) . '</div>

								</td>
							</tr>
						</table>';
						}
$body .= '					</td>
				</tr>
			</table>';
}
$body .= theme('end_content');
?>