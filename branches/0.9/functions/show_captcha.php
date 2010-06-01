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
function show_captcha() {
	global $REGISTER_LANG;
	$return = '<tr>
				<td colspan="2"><img src="functions/securimage/securimage_show.php" id="captcha" \><br /><a title="'.$REGISTER_LANG["title_listen"].'" href="functions/securimage/securimage_play.php">
<img border="0" align="top" alt="'.$REGISTER_LANG["title_audio"].'" src="functions/securimage/images/audio_icon.gif" />
</a><a href="#" onclick="document.getElementById(\'captcha\').src = \'functions/securimage/securimage_show.php?\' + Math.random(); return false" title="'.$REGISTER_LANG["title_reload"].'"><img src="functions/securimage/images/refresh.gif" alt="'.$REGISTER_LANG["title_reload"].'" /></a></td>
		   </tr>
		   <tr>
				<td>'.$REGISTER_LANG["image_text"].'</td><td><input type="text" name="captcha" size="10" maxlength="6" class="required" /></td>
		   </tr>';
	return $return;
}
?>