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

   Added Language - 07/05/09 - Paul Wratt
*/

if(!(defined("IN_ECMS"))) die("Hacking Attempt...");

$title = $ARTICLES_LANG["title"];
$title = 'Articles';
$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
$(function() {
	$("#visible").click(function(event) {
		event.preventDefault();
		$(".visiblebox").slideToggle();
	});
});
</script>
<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.populate.pack.js"></script><script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.tablednd_0_5.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#articles").validate();
	$("#categories").validate();
	$("#list").tableDnD({
		onDragClass: "Order",
		onDrop: function(table, row) {
			var neworder = $.tableDnD.serialize();
			$.post("index.php?act=ajax", {
				m: "order",
				order: neworder,
				type: "articlecats"
				},
				function(data, textStatus) {
					if(textStatus == "success") {
						$("#update").text("' . $ARTICLES_LANG["js_order_saved"] . '").slideDown();
					} else {
						$("#update").text("' . $ARTICLES_LANG["js_order_not_saved"] . '").slideDown();
					}
				},
				"text"
			);
		}
	});
});
</script>';
$query = call('sql_query', "SELECT * FROM article_categories ORDER BY item_order ASC");
if(!isset($_GET['type']) || $_GET['type']==null) {
	if($_POST) {
		if(isset($_POST['article'])) {
			$check = call('addarticle', $_POST['subject'], $_POST['summary'], $_POST['full_article'], $_POST['cat'], $_POST['ratings'], $_POST['comments']);
			if($check==true && !errors()) {
				$_SESSION['update'] = $ARTICLES_LANG["article_added"];
			}
		}
		if(isset($_POST['categories'])) {
			$check = call('addarticlecat', $_POST['name'], $_POST['description'], $_POST['visible']);
			if($check==true && !errors()) {
				$_SESSION['update'] = $ARTICLES_LANG["category_added"];
				session_write_close();
				call('redirect', 'index.php?act=admin&opt=articles&'.$authid);
			}
		}
	} else {
		$check = false;
	}
	$body = '<form action="" method="post" id="categories"><div class="admin-panel">'.theme('title', 'New Category').theme('start_content').'<table class="admin-table">
	  <tr>
	    <td>' . $ARTICLES_LANG["name"] . ':</td><td><input type="text" class="required" name="name" value="';
	if($check==false && isset($_POST['name'])) {
		$body .= $_POST['name'];
	}
	$body .= '"  /></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["description"] . ':</td><td><input type="text" class="required" name="description" value="';
	if($check==false && isset($_POST['description'])) {
		$body.= $_POST['description'];
	}
	$body .= '"  /></td>
	  </tr>
	  <tr>
	    <td colspan="2"><a href="javascript:void;" title=" ' . $ARTICLES_LANG["t_click_to_expand"] . ' " id="visible">' . $ARTICLES_LANG["visible_to"] . ':</a></td>
	  </tr>
	  <tr class="visiblebox">
	    <td>';
	$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql3)) {
		$body .= '
		<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="visible_' . $m['membergroup_id'] . '" checked="checked"/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
	}
	$body .= '</td>
	  </tr>
	  <tr>
	    <td  colspan="2" align="center"><input type="submit" value=" ' . $ARTICLES_LANG["btn_add_category"] . ' " name="categories" /><input name="Reset" type="reset" value=" ' . $ARTICLES_LANG["btn_reset"] . ' " /></td>
	  </tr>
	</table>'.theme('end_content').'</div></form>';
	if(call('sql_num_rows', $query)!=0) {
		$sql = call('sql_query', "SELECT * FROM articles");
		$articles = array();
		while($r = call('sql_fetch_assoc', $sql)){
			$articles[$r['id']] = $r;
		}
		$body .= '
    <div class="admin-panel">'.theme('title', $ARTICLES_LANG["current_categories"]).theme('start_content').'
	<table class="admin-table Order" id="list">';
		$cat = array();
		while($r = call('sql_fetch_array', $query)) {
			$cat[$r['id']] = $r;
		}
		foreach($cat as $r) {
			$body .= '
	  <tr id="'.$r['id'].'">
	    <td>'.$r['name'].'</td>
	    <td align="right"><a href="'.$settings['site_url'].'/index.php?act=admin&opt=articles&type=editcategory&id=' . $r['id'] . '&amp;'.$authid.'" title=" ' . $ARTICLES_LANG["t_edit"] . ' "><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="' . $ARTICLES_LANG["t_edit"] . '" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&opt=articles&type=deletecategory&id=' . $r['id'] . '&amp;'.$authid.'" title=" ' . $ARTICLES_LANG["t_delete"] . ' " onclick="return confirm(\'' . $ARTICLES_LANG["js_category_delete"] . '?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="' . $ARTICLES_LANG["t_delete"] . '" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span></td>
	  </tr>';
		}
		$body .= '
	</table>'.theme('end_content').'</div>
  <form action="" method="post" id="articles"><div class="admin-panel">'.theme('title', $ARTICLES_LANG["add_article"]).theme('start_content').'
	<table class="admin-table">
	  <tr>
	    <td>' . $ARTICLES_LANG["subject"] . ':</td>
	  </tr>
	  <tr>
	    <td><input type="text" class="required" name="subject" value="';
		if($check==false && isset($_POST['subject'])) {
			$body .= $_POST['subject'];
		}
		$body .= '" /></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["category"] . ':</td>
	  </tr>
	  <tr>
	    <td colspan="2"><select name="cat" class="required">';
		foreach($cat as $row) {
			$body .= '
		<option value="' . $row['id'] . '"';
			if($check==false && isset($_POST['cat']) && $_POST['cat']==$row['id']) {
				$body .= 'selected="selected"';
			}
			$body .= '>' . $row['name'] . '</option>';
		}
		$body .= '
		</select></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["summary"] . ':</td>
	  </tr>
	  <tr>
	    <td><textarea style="width: 99%;" class="required" rows="7" cols="55" name="summary">';
		if($check==false && isset($_POST['summary'])) {
			$body.= $_POST['summary'];
		}
		$body .= '</textarea></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["full_article"] . ':</td>
	  </tr>
	  <tr>
	    <td><textarea style="width: 99%;" class="textarea" rows="7" cols="55" name="full_article">';
		if($check==false && isset($_POST['full_article'])) {
			$body .= $_POST['full_article'];
		}
		$body .= '</textarea></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["ratings"] . '? <input type="checkbox" name="ratings" checked="checked" /></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["comments"] . '? <input type="checkbox" name="comments" checked="checked" /></td>
	  </tr>
	  <tr>
	    <td colspan="2" align="center"><input type="submit" value=" ' . $ARTICLES_LANG["btn_add_article"] . ' " name="article" /><input name="Reset" type="reset" value=" ' . $ARTICLES_LANG["btn_reset"] . ' " /></td>
	  </tr>
	</table>'.theme('end_content').'</div></form>
<br /><div class="admin-panel2">'.theme('title', $ARTICLES_LANG["current_articles"]).theme('start_content').'
<table class="admin-table2">
  <tr>
    <th>' . $ARTICLES_LANG["subject"] . '</th>
    <th>' . $ARTICLES_LANG["options"] . '</th>
  </tr>';
		foreach ($articles as $p){
			$body .= '
  <tr>
    <td><a href="'.$settings['site_url'].'/index.php?act=articles&amp;sa=article&amp;article=' . $p['id'] . '" target="_blank">' . $p['subject'] . '</a></td>
    <td width="10%"><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=articles&amp;type=edit&amp;id=' . $p['id'] . '&amp;'.$authid.'" title=" ' . $ARTICLES_LANG["t_edit"] . ' "><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" alt="' . $ARTICLES_LANG["t_edit"] . '" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span><a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=articles&amp;type=delete&amp;id=' . $p['id'] . '&amp;'.$authid.'" title=" ' . $ARTICLES_LANG["t_delete"] . ' " onclick="return confirm(\'' . $ARTICLES_LANG["js_article_delete"] . '?\')"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" alt="' . $ARTICLES_LANG["t_delete"] . '" /></a><span class=image-list-spacer>&nbsp;&nbsp;</span></td>
  </tr>';
		}
		$body .= '
</table>';
	}else {
		$body .= '
  </tr>
</table>'.theme('end_content').'</div>';
	}
}
if(isset($_GET['type']) && $_GET['type'] == 'edit') {
	if($_POST) {
		$check = call('editarticle', $_POST['subject'], $_POST['summary'], $_POST['full_article'], $_POST['cat'], $_POST['ratings'], $_POST['comments'], $_GET['id']);
	} else {
		$check = false;
	}
	if($check==true && !errors()) {
		$_SESSION['update'] = $ARTICLES_LANG["article_updated"];
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=articles&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM articles WHERE id = '" . $_GET['id'] . "'");
	$r = call('sql_fetch_assoc', $sql);
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=articles&amp;'.$authid.'">Articles</a>', $r['subject'])).'<form action="" method="post" id="articles"><div class="admin-panel">'.theme('title', $ARTICLES_LANG["edit_article"]).theme('start_content').'<table class="admin-table">
	  <tr>
	    <td>' . $ARTICLES_LANG["subject"] . ':</td>
	  </tr>
	  <tr>
	    <td><input type="text" class="required" name="subject" value="';
	if(errors()) {
		$body .= $_POST['subject'];
	} else {
		$body .= $r['subject']; 
	}
	$body .= '" /></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["category"] . ':</td>
	  </tr>
	  <tr>
	    <td colspan="2"><select name="cat" class="required">';
	while($row = call('sql_fetch_array',$query)) {
		$body .= '
		<option value="' . $row['id'] . '"';
		if(errors() && $_POST['cat']==$row['id']) {
			$body .= 'selected="selected"';
		} elseif($r['cat']==$row['id']) {
			$body .= 'selected="selected"';
		}
		$body .= '>' . $row['name'] . '</option>';
	}
	$body .= '
		</select></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["summary"] . ':</td>
	  </tr>
	  <tr>
	    <td><textarea style="width: 100%;" class="required" rows="7" cols="55" name="summary">';
	if(errors()) {
		$body .= $_POST['summary'];
	} else {
		$body .= $r['summary'];
	}
	$body .= '</textarea></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["full_article"] . ':</td>
	  </tr>
	  <tr>
	    <td><textarea style="width: 99%;" class="textarea" rows="7" cols="55" name="full_article">';
	if(errors()) {
		$body .= $_POST['full_article'];
	} else {
		$body .= $r['full_article'];
	}
	$body .= '</textarea></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["ratings"] . '? <input type="checkbox" name="ratings" ';
	if(errors() && $_POST['ratings']) {
		$body .= 'checked="checked"';
	} elseif($r['ratings']=='1') {
		$body .= 'checked="checked"';
	}
	$body .= '/></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["comments"] . '? <input type="checkbox" name="comments" ';
	if(errors() && $_POST['comments']) {
		$body.= 'checked="checked"';
	} elseif($r['comments']=='1') {
		$body .= 'checked="checked"';
	}
	$body .= '/></td>
	  </tr>
	  <tr>
	    <td  colspan="2" align="center"><input type="submit" value=" ' . $ARTICLES_LANG["btn_update_article"] . ' " name="Submit" /><input name="Reset" type="reset" value=" ' . $ARTICLES_LANG["btn_reset"] . ' " /></td>
  </tr>
</table>'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type']=='delete') {
	$check = call('deletearticle', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = $ARTICLES_LANG["article_deleted"];
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=articles&'.$authid);
	}
}
if(isset($_GET['type']) && $_GET['type']=='editcategory') {
	if($_POST) {
		$check = call('editarticlecat', $_POST['name'], $_POST['description'], $_POST['visible'], $_GET['id']);
	}else {
		$check = false;
	}
	if($check==true && !errors()) {
		$_SESSION['update'] = $ARTICLES_LANG["category_updated"];
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=articles&'.$authid);
	}
	$sql = call('sql_query', "SELECT * FROM article_categories WHERE id = '" . $_GET['id'] . "'");
	$fetch = call('sql_fetch_array',$sql);
	$body = theme('breadcrumb', array('<a href="'.$settings['site_url'].'/index.php?act=admin&amp;opt=articles&amp;'.$authid.'">Articles</a>', 'Category', $fetch['name'])).'<form action="" method="post" id="categories"><div class="admin-panel">'.theme('title', $ARTICLES_LANG["edit_category"]).theme('start_content').'<table class="admin-table">
	  <tr>
	    <td>' . $ARTICLES_LANG["name"] . ':</td>
	  </tr>
	  <tr>
	    <td><input type="text" name="name" class="required" value="';
	if(errors()) {
		$body .= $_POST['name'];
	} else {
		$body .= $fetch['name'];
	}
	$body .= '" /></td>
	  </tr>
	  <tr>
	    <td>' . $ARTICLES_LANG["description"] . ':</td>
	  </tr>
	  <tr>
	    <td colspan="2"><input type="text" class="required" name="description" value="';
	if(errors()) {
		$body .= $_POST['description'];
	} else {
		$body .= $fetch['description'];
	}
	$body .= '" /></td>
	  </tr>
	  <tr>
	    <td><a href="javascript:void;" title=" ' . $ARTICLES_LANG["t_click_to_expand"] . ' " id="visible">' . $ARTICLES_LANG["visible_to"] . ':</a></td>
	  </tr>
	  <tr id="visiblebox">
	    <td>';
	$sql3 = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_array',$sql3)) {
		$body.='
		<input type="checkbox" value="' . $m['membergroup_id'] . '" name="visible[]" id="visible_' . $m['membergroup_id'] . '"'; if(call('visiblecheck', $m['membergroup_id'], $fetch['visible'])) {
			$body .= 'checked="checked"';
		}
		$body .= '/><label for="visible_' . $m['membergroup_id'] . '">' . $m['name'] . '</label><br />';
	}
	$body .= '</td>
	  </tr>
	  <tr>
	    <td  colspan="2" align="center"><input type="submit" value=" ' . $ARTICLES_LANG["btn_update_category"] . ' " name="Submit" /><input name="Reset" type="reset" value=" ' . $ARTICLES_LANG["btn_reset"] . ' " /></td>
  </tr>
</table>'.theme('end_content').'</div></form>';
}
if(isset($_GET['type']) && $_GET['type'] == 'deletecategory') {
	$check = call('deletearticlecat', $_GET['id']);
	if($check==true && !errors()) {
		$_SESSION['update'] = 'The category and all articles within its category has been deleted';
		session_write_close();
		call('redirect', 'index.php?act=admin&opt=articles&'.$authid);
	}
}
?>