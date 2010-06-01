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

$title = 'My Navigation Panel';
$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#list").tableDnD({
	    onDragClass: "Order",
	    onDrop: function(table, row) {
		var neworder = $.tableDnD.serialize();
            $.post("index.php?act=ajax&m=plugin&id='.$_GET['id'].'", { order: neworder }, function(data, textStatus) { if(textStatus == "success") { $("#update").text("New order saved"); } else { $("#update").text("Unabled to save new order"); }}, "text");
	    }
	});
	$("#links").validate();
	$.validator.addMethod("dependsOn", function (value, el, params) {
				return !$(params.el).is(params.being) || $(el).is(":filled");
			}, "This field is required.");
	$.validator.addClassRules({
				predefined: {
					dependsOn: {
						el: "#url",
						being: ":blank"
					},
					required: false
				},
				url: {
					dependsOn: {
						el: "#predefined",
						being: ":selected"
					},
					required: false,
					url: true
				}
			});

});
	$(function() {
$("#visible").click(function(event) { event.preventDefault(); $("#visiblebox").slideToggle(); });
});
function windowcheck() {
	if($("#settings option:selected").val()=="popup") {
	$("#popup").slideToggle();
	$("#width").addClass("required digit");
	$("#height").addClass("required digit");
	} else {
	$("#width").removeClass("required digit");
	$("#height").removeClass("required digit");
	if($("#popup").css("display") != "none") {
	$("#popup").slideToggle();
	}
	}
}
</script>';
if(!isset($_GET['sa']) || $_GET['sa'] == null) {
if($_POST) {
$check = plugin('addlink', 'navpanel', $_POST['link_name'], $_POST['url'], $_POST['rank'], $_POST['predefined'], $_POST['authid'], $_POST['settings'], $_POST['width'], $_POST['height']);
}
else { $check = false; }
if($check==true && !errors()) {
$update = 'The link has been added';
}
$sql= call('sql_query', "SELECT * FROM navigation_menu ORDER BY item_order ASC");
$links = array();
while($r = call('sql_fetch_array', $sql))
	{
		$links[$r['id']] = $r;
	}
$body ='<form action="" method="post" id="links"><table cellspacing="0" cellpadding="0" align="center">
  <tr>
   <td class="titlebg" colspan="2" align="center">New Link</td>
  </tr>
  <tr>
  	<td><table class="content" align="center"><tr><td>Name:</td>
  </tr>
  <tr>
    <td><input type="text" name="link_name" class="required" value="'; if($check == false && isset($_POST['link_name'])) { $body.= $_POST['link_name']; } $body .='" /></td>
  </tr>
  <tr>
  	<td>Url: (If using a predefined page, leave the Url empty!)</td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="url" id="url" class="url" value="'; if($check == false && isset($_POST['url'])) { $body.= $_POST['url']; } $body .='" /> External links must include http://</td>
  </tr>
  <tr>
  	<td><a href="javascript:void;" title="Click to expand or contract" id="visible">Viewable by:</a></td>
  </tr>
  <tr id="visiblebox">
  	<td>';
		$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql3)) {
       $body.='<input type="checkbox" value="' . $m['membergroup_id'] . '" name="rank[]" id="visible_' . $m['membergroup_id'] . '" checked="checked"><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
	   }
      $body.='</td>
  </tr>
  <tr>
  	<td>Predefined Link:
    <select name="predefined" id="predefined" class="predefined"><option value="">None</option>'; $pagesql = call('sql_query', "SELECT * FROM pages ORDER BY pagename ASC");
while($pagefetch = call('sql_fetch_array',$pagesql))
	{
$body.= '<option value="' . $pagefetch['id'] . '"'; if($check == false  && isset($_POST['predefined']) && $_POST['predefined'] == $pagefetch['id']) { $body.= 'selected="selected"'; } $body.='>' . $pagefetch['pagename'] . '</option>';
}
   $body.='</td>
  </tr>
  <tr><td>Append Authentication ID? <input type="checkbox" name="authid" /></td>
  </tr>
  <tr>
  	<td>Open Link in  
	<select name="settings" id="settings" onchange="windowcheck();">
	<option value="same"'; if($check == false  && isset($_POST['settings']) && $_POST['settings'] == 'same') { $body.= 'selected="selected"'; } $body.='>Same Window</option>
	<option value="new"'; if($check == false  && isset($_POST['settings']) && $_POST['settings'] == 'new') { $body.= 'selected="selected"'; } $body.='>New Window</option>
	<option value="popup"'; if($check == false  && isset($_POST['settings']) && $_POST['settings'] == 'popup') { $body.= 'selected="selected"'; } $body.='>Popup</option>
	</select><div id="popup" style="display: none;">Popup Width: <input type="text" name="width" id="width" size="3"'; if($check == false  && isset($_POST['settings']) && $_POST['settings'] == 'popup' && isset($_POST['width'])) { $body.= 'value="'.$_POST['width'].'"'; } $body.=' /> Height: <input type="text" name="height" id="height" size="3"'; if($check == false  && isset($_POST['settings']) && $_POST['settings'] == 'popup' && isset($_POST['height'])) { $body.= 'value="'.$_POST['height'].'"'; } $body.=' /></div></td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Add Link" name="Submit" /><input name="Reset" type="reset" value="Reset" /></td>
  </tr></table></td></tr>
</table></form><br /><div class="titlebg" align="center"><b>Current Links</b></div><table align="center" class="content" width="100%" id="list" class="Order">';
        foreach ($links as $p)
        {
            $body.='<tr id="' . $p['id'] . '">
            <td width="90%"><a href="' . $p['link'] . '" target="_blank">' . $p['name'] . '</a><br />
            <td width="10%"><a href="'.$settings['site_url'].'/index.php?act=admin&opt=plugins&type=admin&sa=edit&id='.$_GET['id'].'&linkid=' . $p['id'] . '&amp;'.$authid.'" title="Edit"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&opt=plugins&type=admin&sa=delete&id='.$_GET['id'].'&linkid=' . $p['id'] . '&amp;'.$authid.'" title="Delete" onclick="return confirm(\'Are you sure you want to delete this link?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span></td>
          </tr>';
        }
$body .='</table>';
}
if(isset($_GET['sa']) && $_GET['sa'] == 'edit') {
if($_POST) {
$check = plugin('editlink', 'navpanel', $_POST['link_name'], $_POST['url'], $_POST['predefined'], $_POST['rank'], $_POST['authid'], $_POST['settings'], $_POST['width'], $_POST['height'], $_GET['linkid']);
}
else { $check = false; }
if($check==true && !errors()) {
$update = 'The link has been updated';
session_write_close();
header("Location: index.php?act=admin&opt=plugins&type=admin&id=" . $_GET['id']."&".$authid);
}
$sql = call('sql_query', "SELECT * FROM navigation_menu WHERE id = '" . $_GET['linkid'] . "'");
$r = call('sql_fetch_assoc', $sql);
$body ='<form action="" method="post" id="links"><table cellspacing="0" cellpadding="0" align="center">
  <tr>
   <td class="titlebg" colspan="2" align="center">Edit Link</td>
  </tr>
  <tr>
  	<td><table class="content" align="center"><tr><td>Board Name:</td>
  </tr>
  <tr>
    <td><input type="text" name="link_name" class="required" value="'; if(errors()) { $body.= $_POST['link_name']; } else { $body .= $r['name']; } $body .='" /></td>
  </tr>
  <tr>
   	<td>Url: (If using a predefined page, leave the Url empty!)</td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="url" class="url" id="url" value="'; if(errors()) { $body.= $_POST['url']; } else { $body .= $r['link']; } $body .='" /></td>
  </tr>
      <tr>
  	<td><a href="javascript:void;" title="Click to expand" id="visible">Viewable by:</a></td>
  </tr>
    <tr id="visiblebox">
  	<td>';
		$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql3)) {
       $body.='<input type="checkbox" value="' . $m['membergroup_id'] . '" name="rank[]" id="visible_' . $m['membergroup_id'] . '"';
	   if(call('visiblecheck', $m['membergroup_id'], $r['rank'])) {
	   $body.=' checked="checked"';
	   }
	   $body.='><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
	   }
      $body.='</td>
  </tr>
  <tr>
  	<td>Predefined Link:</td>
    <td><select name="predefined" class="predefined" id="predefined"><option value="">None</option>'; $pagesql = call('sql_query', "SELECT * FROM pages ORDER BY pagename ASC");
while($pagefetch = call('sql_fetch_array',$pagesql))
	{
$body.= '<option value="' . $pagefetch['id'] . '"'; if(errors() && $_POST['predefined']) { $body.= 'selected="selected"'; } $body.='>' . $pagefetch['pagename'] . '</option>';
}
   $body.='</td>
  </tr>
  <tr>
  <td  colspan="2" align="center"><input type="submit" value="Update Link" name="Submit" /></td>
  </tr>
</table></td></tr></table></form>';
}
if(isset($_GET['sa']) && $_GET['sa'] == 'delete') {
$check = plugin('deletelink', 'navpanel', $_GET['id']);
if($check==true && !errors()) {
$update = 'The link has been deleted';
session_write_close();
header("Location: index.php?act=admin&opt=plugins&type=admin&id=" . $_GET['id']."&".$authid);
}
}
?>