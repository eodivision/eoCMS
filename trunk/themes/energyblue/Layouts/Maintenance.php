<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
$check = false;
if(!$settings['maintenance_mode'])
	$check = false;
$title = $MAINTENANCE_LANG["title"];
if($_POST)
	$check = call('login', $_POST['username'], md5($_POST['password']), $_POST['remember'], $_POST['token']);
if($check==true && !errors())
	call('redirect', 'index.php');
else {
	$body = '<table class="content-maintenance">
  <tr class="logo">
    <td align="center"><img src="' . $settings['logo'] . '" alt="logo" /></td>
  </tr>
  <tr>
    <td align="center" class="maintenance-message">' . nl2br($settings['maintenance_message']) . '</td>
  </tr>
  <tr class="maintenance-login">
    <td align="center"><form action="" method="post">
    <table>
    <tr><td>'.$MAINTENANCE_LANG["username"].':</td><td><input name="username" type="text" size="8" class="input" value="'.(isset($_POST['username']) ? $_POST['username'] : '').'"/></td>
<td>'.$MAINTENANCE_LANG["password"].':</td><td><input name="password" type="password" size="8" class="input" value=""/></td>
<td>'.$MAINTENANCE_LANG["remember_me"].' <input type="checkbox" name="remember" checked="yes">' . call('form_token') . '</td><td colsapn="2"><input name="login" type="submit" value="'.$MAINTENANCE_LANG["btn_login"].'"/></td></tr>
    </form>
    </table></td>
  </tr>
</table>';
}
?>