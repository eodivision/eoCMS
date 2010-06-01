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
$title = 'Site Settings';
if($_POST)
	$check = call('sitesettings', $_POST['registration'], $_POST['reg_approval'], $_POST['email'], $_POST['mail'], $_POST['host'], $_POST['username'], $_POST['password'], $_POST['mode'], $_POST['message'], $_POST['captcha'], $_POST['topics_per_page'], $_POST['posts_per_page'], $_POST['site_name'], $_POST['tos']);
else 
	$check = false;
if($check==true && !errors()) {
	$_SESSION['update'] = 'The site settings have been updated';
	session_write_close();
	header("Location: index.php?act=admin&opt=sitesettings&".$authid);
}

if(isset($_GET['type']) && $_GET['type'] == 'clearcache') {
   call('clearcache');
}
$settingquery = call('sql_query', "SELECT variable, help_text FROM settings");
      while ($settingrow = call('sql_fetch_array',$settingquery)) {
          $settingshelp[$settingrow['variable']] = $settingrow['help_text'];
      }
$body ='<form action="" method="post"><div class="admin-panel">'.theme('title', 'Manage Site Settings').theme('start_content').'<table class="admin-table">
  <tr>
  	<td>Site Name: (Will appear in the title)</td>
  	<td><input type="text" name="site_name" class="required" value="'; if(errors()) { $body.= $_POST['site_name']; } else { $body.= $settings['site_name']; }$body .='" /></td>
  </tr>
    <tr>
  	<td>Default number of topics per page in a board:</td>
  	<td><input type="text" name="topics_per_page" class="digits" value="'; if(errors()) { $body.= $_POST['topics_per_page']; } else { $body.= $settings['topics_page']; }$body .='" /></td>
  </tr>
    <tr>
  	<td>Default number of posts per page in a topic:</td>
  	<td><input type="text" name="posts_per_page" class="digits" value="'; if(errors()) { $body.= $_POST['posts_per_page']; } else { $body.= $settings['posts_topic']; }$body .='" /></td>
  </tr>
  <tr>
  	<td>Registration:</td>
    <td><select name="registration">
	<option value="on" '; if(errors() && $_POST['registration'] =='on') { $body.='selected="selected"'; } elseif($settings['registration'] =='on') { $body.= 'selected="selected"'; } $body.='>On</option>
	<option value="off" '; if(errors() && $_POST['registration'] =='off') { $body.='selected="selected"'; } elseif($settings['registration'] =='off') { $body.= 'selected="selected"'; } $body.='>Off</option>
	</select></td>
  </tr>
  <tr>
  	<td>Register Approval Type:<span class="help" title="' . $settingshelp['register_approval'] . '"></span></td>
  	<td><select name="reg_approval"><option value="admin" '; if(errors() && $_POST['reg_approval'] =='admin') { $body.='selected="selected"'; } elseif($settings['register_approval'] =='admin') { $body.= 'selected="selected"'; } $body.='>Admin Activation</option>
  <option value="email" '; if(errors() && $_POST['reg_approval'] =='email') { $body.='selected="selected"'; } elseif($settings['register_approval'] =='email') { $body.= 'selected="selected"'; } $body.='>Email Activation</option>
  <option value="none" '; if(errors() && $_POST['reg_approval'] =='none') { $body.='selected="selected"'; } elseif($settings['register_approval'] =='none') { $body.= 'selected="selected"'; } $body.='>None</option>
</select></td>
  </tr>
  <tr>
  	<td>Captcha:<span class="help" title="' . $settingshelp['register_captcha'] . /*$captcha . */'"></span></td>
    <td><select name="captcha">
	<option value="on" '; if(errors() && $_POST['captcha'] =='on') { $body.='selected="selected"'; } elseif($settings['register_captcha'] =='on') { $body.= 'selected="selected"'; } $body.='>On</option>
	<option value="off" '; if(errors() && $_POST['captcha'] =='off') { $body.='selected="selected"'; } elseif($settings['register_captcha'] =='off') { $body.= 'selected="selected"'; } $body.='>Off</option>
	</select></td>
  </tr>
  <tr>
  	<td>Terms of Service:</td>
	<td><textarea name="tos" rows="5" cols="30">'; if(errors()) { $body.= $_POST['tos']; } else { $body.= $settings['tos']; } $body .='</textarea></td>
  </tr>
    <tr>
		<td class="admin-subtitlebg" colspan="2">Email</td>
	</tr>
	<tr>
  <td>Email:</td>
	<td><input type="text" name="email" value="'; if(errors()) { $body.= $_POST['email']; } else { $body.= $settings['email']; }$body .='" /></td></tr>
	  <tr>
  <tr>
  <td>Type:<span class="help" title="' . $settingshelp['mail'] . /*$mailtest . */'"></span></td>
	<td><select name="mail"><option value="sendmail" '; if(errors() && $_POST['mail'] =='sendmail') { $body.='selected="selected"'; } elseif($settings['mail'] =='sendmail') { $body.= 'selected="selected"'; } $body.='>SendMail</option>
  <option value="smtp" '; if(errors() && $_POST['mail'] =='smtp') { $body.='selected="selected"'; } elseif($settings['mail'] =='smtp') { $body.= 'selected="selected"'; } $body.='>SMTP</option>
</select></td></tr>
    <tr>
		<td colspan="2" class="admin-subtitlebg">SMTP</td>
	</tr>
	  <tr>
  <td>Host:</td>
	<td><input type="text" name="host" value="'; if(errors()) { $body.= $_POST['host']; } else { $body.= $settings['smtp_host']; }$body .='" /></td></tr>
	  <tr>
  <td>Username:</td>
	<td><input type="text" name="username" value="'; if(errors()) { $body.= $_POST['username']; } else { $body.= $settings['smtp_username']; }$body .='" /></td></tr>
	  <tr>
  <td>Password:</td>
	<td><input type="password" name="password" /></td>
</tr>
<tr><td colspan="2" class="admin-subtitlebg">Cache</td></tr>
<tr><td colspan="2" >&nbsp;</td></tr>
    <tr><td colspan="2" align="center"><div class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=sitesettings&amp;type=clearcache&amp;'.$authid.'">Clear Cache</a></div></td></tr>
    <tr><td colspan="2" >&nbsp;</td></tr>
	<tr><td colspan="2" class="admin-subtitlebg">Maintenance</td></tr>
	<tr><td>Maintenance Mode:<span class="help" title="' . $settingshelp['maintenance_mode'] . '"></span></td><td><select name="mode"><option value="off" '; if(errors() && $_POST['mode'] =='off') { $body.='selected="selected"'; } elseif($settings['maintenance_mode'] =='off') { $body.= 'selected="selected"'; } $body.='>Off</option>
  <option value="on" '; if(errors() && $_POST['mode'] =='on') { $body.='selected="selected"'; } elseif($settings['maintenance_mode'] =='on') { $body.= 'selected="selected"'; } $body.='>On</option>
</select></td>
</tr>
<tr><td>Maintenance Message:</td><td><textarea name="message" rows="5" cols="30">'; if(errors()) { $body.= $_POST['message']; } else { $body.= $settings['maintenance_message']; } $body .='</textarea></td>
	</tr>
	  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Save Settings" name="Submit" /><input name="Reset" type="reset" value="Reset" /></td>
  </tr>
</table>'.theme('end_content').'</div></form>';
?>