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
if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$theme['title'] = $PANELS_LANG["title"] ;
$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#panels").validate();
	$.validator.addMethod("dependsOn", function (value, el, params) {
		return !$(params.el).is(params.being) || $(el).is(":filled");
		}, "' . $PANELS_LANG["js_required"] . '.");
	$.validator.addClassRules({
		file: {
			dependsOn: {
				el: "#content",
				being: ":blank"
			},
			required: false
		},
		content: {
			dependsOn: {
				el: "#file",
				being: ":selected"
				},
			required: false
		}
	});

});
$(function() {
	$("#visible").click(function(event) {
		event.preventDefault();
		$(".visiblebox").slideToggle();
	});
});
$(function() {
	$("#file").change(function(event) {
		if($("#file").val()=="")
			forms[0].content.disabled=true;
		else
			forms[0].content.disabled=false;
	});
});
</script>';
if(isset($_GET['id']) && ($_GET['type']=="delete" || $_GET['type']=="online" || $_GET['type']=="offline") && $_POST) {
	unset($_GET['type']);
	unset($_GET['id']);
}
if(isset($_GET['type']) && $_GET['type']=='delete') {
	$check = call('deletepanel', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = $PANELS_LANG["deleted"] ;
	}
	unset($_GET['type']);
}
if(isset($_GET['type']) && ($_GET['type']=='offline' || $_GET['type']=='online')) {
	$check = call('togglepanel', $_GET['id'], ($_GET['type']=='online'?'1':'0'));
	if($check==true && !errors()) {
		if(isset($_GET['type'])){
			$_SESSION['update'] = $PANELS_LANG["now"] . ' "' . $PANELS_LANG[$_GET['type']] . '"';
		}	}
	unset($_GET['type']);
}
if(!isset($_GET['type']) || $_GET['type']==null) {
	$check = false;
	if($_POST) {
		$check = call('addpanel', $_POST['panelname'], htmlentities($_POST['content']), $_POST['rank'], $_POST['online'], $_POST['side'], $_POST['file'], $_POST['all'], $_POST['type']);
	}
	if($check==true && !errors()) {
		$_SESSION['update'] = $PANELS_LANG["added"];
	}
	$theme['body'] ='<form action="#" method="post" id="panels">
<div class="admin-panel">'. theme('title', $PANELS_LANG["title_add"]) . theme('start_content') . '<table class="admin-table">
  <tr>
	  <td>' . $PANELS_LANG["panel_name"] . ':</td>
	</tr>
	<tr>
	  <td><input type="text" name="panelname" class="required" value="' . ($check==false && isset($_POST['panelname']) ?  $_POST['panelname'] : '') . '" /></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["panel_content"] . ': (' . $PANELS_LANG["leave_blank"] . ')</td>
	</tr>
	<tr>
          <td colspan="2"><textarea rows="1" cols="40" name="content" id="content" onclick="this.cols=40;this.rows=25" onblur="this.cols=40;this.rows=1">' . (($check==false && isset($_POST['content'])) ? $_POST['content'] : '') . '</textarea></td>
	</tr>
	<tr>
		<td>'.$PANELS_LANG["panel_type"].':</td>
	</tr>
	<tr>
    	<td><select name="type">
		<option value="html"'.($check==false && isset($_POST['type']) && $_POST['type'] == 'html' ? 'selected="selected"' : '') . '>HTML</option>
		<option value="php"'.($check==false && isset($_POST['type']) && $_POST['type'] == 'php' ? 'selected="selected"' : '') . '>PHP</option>
	    </select></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["built_in_panels"] . ':</td>
	</tr>
	<tr>
	  <td><select name="file" id="file" class="file">
		<option value="">' . $PANELS_LANG["o_none"] . '</option>';
	$sql = call('sql_query', "SELECT name, panels, folder FROM plugins WHERE active = '1'");
	if(call('sql_num_rows', $sql) != 0) {
		while($p = call('sql_fetch_array', $sql)) {
			$p['panels'] = unserialize($p['panels']);
			if(is_array($p['panels']) && count($p['panels']) != 0) {
				$theme['body'] .= '<optgroup label="'.$p['name'].'">';
				foreach($p['panels'] as $name => $file) {
					$theme['body'] .= '<option value="plugins/'.$p['folder'].'/panels/'.$file.'">'.$name.'</option>';	
				}
				$theme['body'] .= '</optgroup>';
			}
		}
	}
	$dir = 'panels/';
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			$theme['body'] .= '<optgroup label="Panels">';
			while (($file = readdir($dh))!==false){
				if (filetype($dir . $file)== "dir"){
					if($file!="." && $file!=".." && $file['0']!=".") {
						$theme['body'].='
		<option value="' . $file . '"' . (($check==false && isset($_POST['file']) && $_POST['file'] == $file) ? 'selected="selected"' : '') .  '>' . (isset($PANEL_LANG[$file]) ? $PANEL_LANG[$file] : str_replace('_',' ',$file)) . '</option>';
					}
				}
			}
			$theme['body'] .= '</optgroup>';
			closedir($dh);
		}
	}
	$theme['body'] .= '</select></td>
	</tr>
	<tr>
	  <td class="admin-subtitlebg"><a href="javascript:void;" title=" ' . $PANELS_LANG["t_click_to_expand"] . ' " id="visible">' . $PANELS_LANG["viewable_by"] . ':</a></td>
	</tr>
	<tr class="visiblebox">
	  <td>';
	$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql3)) {
		$theme['body'] .= '<input type="checkbox" value="' . $m['membergroup_id'] . '" name="rank[]" id="visible_' . $m['membergroup_id'] . '" checked="checked"/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
	}
	$theme['body'] .= '</td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["status"] . ':</td>
	</tr>
	<tr>
	  <td><select name="online" class="panels_online">
		<option value="1" ' . (($check==false && isset($_POST['online']) && $_POST['online'] == '1') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["online"] . '</option>
		<option value="0" ' . (($check==false && isset($_POST['online']) && $_POST['online'] == '0') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["offline"] . '</option>
	  </select></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["panel_placement"] . ':</td>
	</tr>
	<tr>
	  <td><select name="side">
		<option value="left" ' . (($check==false && isset($_POST['side']) && $_POST['side'] == 'left')  ? 'selected="selected"' : '') . '>' . $PANELS_LANG["left"] . '</option>
		<option value="right" ' . (($check==false && isset($_POST['side']) && $_POST['side'] == 'right') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["right"] . '</option>
		<option value="upper" ' . (($check==false && isset($_POST['side']) && $_POST['side'] == 'upper') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["upper"] . '</option>
		<option value="lower" ' . (($check==false && isset($_POST['side']) && $_POST['side'] == 'lower') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["lower"] . '</option>
	  </select></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["display_on_all_pages"] . '? (' . $PANELS_LANG["on_page_id_1_only"] . ')</td>
	</tr>
	<tr>
	  <td><select name="all">
		<option value="1" ' . (($check==false && isset($_POST['all']) && $_POST['all'] == '1') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["yes"] . '</option>
		<option value="0" ' . (($check==false && isset($_POST['all']) && $_POST['all'] == '0') ? 'selected="selected"' : '') . '>' . $PANELS_LANG["no"] . '</option>
	  </select></td>
	</tr>
	<tr>
	  <td colspan="2" align="center"><input type="submit" value=" ' . $PANELS_LANG["btn_add_panel"] . ' " name="Submit" /><input name="Reset" type="reset" value=" ' . $PANELS_LANG["btn_reset"] . ' " /></td>
	</tr>
</table>'.theme('end_content').'
</div>
</form>
<br />';
	$panelquery = call('sql_query', "SELECT * FROM panels ORDER BY item_order ASC", 'cache');
	$panels = array();
	foreach ($panelquery as $fetch) {
		 $panelstuff = array('id' => $fetch['id'], 'rank' => $fetch['rank'], 'all_pages' => $fetch['all_pages'], 'file' => $fetch['file'], 'panelname' => $fetch['panelname'], 'panelcontent' => html_entity_decode(htmlspecialchars_decode($fetch['panelcontent'], ENT_QUOTES), ENT_QUOTES), 'item_order' => $fetch['item_order'], 'type' => $fetch['type'], 'online' => $fetch['online'], 'side' => $fetch['side']);
		if ($fetch['side'] == 'left')
		  $panels['left'][$fetch['id']] = $panelstuff;
	    if ($fetch['side'] == 'right')
		  $panels['right'][$fetch['id']] = $panelstuff;
	    if ($fetch['side'] == 'upper')
		  $panels['upper'][$fetch['id']] = $panelstuff;
	    if ($fetch['side'] == 'lower')
		  $panels['lower'][$fetch['id']] = $panelstuff;
	}
	foreach($panels as $side => $data) {
		$theme['body'].= '<div class="admin-panel2">'.theme('title', $PANELS_LANG['title_'.$side]).theme('start_content').'<table class="admin-table2">';
		$i = 1;
		$count = count($data);
		foreach($data as $panel) {
			if($panel['online']=='1') {
				$on_off='online'; 
				$toggle='offline';
			} else {
				$on_off='offline'; 
				$toggle='online';
			}
			$theme['body'].= '<tr>
						<td width="90%">'.$panel['panelname'].'</td>
						<td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=' . $toggle . '&amp;id=' . $panel['id'] . '&amp;'.$authid.'" title=" ' . $PANELS_LANG["t_click_to_toggle"] . ' "><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/' . $on_off . '.png" alt="' . $on_off . '" /></a></td>
						<td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=edit&amp;id=' . $panel['id'] . '&amp;'.$authid.'" title=" ' . $PANELS_LANG["t_edit"] . ' "><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="' . $PANELS_LANG["t_edit"] . '" /></a></td>
						<td><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=delete&amp;id=' . $panel['id'] . '&amp;'.$authid.'" title=" ' . $PANELS_LANG["t_delete"] . ' " onclick="return confirm(\'' . $PANELS_LANG["js_confirm_delete"] . '?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="' . $PANELS_LANG["t_delete"] . '" /></a></td>';
			if ($i==1 && $count>1) {
				$theme['body'] .= '<td></td>
				<td><a title="' . $PANELS_LANG["t_move_down"] . '" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=order&amp;side='.$panel['side'].'&amp;order=down&amp;id=' . $panel['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderdown.png" alt="' . $PANELS_LANG["t_move_down"] . '" /></a></td>';
			} elseif ($i<$count) {
				$theme['body'] .= '<td><a title="' . $PANELS_LANG["t_move_up"] . '" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=order&amp;side='.$panel['side'].'&amp;order=up&amp;id=' . $panel['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderup.png" alt="' . $PANELS_LANG["t_move_up"] . '" /></a></td>
				<td><a title=" ' . $PANELS_LANG["t_move_down"] . ' " href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=order&amp;side=' . $panel['side'] . '&amp;order=down&amp;id=' . $panel['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderdown.png" alt="' . $PANELS_LANG["t_move_down"] . '" /></a></td>';
			} elseif ($count>1) {
				$theme['body'] .= '<td><a title="' . $PANELS_LANG["t_move_up"] . '" href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=order&amp;side='.$panel['side'].'&amp;order=up&amp;id=' . $panel['id'] . '&amp;' . $authid . '"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/orderup.png" alt="' . $PANELS_LANG["t_move_up"] . '" /></a></td>
				<td></td>';
			}
			$theme['body'].='
					</tr>';	
			++$i;
		}
		$theme['body'] .= '</table>'.theme('end_content').'</div>';
	}
}
if(isset($_GET['type']) && $_GET['type']=='edit') {
	if($_POST) {
		$check = call('editpanel', $_POST['panelname'], htmlentities($_POST['content']), $_POST['rank'], $_POST['online'], $_POST['side'], $_POST['file'], $_POST['all'], $_POST['type'], $_GET['id']);
	} else {
		$check = false;
	}
	if($check==true && !errors()) {
		$_SESSION['update'] = $PANELS_LANG["updated"];
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=panels&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM panels WHERE id = '" . $_GET['id'] . "'");
	$r = call('sql_fetch_assoc', $sql);
	$theme['body'] = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;'.$authid.'">Panels</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=panels&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['panelname'].'</a>')).'<form action="" method="post" id="panels">
<div class="admin-panel">'. theme('title', $PANELS_LANG["title_edit"]) . theme('start_content') . '<table class="admin-table"> 
	<tr>
	  <td>' . $PANELS_LANG["panel_name"] . ':</td>
	</tr>
	<tr>
	  <td><input type="text" name="panelname" value="' . (errors() ? $_POST['panelname'] : $r['panelname']) . '" /></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["panel_content"] . ':</td>
	</tr>
	<tr>
	  <td colspan="2"><textarea rows="1" cols="40" name="content" id="content" onclick="this.cols=40;this.rows=25" onblur="this.cols=40;this.rows=1">' . (errors() ? html_entity_decode(htmlspecialchars_decode($_POST['content'], ENT_QUOTES), ENT_QUOTES) : html_entity_decode(htmlspecialchars_decode($r['panelcontent'], ENT_QUOTES), ENT_QUOTES)) . '</textarea></td>
	</tr>
	<tr>
		<td>'.$PANELS_LANG["panel_type"].':</td>
	</tr>
	<tr>
    	 <td><select name="type">
		<option value="html"'; if (errors() && $_POST['type'] == 'html') { $theme['body'] .= ' selected="selected"'; } elseif ($r['type']=='html') { $theme['body'] .= ' selected="selected"'; } $theme['body'] .= '>HTML</option>
		<option value="php"'; if (errors() && $_POST['type'] == 'php') { $theme['body'] .= ' selected="selected"'; } elseif ($r['type']=='php') { $theme['body'] .= ' selected="selected"'; } $theme['body'] .= '>PHP</option>
	    </select></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["built_in_panels"] . ':</td>
	</tr>
	<tr>
	  <td><select name="file" id="file" class="file">
		<option value="">' . $PANELS_LANG["o_none"] . '</option>';
		$sql = call('sql_query', "SELECT name, panels, folder FROM plugins WHERE active = '1'");
	if(call('sql_num_rows', $sql) != 0) {
		while($p = call('sql_fetch_array', $sql)) {
			$theme['body'] .= '<optgroup label="'.$p['name'].'">';
			$p['panels'] = unserialize($p['panels']);
			if(is_array($p['panels'])) {
				foreach($p['panels'] as $name => $file) {
					$theme['body'] .= '<option value="plugins/'.$p['folder'].'/panels/'.$file.'">'.$name.'</option>';	
				}
			}
			$theme['body'] .= '</optgroup>';
		}
	}
	$dir = 'panels/';
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh))!==false){
				if (filetype($dir . $file)== "dir"){
					if($file!="." && $file!="..") {
						$theme['body'].='
		<option value="' . $file . '"';
						if($check==false && errors() && $_POST['file']==$file) {
							$theme['body'].='selected="selected"';
						} elseif($r['file']==$file) {
							$theme['body'].='selected="selected"';
						}
						$theme['body'].= '>' . (isset($PANEL_LANG[$file]) ? $PANEL_LANG[$file] : str_replace('_',' ',$file)) . '</option>';
					}
				}
			}
			closedir($dh);
		}
	}
	$theme['body'] .= '</select></td>
	</tr>
	</tr>
	<tr>
	  <td><a href="javascript:void;" title=" ' . $PANELS_LANG["t_click_to_expand"] . ' " id="visible">' . $PANELS_LANG["viewable_by"] . ':</a></td>
	</tr>
	<tr class="visiblebox">
	 <td>';
	$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql3)) {
		$theme['body'] .= '<input type="checkbox" value="' . $m['membergroup_id'] . '" name="rank[]" id="visible_' . $m['membergroup_id'] . '"';
		if(call('visiblecheck', $m['membergroup_id'], $r['rank'])) {
			$theme['body'] .= ' checked="checked"';
		}
		$theme['body'] .= '/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
	}
	$theme['body'] .= '</td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["status"] . '</td>
	</tr>
	<tr>
	  <td><select name="online">
		<option value="1" '; if (errors() && $_POST['online']=='1') { $theme['body'] .= 'selected="selected"'; } elseif ($r['online']=='1') { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["online"] . '</option>
		<option value="0" '; if (errors() && $_POST['online']=='0') { $theme['body'] .= 'selected="selected"'; } elseif ($r['online']=='0') { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["offline"] . '</option>
	    </select></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["panel_placement"] . ':</td>
	</tr>
	<tr>
	  <td><select name="side">
		<option value="left" ';  if (errors() && $_POST['side']=='left')  { $theme['body'] .= 'selected="selected"'; } elseif ($r['side']=='left')  { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["left"] . '</option>
		<option value="right" '; if (errors() && $_POST['side']=='right') { $theme['body'] .= 'selected="selected"'; } elseif ($r['side']=='right') { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["right"] . '</option>
		<option value="upper" '; if (errors() && $_POST['side']=='upper') { $theme['body'] .= 'selected="selected"'; } elseif ($r['side']=='upper') { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["upper"] . '</option>
		<option value="lower" '; if (errors() && $_POST['side']=='lower') { $theme['body'] .= 'selected="selected"'; } elseif ($r['side']=='lower') { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["lower"] . '</option>
	    </select></td>
	</tr>
	<tr>
	  <td>' . $PANELS_LANG["display_on_all_pages"] . '? (' . $PANELS_LANG["on_page_id_1_only"] . ')</td>
	</tr>
	<tr>
	  <td><select name="all">
		<option value="0" '; if($check==false && errors() && $_POST['all']=='0') { $theme['body'] .= 'selected="selected"'; } elseif ($r['all_pages']=='0') { $theme['body'] .= 'selected="selected"'; } $theme['body'].='>' . $PANELS_LANG["no"] . '</option>
		<option value="1" '; if($check==false && errors() && $_POST['all']=='1') { $theme['body'] .= 'selected="selected"'; } elseif ($r['all_pages']=='1') { $theme['body'] .= 'selected="selected"'; } $theme['body'] .= '>' . $PANELS_LANG["yes"] . '</option>
	    </select></td>
	</tr>
	<tr>
	  <td  colspan="2" align="center"><input type="submit" name="Submit" value="' . $PANELS_LANG["btn_update_panel"] . '" /><input type="button" value=" ' . $PANELS_LANG["btn_cancel"] . ' " onClick="location.href=\'index.php?act=admin&opt=panels&'.$authid.'\';" /></td>
	</tr>
</table>'.theme('end_content').'</div>
</form>';
}
if (isset($_GET['type']) && $_GET['type']=='order' && isset($_GET['side'])) {
	$sql = call('sql_query', "SELECT * FROM panels WHERE side = '" . $_GET['side'] . "'");
	while ($p = call('sql_fetch_array', $sql)) {
		if ($_GET['order']=='down') {
			$result = call('sql_query', "UPDATE panels SET item_order = item_order-1 WHERE id = '" . $p['id'] . "'");
		} elseif ($_GET['order']=='up') {
			$result = call('sql_query', "UPDATE panels SET item_order = item_order+1 WHERE id = '" . $p['id'] . "'");
		}
	}
	if ($_GET['order']=='down') {
		$result = call('sql_query', "UPDATE panels SET item_order = item_order+1 WHERE id = '" . $_GET['id'] . "'");
	} elseif ($_GET['order']=='up') {
		$result = call('sql_query', "UPDATE panels SET item_order = item_order-1 WHERE id = '" . $_GET['id'] . "'");
	}
	if ($result) {
		call('redirect', 'index.php?act=admin&opt=panels&'.$authid);
	}
}

?>