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
$title = 'Bans';
if(!isset($_GET['type']) || $_GET['type'] == null) {
	if($_POST)
		$check = call('addban', $_POST['ip'], $_POST['reason'], $_POST['type']);
	else
		$check = false;
	if($check==true && !errors())
		$_SESSION['update'] = 'The ban has been added';
	$query = call('sql_query', "SELECT * FROM bans");
	$bans = array();
	while($r = call('sql_fetch_assoc', $query)) {
		$bans[$r['id']] = $r;
	}
	$body ='<div id="tooltip"></div><form action="" method="post"><div class="admin-panel">'.theme('title', 'New Ban').theme('start_content').'<table class="admin-table">	
  <tr>
  	<td>Ip Address:(For a range enter a partial IP for example: 127.0)</td>
  </tr>
  <tr>
    <td><input type="text" name="ip" value="'; if($check == false && isset($_POST['ip'])) { $body.= $_POST['ip']; } $body .='" /></td>
  </tr>
    <tr>
  	<td>Ip Type:</td>
  </tr>
    <tr>
    <td><select name="type">
	<option value="ip"'; if($check == false && isset($_POST['ip_type']) && $_POST['ip_type'] == 'ip') { $body.= 'selected="selected"'; } $body .='>Address</option>
	<option value="range"'; if($check == false && isset($_POST['ip_type']) && $_POST['ip_type'] == 'range') { $body.= 'selected="selected"'; } $body .='>Range</option></td>
  </tr>
  <tr>
  	<td>Reason:</td>
  </tr>
  <tr>
    <td colspan="2"><textarea name="reason" cols="36" rows="2">'; if($check == false && isset($_POST['reason'])) { $body.= $_POST['reason']; } $body .='</textarea></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Add Ban" name="Submit" /><input name="Reset" type="reset" value="Reset" /></td>
  </tr>
</table>
'.theme('end_content').'</div></form><br />
<div class="admin-panel2">'.theme('title', 'Current Bans').theme('start_content').'<table class="admin-table2">';
	if(call('sql_num_rows', $query) == 0)
		$body .= '<tr><td>Nobody banned</td></tr>';
	else {
        foreach ($bans as $p) {
			$fetch = call('userprofilelink', $p['created_by']);
            $body.='<tr>
						<td width="10%">' . $p['ip'] . '' . $p['ip_range'] . '<br />
              <span class="small-text">banned by ' . $fetch . ' </span></td>
			  <td>'.$p['reason'].'</td>
            <td width="10%"><a href="'.$settings['site_url'].'/index.php?act=admin&opt=bans&type=edit&id=' . $p['id'] . '&amp;'.$authid.'" title="Edit"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/save.png" alt="Edit" /></a> | <a href="'.$settings['site_url'].'/index.php?act=admin&opt=bans&type=delete&id=' . $p['id'] . '&amp;'.$authid.'" title="Delete" onclick="return confirm(\'Are you sure you want un-ban this ip?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a></td>
          </tr>';
		}
	}
	$body .='</table>'.theme('end_content').'</div>';
}
if(isset($_GET['type']) && $_GET['type'] == 'edit') {
	if($_POST)
		$check = call('editban', $_POST['ip'], $_POST['reason'], $_POST['type'], $_GET['id']);
	else
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The ban has been updated';
		session_write_close();
		header("Location: index.php?act=admin&opt=bans&".$authid);
	}
	$sql = call('sql_query', "SELECT * FROM bans WHERE id = '" . $_GET['id'] . "'");
	$r = call('sql_fetch_assoc', $sql);
	$body =theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=bans&amp;'.$authid.'">Bans</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=bans&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['ip'].$r['ip_range'].'</a>')).'<form action="" method="post"><div class="admin-panel">'.theme('title', 'Edit Ban').theme('start_content').'<table class="admin-table">
  <tr>
    <td><input type="text" name="ip" value="'; if(errors()) { $body.= $_POST['ip']; } elseif(!empty($r['ip'])) { $body .= $r['ip']; } elseif(!empty($r['ip_range'])) { $body .= $r['ip_range']; } $body .='" /></td>
  </tr>
  <tr>
  	<td>Ip Type:</td>
  </tr>
  <tr>
    <td><select name="type">
	<option value="ip"'; if(errors() && $_POST['ip_type'] == 'ip') { $body.= 'selected="selected"'; } elseif(!empty($r['ip'])) { $body.= 'selected="selected"'; } $body .='>Address</option>
	<option value="range"'; if(errors() && $_POST['ip_type'] == 'range') { $body.= 'selected="selected"'; } elseif(!empty($r['ip_range'])) { $body.= 'selected="selected"'; } $body .='>Range</option></td>
  </tr>
  <tr>
  	<td>Reason:</td>
  </tr>
  <tr>
    <td colspan="2"><textarea name="reason" cols="36" rows="2">'; if(errors()) { $body.= $_POST['reason']; } else { $body .= $r['reason']; } $body .='</textarea></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Update Ban" name="Submit" /></td>
  </tr>
</table>
'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
	$check = call('deleteban', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The ban has been lifted';
		session_write_close();
		header("Location: index.php?act=admin&opt=bans&".$authid);
	}
}
?>