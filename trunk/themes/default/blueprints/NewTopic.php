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
if(!isset($_GET['board']))
	$error_die[] = $NEWTOPIC_LANG["error_die"];
call('getforumpermission', $_GET['board']);
$check = false;
$theme['title'] = $NEWTOPIC_LANG["title"];
if(isset($_POST['submit'])) {
	if(!isset($_POST['smiley']))
		$_POST['smiley'] = '';
	$check = call('newtopic', $_GET['board'], $_POST['topic_title'], $_POST['message'], $_POST['token'], $_POST['smiley']);
}
if($check==true && !errors())
	call('redirect', 'index.php?act=viewboard&id='.$_GET['board']);
else {
	$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
	$(document).ready(function() {
		$("#newtopic").validate();
	});</script>';
	$theme['body'] = theme('title', $NEWTOPIC_LANG["theme_title"]) . theme('start_content') . '
	<form action="" method="post" id="newtopic"><table class="newtopic">
	  <tr>
		<td>'.$NEWTOPIC_LANG["topic_title"].':</td>
		<td><input type="text" name="topic_title" class="required" id="topic_title" size="60" maxlength="80" value="'.(isset($_POST['topic_title']) ? $_POST['topic_title'] : '').'"></td>
	  </tr>
	  <tr>
		<td>'.$NEWTOPIC_LANG["message"].':</td>
		<td>' . call('form_token') . call('bbcodeform', 'message') . '<textarea rows="12" cols="40" name="message" id="message" type="text" class="required">'.(isset($_POST['message']) ? $_POST['message'] : '').'</textarea></td>
	  </tr>
	   <tr>
	  	<td></td>
		<td id="disablesmileys"><label for="smiley"><input type="checkbox" id="smiley" name="smiley"> '.$NEWTOPIC_LANG["disable_smileys"].'</label></td>
	  </tr>
	  <tr>
	  	<td colspan="2" align="center"><input type="submit" value="'.$NEWTOPIC_LANG["btn_post_topic"].'" name="submit"></td>
	  </tr>
	  </table>
					</form>' . theme('end_content');
}
?>