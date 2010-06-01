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

$theme['title'] = $EDITPOST_LANG["title"];
$fetch = call('get_post', $_GET['id']);
$check = false;
if(isset($_POST['message']))
	$check = call('edit_post', $_GET['id'], $_POST['subject'], $_POST['message'], $_POST['token']);
if($check==true && !errors())
	call('redirect', 'index.php?act=viewtopic&id='.$fetch['topic_id']);
else {
	$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
	$(document).ready(function() {
		$("#editpost").validate();
	});
	</script>';
	$theme['body'] = theme('title', $EDITPOST_LANG["theme_title"]) . theme('start_content') . '
			<form action="" method="post" id="editpost"><table>
  <tr>
    <td>'.$EDITPOST_LANG["subject"].':</td>
    <td><input type="text" name="subject" class="required" id="subject" maxlength="80" value="'.(errors() ? $_POST['subject'] : $fetch['subject']).'" /></td>
  </tr>
  <tr>
    <td>'.$EDITPOST_LANG["message"].':</td>
    <td>' . call('bbcodeform', 'message') . '<textarea rows="12" cols="40" name="message" id="message" class="required">'.(errors() ? $_POST['message'] : $fetch['message']).'</textarea></td>
  </tr><tr><td>' . call('form_token') . '</td></tr>
  <tr>
<td colspan="2" align="center"><input type="submit" value="'.$EDITPOST_LANG["btn_update"].'" /></td>
</tr>
                </table></form>' . theme('end_content');
}
?>