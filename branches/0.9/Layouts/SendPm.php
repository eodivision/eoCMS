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
if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

if($user['guest']) {
	$error_die[] = $SENDPM_LANG["error_die"];
}
if (!$user['send_pms']) {
	$error_die[] = $SENDPM_LANG["error_die_perm"];
	return false;
}
$title = $SENDPM_LANG["title"];
$check = false;
if(!isset($_GET['pm'])) $_GET['pm'] = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(isset($_POST['to'])) {
	$check = call('sendpm', $_POST['to'], $_POST['title'], $_POST['message'], $_POST['token'], $_POST['draft'], $_GET['id']);
}elseif (isset($_GET['opt']) && $_GET['opt']=='edit'){
	$query = call('sql_query', "SELECT * FROM pm WHERE id='" . $_GET['id'] . "'");
	if($query){
		$fetch = call('sql_fetch_array',$query);
		$_POST['draft'] = $_GET['id'];
		$_POST['to'] = $fetch['to_send'];
		$_POST['title'] = $fetch['title'];
		$_POST['message'] = $fetch['message'];
	} else {
		$check = true;
	}
}
if($check==true && !errors()) {
	header("Location: index.php?act=pm");
} else {
	if($_GET['pm']!='') {
		$query = call('sql_query', "SELECT * FROM pm WHERE id='" . $_GET['pm'] . "'");
		$fetch = call('sql_fetch_array',$query);
	}
	$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.populate.pack.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$("#sendpm").validate();
	$("#save_draft").click(function() {
		if($("#draft").val()=="") $("#draft").val("save");
		$("#sendpm").submit();
	});
});
</script>';
	$body = theme('title', $SENDPM_LANG["theme_title"]) . theme('start_content') . '
<form action="" method="post" id="sendpm"><table>
  <tr>
    <td colspan=2>'.$SENDPM_LANG["h_to"].'</a></td>
  </tr>
  <tr>
    <td>'.$SENDPM_LANG["to"].':</td>
    <td><input type="text" name="to" class="required" id="to"'; if(isset($_GET['userid'])) { $fetch2 = call('displayuserinfo', $_GET['userid']); $body .= ' value="' . $fetch2['user'] . '">'; } elseif(isset($_POST['to'])) {  $body .= ' value="' . $_POST['to'] . '">'; } $body .= ' <a href="javascript:;" onclick="window.open(\''.$settings['site_url'].'/index.php?act=finduser&area=to\',\'\',\'width=400,height=300\')" class="help"></a></td>
  </tr>
  <tr>
    <td>'.$SENDPM_LANG["subject"].':</td>
    <td><input type="text" name="title" class="input" id="title" maxlength="80" value="'; if($_GET['pm']!='') { $body.= (!preg_match("/RE/", $fetch['title'])? 'RE:' . $fetch['title'] : $fetch['title']); } elseif(isset($_POST['title'])) { $body.= $_POST['title']; } $body.='" class="required"></td>
  </tr>
  <tr>
    <td>'.$SENDPM_LANG["message"].':</td>
    <td>'.call('form_token').call('bbcodeform', 'message').'<textarea rows="12" cols="40" name="message" id="message" type="text" class="required">'; if(isset($_POST['message'])) $body.= $_POST['message']; $body.='</textarea></td>
  </tr>
</table>
<p align="center"><input type="submit" value=" '.$SENDPM_LANG["btn_send"].' "><input type="hidden" name="draft" id="draft" value=""><input type="button" id="save_draft" value=" '.$SENDPM_LANG["btn_send_later"].' "></p>
</form>
' . theme('end_content');
	if($_GET['pm']!='') {
		if(call('sql_num_rows', $query)!=0) {
			$body.='
<table cellspacing="0" cellpadding="0" width="92%" align="center" border="0">
  <tr>
    <td><table width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr class="titlebg">
	  <td colspan="2">'.$SENDPM_LANG["pm_summary"].'</td>
	</tr>
      </table>
      <table width="100%" cellspacing="0" cellpadding="0" align="center" style="table-layout: fixed;" class="content">
	<tr>
	  <td colspan="2" align="left" class="smalltext subtitlebg"><div style="float: right;">' . call('dateformat', $fetch['time_sent']) . '</div>
		'.$SENDPM_LANG["posted_by"].': ' . call('userprofilelink', $fetch['sender']) . '</td>
	</tr>
	<tr>
	  <td colspan="2" class="smalltext" width="100%"><div align="right" class="smalltext"><a style="cursor: pointer;" title="'.$SENDPM_LANG["t_quote"].'" onclick="InsertQuote(\'' . $fetch['id'] . '\', \'sendpm\', \'message\', \'pm\')">'.$SENDPM_LANG["insert_quote"].'</a></div>
		<div>' . call('bbcode', $fetch['message']) . '</div></td>
	</tr>
      </table></td>
  </tr>
</table>';
		}
	}
}
?>