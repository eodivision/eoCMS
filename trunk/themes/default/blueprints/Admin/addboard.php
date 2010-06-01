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
if(isset($_POST['submit'])) {
	$_POST['sticky'] = (!isset($_POST['sticky']) ? false : $_POST['sticky']);
	$_POST['lock'] = (!isset($_POST['lock']) ? false : $_POST['lock']);
	$check = call('addboard', $_POST['board_name'], $_POST['board_description'], $_POST['category'], $_POST['sticky'], $_POST['lock'], $_POST['permission']);
	if($check === true)
		$theme['success'] = theme('start_content').'Board Successfully Added'.theme('end_content');
} else {
	$query = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order ASC");
	$cats = array();
	if(call('sql_num_rows', $query) != 0) {
		while($p = call('sql_fetch_array', $query)) {
			$cats[$p['id']] = array('cat_id' => $p['id'], 'cat_name' => $p['cat_name']);
		}
	}
	$theme['title'] = $ADMIN_LANG["new_board"];
	$theme['head'] = '
					<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.jcarousellite.pack.js"></script>';
	$theme['head_js'] = '
					$(function() {
						$(".boards").validate();
						$(".permissions").tabs();
						$(".permissions").jCarouselLite({
							btnNext: ".next",
							btnPrev: ".prev",
							visible: "3",
							circular: false
						});
						$(".slider").slider({
							min: 1, 
							max: 2,
							slide: function(event, ui) { 
								if(ui.value == 1)
									$(this).parent("div").find(".slidervalue").css("color", "red").text("No");
								else if(ui.value == 2)
									$(this).parent("div").find(".slidervalue").css("color", "green").text("Yes");
								$(this).parent("div").find("input:hidden").val(ui.value);
							},
							value: $(this).parent("div").find("input:hidden").val()
						});
						$(".slider2").slider({
							min: 1,
							max: 3,
							slide: function(event, ui) { 
								if(ui.value == 1)
									$(this).parent("div").find(".slider2value").css("color", "red").text("No");
								else if(ui.value == 2)
									$(this).parent("div").find(".slider2value").css("color", "orange").text("Own");
								else if(ui.value == 3)
									$(this).parent("div").find(".slider2value").css("color", "green").text("Any");
								$(this).parent("div").find("input:hidden").val(ui.value);
							},
							value: $(this).parent("div").find("input:hidden").val()
						});
					});
					// jQuery UI doesnt move the slider for some reason when value is set, need to do it manually
					// they start at 0% so no need to move/check for value of 1
					$(".slider").each(function() {
						if($(this).parent("div").find("input:hidden").val() == 2)
							$(this).find(".ui-slider-handle").css("left", "100%");
					});
					$(".slider2").each(function() {
						if($(this).parent("div").find("input:hidden").val() == 2)
							$(this).find(".ui-slider-handle").css("left", "50%");
						else if($(this).parent("div").find("input:hidden").val() == 3)
							$(this).find(".ui-slider-handle").css("left", "100%");
					});';
	$theme['body'] = '
	<form action="" method="post" class="boards">
		<div class="admin-panel">
			'.theme('title', $ADMIN_LANG["board_name"]).'
		'.theme('start_content').'
				<input type="text" name="board_name" class="required" />
			'.theme('end_content').'
			'.theme('title', $ADMIN_LANG["board_description"]).'
			'.theme('start_content').'
				<textarea name="board_description" cols="36" rows="2" class="required"></textarea>
			'.theme('end_content').'
					<tr>
						<td>
							'.$ADMIN_LANG["board_category"].': 
							<select name="category">';
	foreach ($cats as $cat) {
		$theme['body'] .= '
								<option value="'.$cat['cat_id'].'">
									'.$cat['cat_name'].'
								</option>';
	}
	$theme['body'] .= '
							</select>
						</td>
	  				</tr>
				  	<tr>
						<td class="admin-subtitlebg">
							'.$ADMIN_LANG["board_permissions"].
						'</td>
					</tr>
				  	<tr>
						<td>
							<a class="prev"></a>
							<a class="next"></a>
							<div class="permissions" style="margin-left: auto; margin-right: auto; min-width: 200px; padding-left: 13px; padding-right: 17px;">
								<ul class="permissions-list">';
	$perms = call('forumpermlist');
	$membergroups = call('sql_query', "SELECT membergroup_id, name FROM membergroups");
	while($m = call('sql_fetch_assoc', $membergroups)) {
		$member[] = $m;
	}
	foreach($member as $m) {
		$theme['body'] .= '
									<li>
										<a href="#permissions-'.$m['membergroup_id'].'">
											'.$m['name'].'
										</a>
									</li>';
	}
	$theme['body'] .= '
								</ul>';
	foreach($member as $m) {
		$theme['body'] .= '
								<div id="permissions-'.$m['membergroup_id'].'"><br />';
		foreach($perms['types'] as $perm => $value) {
			$permname = ucwords(str_replace('_', ' ', $perm));
			$inputvalue = $perms['default'][$perm][$m['membergroup_id']];
			if(!is_array($value)) {
				if($inputvalue == 1) {
					$colour = 'red';
					$comment = 'No';
				} elseif($inputvalue == 2) {
					$colour = 'green';
					$comment = 'Yes';
				}
			} else {
				if($inputvalue == 1) {
					$colour = 'red';
					$comment = 'No';
				} elseif($inputvalue == 2) {
					$colour = 'orange';
					$comment = 'Own';
				} elseif($inputvalue == 3) {
					$colour = 'green';
					$comment = 'Any';
				}
			}
			$theme['body'] .= '<div>
									'.$permname.': <span class="slider'.(is_array($value) ? '2' : '').'value" style="color: '.$colour.';">
										'.$comment.'
									</span>
									<div class="slider'.(is_array($value) ? '2' : '').' '.$perm.'"></div>
									<input type="hidden" name="permission['.$m['membergroup_id'].']['.$perm.']" value="'.$inputvalue.'" />
								</div>';
		}
		$theme['body'] .= '
								</div>';
	}
	$theme['body'] .= '
						</td>
					</tr>
	  				<tr>
						<td class="admin-subtitlebg">
							'.$ADMIN_LANG["topic_creation"].'
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="sticky" />
							'.$ADMIN_LANG["sticky_topic"].'
						</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="lock" />
								'.$ADMIN_LANG["lock_topic"].'
							</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="'.$ADMIN_LANG["btn_add_board"].'" name="submit" />
							<input name="Reset" type="reset" value="'.$ADMIN_LANG["btn_reset"].'" />
						</td>
					</tr>
				</table>
			'.theme('end_content').'
		</div>
	</form>';
}
?>