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

$title = 'News';
$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
<script type="text/javascript">
	$(function() {
		$("#visible").click(function(event) { event.preventDefault(); $("#visiblebox").slideToggle(); });
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#news").validate();
		$(".visible").click(function(event) { event.preventDefault(); $(".visiblebox").slideToggle(); });
		$(".news tr").mouseover(function() { $(this).removeClass(\'inline\').addClass(\'inline\'); });
		$(".news tr").mouseout(function() { $(this).removeClass(\'inline\'); });
	});
</script>';
$query = call('sql_query', "SELECT * FROM news_categories");
if(!isset($_GET['type']) || $_GET['type'] == null) {
	if(call('sql_num_rows', $query) == 0)
		$body ='<div class="admin-panel2">'.theme('title', 'Notice').theme('start_content').'You must add a category before you can make any news items<div class="imagebuttonwide"  style="margin-left:auto;margin-right:auto;"><a href="'.$settings['site_url'].'/index.php?act=admin&opt=news&type=category&amp;'.$authid.'">Add Category</a></div>'.theme('end_content').'</div>';
	else {
		if(isset($_POST['news'])) {
			if($_POST['start_day'] != '0')
				$start = mktime($_POST['start_hour'], $_POST['start_min'], 0, $_POST['start_month'], $_POST['start_day'], $_POST['start_year']);
			else
				$start = '';
			if($_POST['end_day'] != '0')
				$end = mktime($_POST['end_hour'], $_POST['end_min'], 0, $_POST['end_month'], $_POST['end_day'], $_POST['end_year']);
			else 
				$end ='';
			$check = call('addnews', $_POST['subject'], $_POST['news'], $_POST['extended'], $start, $end, $_POST['visible'], $_POST['cat'], $_POST['ratings'], $_POST['comments']);
			if($check==true && !errors())
				$_SESSION['update'] = 'The news has been added';
		} else
			$check = false;
		$sql = call('sql_query', "SELECT * FROM news");
		$news = array();
		while($r = call('sql_fetch_assoc', $sql)) {
			$news[$r['id']] = $r;
		}
		$days = range (1, 31);
		$months = range (1, 12);
		$years = range (date('Y'), date('Y')+5);
		$hour = range (0, 23);
		$min = range (0, 59);
		$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=category&amp;'.$authid.'">Category</a>')).'
		<form action="" method="post" id="news"><div class="admin-panel">'.theme('title', 'Add News').theme('start_content').'
		<table class="admin-table">
		  <tr>
			<td colspan="2">Subject:</td>
		  </tr>
		  <tr>
			<td colspan="2"><input type="text" name="subject" class="required" value="'; if($check == false && isset($_POST['subject'])) { $body.= $_POST['subject']; } $body .='" /></td>
		  </tr>
		  <tr>
			<td colspan="2">Category:</td>
		  </tr>
		  <tr>
			<td colspan="2"><select name="cat" class="required" >';
			while($row = call('sql_fetch_array',$query)) {
				$body.='<option value="' . $row['id'] . '"'; if($check == false && isset($_GET['cat']) && $_POST['cat'] == $row['id']) { $body.= 'selected="selected"'; } $body .='>' . $row['name'] . '</option>';
			}
		$body.='</select></td>
	  </tr>
	  <tr>
		<td colspan="2">News:</td>
		</tr>
		<tr>
		<td colspan="2"><textarea style="width: 99%;" class="required" rows="7" cols="55" name="news">'; if($check == false && isset($_POST['news'])) { $body.= $_POST['news']; } $body .='</textarea>
		</td>
	  </tr>
		<tr>
		<td colspan="2">Extended:</td>
		</tr>
		<tr>
		<td colspan="2"><textarea style="width: 99%;" class="textarea" rows="7" cols="55" name="extended">'; 
		if($check == false && isset($_POST['extended']))
			$body.= $_POST['extended'];
		$body .='</textarea>
		</td>
	  </tr>
		<tr>
		<td class="admin-subtitlebg" colspan="2"><a href="javascript:void;" title="Click to expand" class="visible">Visible</a></td>
	  </tr>
	  <tr class="visiblebox">
		<td>';
		$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
		while($m = call('sql_fetch_array',$sql3)) {
			$body.='<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="visible_' . $m['membergroup_id'] . '" checked="checked"/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
		}
		$body.='</td>
	  </tr>
	  <tr>
		<td class="admin-subtitlebg" colspan="2"><a href="javascript:void;" title="Click to expand" class="visible">Dates (Optional)</a></td>
	  </tr>
		<tr class="visiblebox">
		<td>Start Date:</td>
		</tr>
		<tr class="visiblebox"><td><select name="start_day"><option value="0">**</option>'; 
		foreach ($days as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['start_day']) && $_POST['start_day'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select><select name="start_month"><option value="0">**</option>';
		foreach ($months as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['start_month']) && $_POST['start_month'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select><select name="start_year"><option value="00">****</option>';
		foreach ($years as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['start_year']) && $_POST['start_year'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select> Time: <select name="start_min">';
		foreach ($min as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['start_min']) && $_POST['start_min'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select> : <select name="start_hour">'; 
		foreach ($hour as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['start_hour']) && $_POST['start_hour'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select> : 00</td></tr><tr class="visiblebox"><td>End Date:</td></tr><tr class="visiblebox"><td>';
		$body .='<select name="end_day"><option value="0">**</option>';
		foreach ($days as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['end_day']) && $_POST['end_day'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select><select name="end_month"><option value="0">**</option>';
		foreach ($months as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['end_month']) && $_POST['end_month'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select><select name="end_year"><option value="00">****</option>';
		foreach ($years as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['end_year']) && $_POST['end_year'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select> Time: <select name="end_min">'; 
		foreach ($min as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['end_min']) && $_POST['end_min'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select> : <select name="end_hour">'; 
		foreach ($hour as $value) {
			$body .= '<option value="'.$value.'"'; if($check == false && isset($_POST['end_hour']) && $_POST['end_hour'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
		}
		$body .= '</select>: 00</td></tr>
	 <tr>
		<td>Ratings</td>
		<td><input type="checkbox" name="ratings" checked="checked" /></td>
	  </tr>
	  <tr>
		<td>Comments</td>
		<td><input type="checkbox" name="comments" checked="checked" /></td>
	  </tr>
	  <tr>
	  <td  colspan="2" align="center"><input type="submit" value="Add News" name="submit" /><input name="Reset" type="reset" value="Reset" /></td>
	  </tr>
	</table>'.theme('end_content').'</div></form><br /><div class="admin-panel2">'.theme('title', 'Current News').theme('start_content').'<table class="admin-table2">
									   <tr style="text-align: left;"><th>Subject</th><th>Options</th></tr>';
		if(call('sql_num_rows', $sql) == 0)
			$body .='<tr><td>No News</td></tr>';
		else {
			foreach ($news as $p) {
				$body.='<tr id="'.$p['id'].'">
				<td><a href="'.$settings['site_url'].'/index.php?act=news&amp;readmore=' . $p['id'] . '" target="_blank">' . $p['subject'] . '</a></td>
				<td width="10%"><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=edit&amp;id=' . $p['id'] . '&amp;'.$authid.'" title="Edit"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class="image-list-spacer">&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=delete&amp;id=' . $p['id'] . '&amp;'.$authid.'" class="delete" title="Delete"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a><span class="image-list-spacer">&nbsp;&nbsp;</span></td>
			  </tr>';
			}
		}
		$body .='</table>'.theme('end_content').'</div>';
	}
}
if(isset($_GET['type']) && $_GET['type'] == 'edit') {
	$days = range (1, 31);
	$months = range (1, 12);
	$years = range (date('Y'), date('Y')+5);
	$hour = range (0, 23);
	$min = range (0, 59);
	if($_POST) {
		if($_POST['start_day'] != '0')
			$start = mktime($_POST['start_hour'], $_POST['start_min'], 0, $_POST['start_month'], $_POST['start_day'], $_POST['start_year']);
		else 
			$start = '';
		if($_POST['end_day'] != '0')
			$end = mktime($_POST['end_hour'], $_POST['end_min'], 0, $_POST['end_month'], $_POST['end_day'], $_POST['end_year']);
		else 
			$end ='';
			$check = call('editnews', $_POST['subject'], $_POST['news'], $_POST['extended'], $start, $end, $_POST['visible'], $_POST['cat'], $_POST['ratings'], $_POST['comments'], $_GET['id']);
	} else 
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The news has been updated';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=news&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM news WHERE id = '" . $_GET['id'] . "'");
	$r = call('sql_fetch_assoc', $sql);
	if(!empty($r['start_time']))
		$starttime = getdate($r['start_time']);
	else
		$starttime = '';
	if(!empty($r['end_time']))
		$endtime = getdate($r['end_time']);
	else
		$endtime = '';
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;'.$authid.'">News</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=edit&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$r['subject'].'</a>')).'<form action="" method="post" id="news"><div class="admin-panel">'.theme('title', 'Edit '.$r['subject']).theme('start_content').'<table class="admin-table">
	 <tr>
		<td colspan="2">Subject:</td>
	  </tr>
	  <tr>
		<td colspan="2"><input type="text" name="subject" class="required" value="'; if(errors()) { $body.= $_POST['subject']; } else { $body .= $r['subject']; } $body .='" /></td>
	  </tr>
	  <tr>
		<td colspan="2">Category:</td>
	  </tr>
	  <tr>
		<td colspan="2"><select name="cat" class="required">';
		while($row = call('sql_fetch_array',$query)) {
		$body.='<option value="' . $row['id'] . '"'; if(errors() && $_POST['cat'] == $row['id']) { $body.= 'selected="selected"'; } elseif($r['cat'] == $row['id']) { $body.='selected="selected"'; } $body .='>' . $row['name'] . '</option>';
		}
		$body.='</select></td>
	  </tr>
	  <tr>
		<td colspan="2">News:</td>
		</tr>
		<tr>
		<td colspan="2"><textarea style="width: 99%;" class="required" rows="7" cols="55" name="news">'; if(errors()) { $body.= $_POST['news']; } else { $body .= $r['content']; } $body .='</textarea>
		</td>
	  </tr>
		<tr>
		<td colspan="2">Extended:</td>
		</tr>
		<tr>
		<td colspan="2"><textarea style="width: 99%;" class="textarea" rows="7" cols="55" name="extended">'; if(errors()) { $body.= $_POST['extended']; } else { $body .= $r['extended']; }  $body .='</textarea>
		</td>
	  </tr>
	<tr>
		<td class="admin-subtitlebg" colspan="2"><a href="javascript:void;" title="Click to expand" class="visible">Visible</a></td>
	  </tr>
	  <tr class="visiblebox">
		<td>';
		$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
		while($m = call('sql_fetch_array',$sql3)) {
			$body.='<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="visible_' . $m['membergroup_id'] . '"'; if(call('visiblecheck', $m['membergroup_id'], $r['visibility'])) { $body.='checked="checked"'; } $body.='/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
		}
		  $body.='</td>
	  </tr>
	 <tr>
		<td class="admin-subtitlebg" colspan="2"><a href="javascript:void;" title="Click to expand" class="visible">Dates (Optional)</a></td>
	  </tr>
		<tr class="visiblebox">
		<td>Start Date:</td>
		</tr>
		<tr class="visiblebox"><td><select name="start_day"><option value="0">**</option>'; foreach ($days as $value) {
	$body .= '<option value="'.$value.'"'; if(errors() && $_POST['start_day'] == $value) { $body .='selected="selected"'; } elseif(!empty($starttime) && $starttime['mday'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select><select name="start_month"><option value="0">**</option>';
	foreach ($months as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['start_month'] == $value) { $body .='selected="selected"'; } elseif(!empty($starttime) && $starttime['mon'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select><select name="start_year"><option value="00">****</option>';
	foreach ($years as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['start_year'] == $value) { $body .='selected="selected"'; } elseif(!empty($starttime) && $starttime['year'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select> Time: <select name="start_min">';
		foreach ($min as $value) {
	$body .= '<option value="'.$value.'"'; if(errors() && $_POST['start_min'] == $value) { $body .='selected="selected"'; } elseif(!empty($starttime) && $starttime['minutes'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select> : <select name="start_hour">'; 
	foreach ($hour as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['start_hour'] == $value) { $body .='selected="selected"'; } elseif(!empty($starttime) && $starttime['hours'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select> : 00</td></tr><tr class="visiblebox"><td>End Date:</td></tr><tr class="visiblebox"><td>';
	$body .='<select name="end_day"><option value="0">**</option>';
	foreach ($days as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['end_day'] == $value) { $body .='selected="selected"'; } elseif(!empty($starttime) && $endtime['mday'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select><select name="end_month"><option value="0">**</option>';
	foreach ($months as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['end_month'] == $value) { $body .='selected="selected"'; } elseif(!empty($endtime) && $endtime['mon'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select><select name="end_year"><option value="00">****</option>';
	foreach ($years as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['end_year'] == $value) { $body .='selected="selected"'; } elseif(!empty($endtime) && $endtime['year'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select> Time: <select name="end_min">';
	foreach ($min as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['end_min'] == $value) { $body .='selected="selected"'; } elseif(!empty($endtime) && $endtime['minutes'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select> : <select name="end_hour">';
	foreach ($hour as $value) {
		$body .= '<option value="'.$value.'"'; if(errors() && $_POST['end_hour'] == $value) { $body .='selected="selected"'; } elseif(!empty($endtime) && $endtime['seconds'] == $value) { $body .='selected="selected"'; } $body .='>'.$value.'</option>';
	}
	$body .= '</select>: 00</td></tr>
	 <tr>
		<td>Ratings</td>
		<td><input type="checkbox" name="ratings" '; if(errors() && $_POST['ratings']) { $body.= 'checked="checked"'; } elseif($r['ratings'] =='1') { $body.= 'checked="checked"'; } $body.='/></td>
	  </tr>
	  <tr>
		<td>Comments</td>
		<td><input type="checkbox" name="comments" '; if(errors() && $_POST['comments']) { $body.= 'checked="checked"'; } elseif($r['comments'] =='1') { $body.= 'checked="checked"'; } $body.='/></td>
	  </tr>
	<tr>
	  <td  colspan="2" align="center"><input type="submit" value="Update News" name="Submit" /></td>
	  </tr>
	</table>'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'delete') {
	$check = call('deletenews', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The news has been deleted';
		if(!isset($_GET['ajax'])) {
			session_write_close();
			call('redirect', 'index.php?act=admin&opt=news&'.$authid);
		} else
			echo json_encode(array('message' => $_SESSION['update'], 'id' => $_GET['id']));
	}
}
if(isset($_GET['type']) && $_GET['type'] == 'category') {
	if($_POST)
		$check = call('addcat', $_POST['name'], $_POST['image']);
	else
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The category has been added';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=news&type=category&'.$authid);
	} 
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&opt=news&amp;'.$authid.'">News</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=category&amp;'.$authid.'">Category</a>')).'<br />';
	$sql = call('sql_query', "SELECT * FROM news_categories");
	$body .='<form action="" method="post"><div class="admin-panel">'.theme('title', 'New Category').theme('start_content').'<table class="admin-table">
	  <tr>
		<td>Category Name:</td>
	  </tr>
	  <tr>
		<td><input type="text" name="name" value="'; if($check == false && isset($_POST['name'])) { $body.= $_POST['name']; } $body .='" /></td>
	  </tr>
	  <tr>
		<td>Image URL:</td>
	  </tr>
	  <tr>
		<td colspan="2"><input type="text" name="image" value="'; if($check == false && isset($_POST['image'])) { $body.= $_POST['image']; } $body .='" /><br />Recommended size 90 x 70</td>
	  </tr>
	  <tr>
	  <td  colspan="2" align="center"><input type="submit" value="Add Category" name="category" /><input name="Reset" type="reset" value="Reset" /></td>
	  </tr>
	</table>'.theme('end_content').'</div></form>
	<br /><div class="admin-panel2">'.theme('title', 'News Categories').theme('start_content').'<table class="admin-table2">';
	if(call('sql_num_rows', $sql) != 0) {
		$fetch = array();
		$i=0;
		while($fetch = call('sql_fetch_assoc', $sql)) {
			if ($i != 0 && ($i % 4 == 0))
				$body.='</tr><tr>';
			$body.='<td><img src="' . $fetch['image'] . '" alt="' . $fetch['name'] . '" /><br />' . $fetch['name'] . ' <br /><a href="'.$settings['site_url'].'/index.php?act=admin&opt=news&type=editcategory&id=' . $fetch['id'] . '&amp;'.$authid.'" title="Edit"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="Edit" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&opt=news&type=deletecategory&id=' . $fetch['id'] . '&amp;'.$authid.'" title="Delete" onclick="return confirm(\'Are you sure you want to delete this category? Warning, deleting a category will also delete all news within this category\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="Delete" /></a></td>';
			$i++;
		}
	} else
		$body.='<tr><td>No categories defined</td></tr>';
	$body .='</table>'.theme('end_content').'</div>';
}
if(isset($_GET['type']) && $_GET['type'] == 'editcategory') {
	if($_POST)
		$check = call('editcat', $_POST['name'], $_POST['image'], $_GET['id']);
	else 
		$check = false;
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The category has been updated';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=news&type=category&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM news_categories WHERE id = '" . $_GET['id'] . "'");
	$fetch = call('sql_fetch_array',$sql);
	$body= theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;'.$authid.'">News</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=category&amp;'.$authid.'">Category</a>', '<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=news&amp;type=editcategory&amp;id='.$_GET['id'].'&amp;'.$authid.'">'.$fetch['name'].'</a>')).'<form action="" method="post"><div class="admin-panel">'.theme('title', 'Edit Category').theme('start_content').'<table class="admin-table">
	  <tr>
		<td>Category Name:</td>
	  </tr>
	  <tr>
		<td><input type="text" name="name" value="'; if(errors()) { $body.= $_POST['name']; } else { $body.= $fetch['name']; } $body .='" /></td>
	  </tr>
	  <tr>
		<td>Image URL:</td>
	  </tr>
	  <tr>
		<td colspan="2"><input type="text" name="image" value="'; if(errors()) { $body.= $_POST['image']; } else { $body.= $fetch['image']; } $body .='" /><br />Recommended size 90 x 70</td>
	  </tr>
	  <tr>
	  <td  colspan="2" align="center"><input type="submit" value="Update Category" name="Submit" /><input name="Reset" type="reset" value="Reset" /></td>
	  </tr>
	</table>'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'deletecategory') {
	$check = call('deletecat', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The category and all news within its category has been deleted';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=news&type=category&'.$authid);
	}
}
?>