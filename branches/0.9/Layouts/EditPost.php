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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$title = $EDITPOST_LANG["title"];
$fetch = call('get_post', $_GET['id']);
$check = false;
if(isset($_POST['message']))
	$check = call('edit_post', $_GET['id'], $_POST['subject'], $_POST['message'], $_POST['token']);
if($check==true && !errors())
	header("Location: index.php?act=viewtopic&id=" . $fetch['topic_id']);
else {
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
	$(document).ready(function() {
		$("#editpost").validate();
	});
	</script>';
	$body = theme('title', $EDITPOST_LANG["theme_title"]) . theme('start_content') . '
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