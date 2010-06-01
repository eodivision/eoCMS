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

$theme['head'] = '<link rel="stylesheet" media="screen" type="text/css" href="themes/' . $settings['site_theme'] . '/colorpicker.css" />
<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.jeditable.pack.js"></script>
<script type="text/javascript" src="' . $settings['site_url'] . '/js/colorpicker.js"></script>
<script type="text/javascript"> $(document).ready(function() {
<!--
$("#perms tr").click(function(event) {
    if (event.target.type !== "checkbox") {
    $(":checkbox", this).trigger("click");
    }
  });
     $(\'.edit\').editable(\'index.php?act=ajax&m=permedit\', {
         indicator : \'Saving...\',
         tooltip   : \'Click to edit and press Enter to save the edit\',
		 cancel : \'Cancel\',
		 submit: \'Save\'
     })
	 $("#colour").ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
	$("#colourselector div").css("backgroundColor", "#" + hex);
		$("#colour").val(hex);
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	}
})
.bind("keyup", function(){
	$(this).ColorPickerSetColor(this.value);
});
// -->
	 });
</script>';
$theme['title'] = 'Permissions';
if(!isset($_GET['type'])) {
	$sql= call('sql_query', "SELECT * FROM membergroups");
	$theme['body'] = '<div class="admin-panel2">'.theme('title', 'Membergroups').theme('start_content').'<table class="admin-table2"><tr><th>Member Group Name</th><th>Users</th><th>Options</th></tr>';
	while($p = call('sql_fetch_array',$sql)) {
		if($p['membergroup_id']!='1') {
			$number = call('sql_query', "SELECT id FROM users WHERE membergroup = '" . $p['membergroup_id'] . "'");
			$count = call('sql_num_rows', $number);
		}
		$theme['body'].= '<tr><td><div class="edit" id="' . $p['membergroup_id'] . '">' . $p['name'] . '</div></td><td>'; if($p['membergroup_id']!='1') { $theme['body'].= $count; } else { $theme['body'].='N/A'; }$theme['body'].='</td><td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;type=edit&amp;id=' . $p['membergroup_id'] . '&amp;'.$authid.'"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class="image-list-spacer">&nbsp;&nbsp;</span>'; if($p['membergroup_id'] > 4) { $theme['body'].='<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;type=delete&amp;id=' . $p['membergroup_id'] . '&amp;'.$authid.'" title="Delete" onclick="return confirm(\'Are you sure you want to delete this membergroup?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a><span class="image-list-spacer">&nbsp;&nbsp;</span>'; }$theme['body'].='</td></tr>';
	}
	$theme['body'] .='</table>
	<div class="imagebutton"><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;type=add&amp;'.$authid.'">Add Group</a></div>'.theme('end_content').'</div>';
}
if(isset($_GET['type']) && $_GET['type'] == 'edit') {
	if(isset($_POST['Submit']))
		$check = call('updategroup', $_POST['variable'], $_POST['users'], $_POST['image'], $_POST['colour'], $_GET['id']);
	elseif(isset($_POST['delete']))
		$check = call('movegroup', $_POST['delete'], $_POST['membergroup']);
	else 
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The membergroup has been updated';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=permissions&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id = '" . $_GET['id'] . "'");
	$sql2 = call('sql_query', "SELECT name, image, colour FROM membergroups WHERE membergroup_id = '" . $_GET['id'] . "'");
	$p = call('sql_fetch_array',$sql2);
	$theme['head'] .= '<script type="text/javascript">
		  $(document).ready(function(){
		  $("#check").click(function()  {
		  $("input[id=delete]").each(function()    {
		  this.checked = true;
		  });
		  });
				$("#uncheck").click(function()  {
		  $("input[id=delete]").each(function()    {
		  this.checked = false;
		  });
		  });
		  $("#member").change(function() {
		$("#remove").submit();
	});
		  });
		  </script>';
	$theme['body'] = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;'.$authid.'">Permissions</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$p['name'].'</a>')).'
	<form action="" method="post"><div class="admin-panel2">'.theme('title', 'Edit Permissions - ' . $p['name']).theme('start_content').'<table class="admin-table2">
		<tr>
		<td>Image: {theme/images/}</td>
		<td><input type="text" name="image" value="'; if(errors()) { $theme['body'].= $_POST['image']; } else { $theme['body'] .= $p['image']; } $theme['body'] .='" /><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/' . $p['image'] . '" /></td>
	  </tr>';
	while ($r= call('sql_fetch_array',$sql)) {
		$perm = ucwords($r['variable']);
		$perm = str_replace('_', ' ', $perm);
		$theme['body'].='<tr onmouseover="$(this).removeClass().addClass(\'inline\');" onmouseout="$(this).removeClass();">
    <td>' . $perm . '</td><td><input type="checkbox" name="variable[' . $r['id'] . ']" '; if($r['value']==1) { $theme['body'].='checked="checked"'; } $theme['body'].='/></td>
  </tr>';
	}
	$theme['body'].='<tr><td>Add Users<br/><span class="small-text">Seperate multiple users by a comma followed by a space eg admin, admin2</span></td><td><input type="text" name="users" id="users" /><a href="javascript:;" onclick="window.open(\''.$settings['site_url'].'/index.php?act=finduser&amp;area=users\',\'\',\'width=400,height=300\')" class="help"></a></td>
  </tr>
  <tr>
  	<td>Membergroup Hex Colour:</td><td><input name="colour" id="colour" /><div id="colourselector"><div style="width: 18px; height: 18px; background-color: #'.$p['colour'].';"></div></div></td>
  </tr>
  <tr>
  <td align="center" colspan="2"><input type="submit" value="Update Membergroup" name="Submit" /></td>
  </tr>
</table>'.theme('end_content').'</div></form><br /><form action="" method="post" id="remove"><div class="admin-panel">'.theme('title', 'Current Users').theme('start_content').'<table class="admin-table">
  <tr class="admin-subtitlebg" align="center">
  <td>Username</td>
  </tr>';
  $mem = call('sql_query', "SELECT id, user FROM users WHERE membergroup = '" . $_GET['id'] . "'");
	while($s = call('sql_fetch_array',$mem)) {
		$theme['body'] .='<tr><td><input type="checkbox" name="delete[]" value="' . $s['id'] . '" id="delete" /> ' . $s['user'] . '</td></tr>';
	}
	$theme['body'].='<tr><td><a href="javascript:void(0);" id="check">Select All</a> <a href="javascript:void(0);" id="uncheck">Un-select All</a>
</td></tr>
<tr><td>Move selected to <select id="member" name="membergroup">';
	$sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql2)) {
		$theme['body'].='<option value="' . $m['membergroup_id'] . '"'; if($m['membergroup_id'] == $_GET['id']) { $theme['body'].=' selected="selected"'; } $theme['body'].='>' . $m['name'] . '</option>';
	}
	$theme['body'].='</select></td></tr>
</table>'.theme('end_content').'
  </div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
	if($_GET['id'] <= 4)
		$error_die[] = 'It is not possible to delete this group';
	if($_POST)
		$check = call('deletegroup', $_GET['id'], $_POST['membergroup']);
	else
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The membergroup has been deleted';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=permissions&'.$authid);
	}
	$theme['body'] = '<form action="" method="post"><table><tr><td>Move all members in this group to <select id="member" name="membergroup">';
	$sql2 = call('sql_query', "SELECT membergroup_id, name FROM membergroups WHERE membergroup_id != '".$_GET['id']."'");
	while($m = call('sql_fetch_array',$sql2)) {
		$theme['body'].='<option value="' . $m['membergroup_id'] . '"'; if($m['membergroup_id'] == $_GET['id']) { $theme['body'].=' selected="selected"'; } $theme['body'].='>' . $m['name'] . '</option>';
	}
	$theme['body'].='</select></td></tr><tr><td><input type="submit" value="Confirm Deletion" /></td></tr></table></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'add') {
	if($_POST)
		$check = call('addgroup', $_POST['variable'], $_POST['name'], $_POST['image'], $_POST['colour']);
	else
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The membergroup has been added';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=permissions&'.$authid);
	}
	$theme['body'] = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;'.$authid.'">Permissions</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=permissions&amp;type=add&amp;'.$authid.'">Add Group</a>')).'<form action="" method="post"><div class="admin-panel2">'.theme('title', 'New Membergroup').theme('start_content').'<table class="admin-table2" id="perms">
  <tr>
  	<td>Membergroup Name:</td>
	    <td><input type="text" name="name" value="'; if(errors()) { $theme['body'].= $_POST['name']; } $theme['body'] .='" /></td>
  </tr>
   <tr>
  	<td>Image: {theme/images/}</td>
    <td><input type="text" name="image" value="'; if(errors()) { $theme['body'].= $_POST['image']; } $theme['body'] .='" /></td>
  </tr>';
	$sql = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id='4'");
	while ($fetch = call('sql_fetch_array',$sql)) {
		$perm2 = ucwords($fetch['variable']);
		$perm2 = str_replace('_', ' ', $perm2);
		$theme['body'].='<tr onmouseover="$(this).removeClass().addClass(\'inline\');" onmouseout="$(this).removeClass();">
    <td>' . $perm2 . '</td><td><input type="checkbox" name="variable[' . $fetch['variable'] . ']" /></td>
  </tr>';
	}
	$theme['body'].='<tr>
  	<td>Membergroup Hex Colour:</td><td><input name="colour" id="colour" /><div id="colourselector"><div style="width: 18px; height: 18px;"></div></div></td>
  </tr><tr>
  <td align="center" colspan="2"><input type="submit" value="Add Group" /> <input name="Reset" type="reset" value="Reset" /></td>
  </tr>
</table>'.theme('end_content').'</div></form>';
}
?>