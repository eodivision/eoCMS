<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$title = 'Users';
$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.populate.pack.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#users").validate({rules: {
		password: {
			required: true,
			minlength: 6
		},
		password_confirm: {
			required: true,
			minlength: 6,
			equalTo: "#password"
		},
		email: {
			required: true,
			email: true
		}}});
		$("#select_all").click(function() {
			var checked_status = this.checked;
			$("input[name=\'users[]\']").each(function() {
				this.checked = checked_status;
			});
		});
	$("#checked").change(function() {
		$("#withselected").submit();
	});
	$(".users tr").click(function(event) {
		if (event.target.type !== "checkbox") {
    		$(":checkbox", this).trigger("click");
		}
	});
});
$(function() {
	$(".users tr").mouseover(function() { $(this).removeClass(\'inline\').addClass(\'inline\'); });
	$(".users tr").mouseout(function() { $(this).removeClass(\'inline\'); });
});
</script>';
if(!isset($_GET['type']) || $_GET['type'] == null) {
	if(isset($_POST['checked']) && isset($_POST['users'])) {
		if($_POST['checked'] == 'delete') {
			$count = 0;
			foreach($_POST['users'] as $delete) {
				$deleteUsers = ($count < 1) ? " id = '".$delete."'" : $deleteUsers." OR id = '".$delete."'";
				$count++;
			}
			$query = call('sql_query', "DELETE FROM users WHERE " . $deleteUsers . "");
			if($query)
				$_SESSION['update'] = 'The users has been deleted';
		}
		if($_POST['checked'] == 'ban') {
			$count = 0;
			foreach($_POST['users'] as $ban) {
				$banUsers = ($count < 1) ? " id = '".$ban."'" : $banUsers." OR id = '".$ban."'";
				$count++;
			}
			$search = call('sql_query', "SELECT ip FROM users WHERE " . $banUsers . "");
			$ip = call('sql_fetch_array',$search);
			$query = call('addban', $ip['ip'], 'You have been banned by the admin', 'ip');
			if($query)
				$_SESSION['update'] = 'The users have been banned';
		}
		if($_POST['checked'] == 'nologin') {
			$count = 0;
			foreach($_POST['users'] as $nologin) {
				$loginUsers= ($count < 1) ? " id = '".$nologin."'" : $loginUsers." OR id = '".$nologin."'";
				$count++;
			}
			$query = call('sql_query', "UPDATE users SET allow_login = '0' WHERE " . $loginUsers . "");
			if($query)
				$_SESSION['update'] = 'The users accounts have been disabled, they are unable to login';
		}
}
	if(!isset($_GET['order']))
		$_GET['order'] = 'id';
	if(!isset($_GET['sort']))
		$_GET['sort'] = 'ASC';
	// how many rows to show per page
	$rowsPerPage = 20;
	// by default we show first page
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
		$pageNum = $_GET['page'];
	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
	$sql = call('sql_query', "SELECT u.id, u.email, m.name, u.ip FROM users u LEFT JOIN membergroups m ON membergroup_id=membergroup ORDER BY " . $_GET['order'] . " " . $_GET['sort'] . " LIMIT $offset, $rowsPerPage");
	$users = array();
	while($r = call('sql_fetch_array', $sql)) {
		$users[] = array('id'=>$r[0],'email'=>$r[1],'name'=>$r[2],'ip'=>$r[3]);
	}
	$pagination= call('pagination', $pageNum, 20, 'SELECT COUNT(id) AS numrows FROM users', '?act=admin&opt=users&'.$authid.'&page=', 3);
	$body = $pagination . '<form onsubmit="return false;" action="" name="order"><div class="admin-panel2">'.theme('title', 'Current Users').theme('start_content').'
<table class="admin-table2 users">
	<tr>
		<td>
			<select name="id" onChange="window.location=this.options[this.selectedIndex].value;"><option value="">ID</option><option value="index.php?act=admin&opt=users&order=id&sort=ASC&page=' . $pageNum . '&'.$authid.'">0-9</option><option value="index.php?act=admin&opt=users&order=id&sort=DESC&page=' . $pageNum . '&'.$authid.'">9-0</option></select>
		</td>
		<td>
			<select name="username" onChange="window.location=this.options[this.selectedIndex].value;"><option value="">Username</option><option value="index.php?act=admin&opt=users&order=user&sort=ASC&page=' . $pageNum . '&'.$authid.'">A-Z</option><option value="index.php?act=admin&opt=users&order=user&sort=DESC&page=' . $pageNum . '&'.$authid.'">Z-A</option></select></td><td><select name="email" onChange="window.location=this.options[this.selectedIndex].value;"><option value="">Email</option><option value="index.php?act=admin&opt=users&order=email&sort=ASC&page=' . $pageNum . '&'.$authid.'">A-Z</option><option value="index.php?act=admin&opt=users&order=email&sort=DESC&page=' . $pageNum . '">Z-A</option></select>
		</td>
		<td>
			<select name="group" onChange="window.location=this.options[this.selectedIndex].value;"><option value="">Group</option><option value="index.php?act=admin&opt=users&order=membergroup&sort=ASC&page=' . $pageNum . '&'.$authid.'">Unactivated-Admin</option><option value="index.php?act=admin&opt=users&order=membergroup&sort=DESC&page=' . $pageNum . '&'.$authid.'">Admin-Unactivated</option></select></td><td><select name="ip" onChange="window.location=this.options[this.selectedIndex].value;"><option value="">Ip Address</option><option value="index.php?act=admin&opt=users&order=ip&sort=ASC&page=' . $pageNum . '">0-9</option><option value="index.php?act=admin&opt=users&order=ip&sort=DESC&page=' . $pageNum . '&'.$authid.'">9-0</option></select></form>
		</td>
		<th>Options</th>
		<th><input type="checkbox" id="select_all" /><form method="post" action="" id="withselected"></th>
	</tr>';
	foreach($users as $p) {
		$body.='<tr>
			<td width="5%">' . $p['id'] . '</td>
            <td>' . call('userprofilelink', $p['id']) . '</td>
			<td>' . $p['email'] . '</td>
			<td width="5%">' . $p['name'] . '</td>
			<td><a href="'.$settings['site_url'].'/index.php?act=trackip&amp;ip=' . $p['ip'] . '" target="_blank">'.$p['ip'].'</a></td>
            <td><a href="'.$settings['site_url'].'/index.php?act=admin&opt=users&type=edit&id=' . $p['id'] . '&amp;'.$authid.'" title="Edit"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&opt=users&type=delete&id=' . $p['id'] . '&amp;'.$authid.'" title="Delete" onclick="return confirm(\'Are you sure you want to delete this user?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span></td>
			<td><input type="checkbox" name="users[]" value="' . $p['id'] . '" /></td>
          </tr>';
	}
	$body .='</table>'.theme('end_content').'</div>' . $pagination . '<div style="text-align: right"><select name="checked" id="checked"><option value="">With Selected...</option><option value="delete">Delete</option><option value="ban">Ban IPs</option><option value="nologin">Prevent Login</option></select></form></div>';
}
if(isset($_GET['type']) && $_GET['type'] == 'edit') {
	if($_POST)
		$check = call('edituser', $_POST['username'], $_POST['email'], $_POST['pass'], $_POST['newpass'], $_POST['avatar'], $_POST['signature'], $_POST['location'], md5($_POST['currentpass']), $_POST['membergroup'], $_GET['id']);
	else
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The user has been updated';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=users&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM users WHERE id = '" . $_GET['id'] . "'");
	$r = call('sql_fetch_assoc', $sql);
	$head .= '<script type="text/javascript">function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
countfield.value = maxlimit - field.value.length;
}
</script>';
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=users&amp;'.$authid.'">Members</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=users&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['user'].'</a>')).'<form action="" method="post" id="users"><div class="admin-panel">'.theme('title', 'Edit User').theme('start_content').'<table class="admin-table2">
  <tr>
  	<td>Username:</td>
    <td><input type="text" name="username" class="required" value="'; if(errors()) { $body.= $_POST['user']; } else { $body .= $r['user']; } $body .='" /></td>
  </tr>
  <tr>
  	<td align="center" colspan="2" class="admin-subtitlebg">Account Settings</td>
  </tr>
  <tr>
  	<td>Email:</td>
    <td><input type="text" name="email" class="required email" value="'; if(errors()) { $body.= $_POST['email']; } else { $body .= $r['email']; } $body .='" /></td>
  </tr>
  <tr>
  	<td>Membergroup:</td>
    <td><select name="membergroup">';
	$sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql2)) {
       $body.='<option value="' . $m['membergroup_id'] . '"'; if($m['membergroup_id'] == $r['membergroup']) { $body.=' selected="selected"'; } $body.='>' . $m['name'] . '</option>';
	}
      $body.='</select>
    </td>
	</tr>
  <tr>
  	<td>New Password:</td>
    <td><input type="password" name="pass" id="password" value="'; if(errors()) { $body.= $_POST['pass']; } $body .='" />
    </td>
  </tr>
  <tr>
  	<td>Confirm New Password:</td>
    <td><input type="password" name="newpass" id="password_confirm" value="'; if(errors()) { $body.= $_POST['newpass']; } $body .='" />
    </td>
  </tr>
  <tr>
  	<td align="center" colspan="2" class="admin-subtitlebg">Miscellaneous Settings</td>
  </tr>
  <tr>
  	<td>Current Avatar:</td>
    <td>'; if(!empty($r['avatar'])) { $body.= '<img src="' . $r['avatar'] . '" alt="Avatar" />'; } else { $body .= 'None'; } $body .='</td>
  </tr>
  <tr><td>
  Url to Avatar:
<br/>
<span class="small-text">Must include http://</span>
</td>
<td><input type="text" name="avatar" class="url" value="'; if(errors()) { $body.= $_POST['avatar']; } else { $body .= $r['avatar']; } $body .='" /></td>
</tr><tr><td>Signature:</td><td><textarea name="signature" id="signature" cols="50" rows="3" onKeyDown="textCounter(this.form.signature,this.form.characters,300);" onKeyUp="textCounter(this.form.signature,this.form.characters,300);" style="width: 100%;">'; if(errors()) { $body.= $_POST['signature']; } else { $body .= $r['signature']; } $body .='</textarea><br>Characters Left:<input readonly type=text name=characters size="3" maxlength="3" value="300" /></td></tr>
	  <tr><td>Location:</td>
	  <td><input type="text" name="location" value="'; if(errors()) { $body.= $_POST['location']; } else { $body .= $r['location']; } $body .='" /></td>
	  </tr>
	  <tr><td>Your Password: <br /><span class="small-text">Required for security to update the user</span></td><td><input name="currentpass" type="password" class="required" /></td></tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Update User" name="Submit" /></td>
  </tr>
</table>'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
	$check = call('deleteuser', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The user has been deleted';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=users&'.$authid);
	}
}
?>