<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

   Added Language - 29/03/09 - Paul Wratt
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$title = $EDITPROFILELOOK_LANG["title"];
$check = false;
if($user['guest'])
	$error_die[] = $EDITPROFILELOOK_LANG["error_die"];
if(isset($_POST['currentpassword']))
	$check = call('updatelook', $user['id'], $_POST['topics_page'], $_POST['posts_topic'], md5($_POST['currentpassword']), $_POST['quickreply'], $_POST['theme'], $_POST['token']);
if($check==true && !errors()) {
	$head = '<META HTTP-EQUIV="refresh" content="3;URL='.$settings['site_url'].'/index.php?act=editlook">';
	$body = theme('title', $EDITPROFILELOOK_LANG["theme_title"]) . theme('start_content') . $EDITPROFILELOOK_LANG["body_content"] . theme('end_content');
} else {
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#editlook").validate({
		rules: {
			topics_page: {
				min: 5
			},
			posts_topic: {
				min: 5
			}
		}
	});
	$("a[id=site_preview]").attr("href", "index.php?act=editlook&theme="+$("#theme").val() +"&'.$authid.'");
	$("#theme").change(function() {
		$("a[id=site_preview]").attr("href", "index.php?act=editlook&theme="+$("#theme").val()+"&'.$authid.'");
		$("#theme_thumb]").attr("src", "theme/"+$("#theme").val()+"/images/"+$("#theme").val()+"_preview.jpg");
	});
});
</script>';
	$body = theme('title', $EDITPROFILELOOK_LANG["theme_title_edit"]) . theme('start_content') . '<form action="" method="post" id="editlook">
<table>
	<tr>
		<td colspan="2">'.theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=editprofile">Edit Profile</a>', '<a href="'.$settings['site_url'].'/index.php?act=editlook">Look &amp; Layout</a>')).'</td>
	</tr>
  	<tr>
    	<td>' . $EDITPROFILELOOK_LANG["topics_per_page"] . ':<br />
		<span class="small-text">' . $EDITPROFILELOOK_LANG["default"] . ': ' . $settings['topics_page'] . '</span></td>
    	<td><input type="text" name="topics_page" id="topics_page" value="';
	if(!errors() && isset($user['topics_page']) && $user['topics_page']!='0') {
		$body .= $user['topics_page'];
	} else {
		if(isset($_POST['topics_page'])) $body .= $_POST['topics_page'];
	}
	$body .= '" /></td>
  </tr>
  <tr>
    <td>' . $EDITPROFILELOOK_LANG["posts_per_page"] . ':<br />
	<span class="small-text">' . $EDITPROFILELOOK_LANG["default"] . ': ' . $settings['posts_topic'] . '</span></td>
    <td><input type="text" name="posts_topic" id="posts_topic" value="';
	if(!errors() && isset($user['posts_topic']) && $user['posts_topic']!='0') {
		$body .= $user['posts_topic'];
	} else {
		if(isset($_POST['posts_topic'])) $body .= $_POST['posts_topic'];
	}
	$body .= '" /></td>
  </tr>
  <tr>
    <td>' . $EDITPROFILELOOK_LANG["show_quick_reply"] . '?</td>
    <td><input type="radio" name="quickreply" value="1"';
	if($user['quickreply']=='1') {
		$body .='checked';
	}
	$body .= '> ' . $EDITPROFILELOOK_LANG["yes"] . ' <input type="radio" name="quickreply" value="0"';
	if($user['quickreply'] =='0') {
		$body .='checked';
	}
	$body .='> ' . $EDITPROFILELOOK_LANG["no"] . '</td>
  </tr>
  <tr>
    <td>' . $EDITPROFILELOOK_LANG["choose_theme"] . ':</td>
    <td><select name="theme" id="theme" class="required">';
# DEV: make into function, need to place "default" at top and Human Readble Sort
	$themes = call('sql_query', "SELECT * FROM themes", 'cache');
	if(count($themes)==0) 
		$themes[] = array('theme_visibility'=>'1,2,3,4,5', 'theme_name'=>'default', 'folder'=>'default');
	$thumb = $themes[0]['folder'];
	foreach($themes as $r) {
		if(call('visiblecheck', $user['membergroup_id'], $r['theme_visibility'])) {
			$body .= '
	<option value="'.$r['folder'].'"';
			if ($r['theme_name']==$settings['site_theme']) {
				$body .= ' selected="selected"';
				$thumb = $r['folder'];
			}
			$body .= '>'.$r['theme_name'].'</option>';
		}
	}
	$body.='</select>  <a id="site_preview" href="">' . $EDITPROFILELOOK_LANG["preview"] . '</a><br><img id="theme_thumb" src="'.$settings['site_url'].'/themes/' . $thumb . '/images/' . $thumb . '_preview.jpg" onError="this.style.display=\'none\';" /></td>
  </tr>
  <tr>
    <td>' . $EDITPROFILELOOK_LANG["current_password"] . ': <br />
	<span class="small-text">' . $EDITPROFILELOOK_LANG["required"] . '</span></td>
    <td><input name="currentpassword" type="password" class="required"/></td>
  </tr>
  <tr>
    <td colsapn="2">' . call('form_token') . '<input name="update" type="submit" value=" ' . $EDITPROFILELOOK_LANG["btn_update"] . ' "/></td>
  </tr>
</table>
</form>' . theme('end_content');
}
?>