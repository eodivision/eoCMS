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