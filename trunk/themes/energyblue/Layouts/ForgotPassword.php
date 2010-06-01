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

$title = $FORGETPASSWORD_LANG["title"];
if($_POST) {
$check = call('forgotpassword', $_POST['email']);
}
else { $check = false; }
if($check==true && !errors()) {
$body = $FORGETPASSWORD_LANG["update"];
} else {
$body = theme('title', $FORGETPASSWORD_LANG["theme_title"]) . theme('start_content') . '<table style="text-align: center;" align="center"><form action="" method="post">
<tr>
	  <td>'.$FORGETPASSWORD_LANG["body_content"].'</td>
</tr>
<tr>
	<td><input type="text" name="email" value="'; if(errors()) { $body.= $_POST['email']; } $body .='" /></td></tr>
<tr>
	<td><input type="submit" value="'.$FORGETPASSWORD_LANG["btn_submit"].'" /></form></table>' . theme('end_content');
}
?>