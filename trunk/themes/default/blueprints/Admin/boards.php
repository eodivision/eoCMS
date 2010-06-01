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
$theme['title'] = $ADMIN_LANG["title"];
$theme['js'] = '
$(function() {
	oTable = $("#boardslist").dataTable({
		"bJQueryUI": true,
		"bPaginate": false,
		"bAutoWidth": false,
		"fnDrawCallback": function ( oSettings ) {
			if(oSettings.aiDisplay.length == 0)
				return;			
			var nTrs = $("#boardslist tbody tr");
			var iColspan = nTrs[0].getElementsByTagName("td").length;
			var sLastGroup = "";
			for ( var i=0 ; i<nTrs.length ; i++ )
			{
				var iDisplayIndex = oSettings._iDisplayStart + i;
				var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
				if ( sGroup != sLastGroup )
				{
					var nGroup = document.createElement( "tr" );
					var nCell = document.createElement( "td" );
					nCell.colSpan = iColspan;
					nCell.className = "group";
					nCell.innerHTML = sGroup;
					nGroup.appendChild( nCell );
					nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
					sLastGroup = sGroup;
				}
			}
		},
		"aoColumns": [
			{ "bVisible": false },
			null,
			null,
			null
		],
		"aaSortingFixed": [[ 0, "asc" ]],
		"aaSorting": [[ 1, "asc" ]],
		"sDom": \'lfr<"giveHeight"t>ip\'
	});
	$(".boards").validate();
	$(".permissions").tabs();
	/*$(".permissions").jCarouselLite({
		btnNext: ".next",
		btnPrev: ".prev",
		visible: "3",
		circular: false
	});*/
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
//complicated query and whiles to get the forum categories and its boards
$query = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order ASC");
$cats = array();
if(call('sql_num_rows', $query) != 0) {
	while ($p = call('sql_fetch_array', $query)) {
		$count = call('sql_num_rows', call('sql_query', "SELECT id FROM forum_boards WHERE cat = '" . $p['id'] . "'"));
		$cats[$p['id']] = array('cat_id' => $p['id'], 'cat_name' => $p['cat_name'], 'board_count' => $count, 'cat_count' => call('sql_num_rows', $query),'boards' => array());
	}
	$sql = call('sql_query', "SELECT * FROM forum_boards ORDER BY item_order");
	while($r = call('sql_fetch_assoc', $sql)) {
		$cats[$r['cat']]['boards'][$r['id']] = array('id' => $r['id'], 'board_name' => $r['board_name'], 'board_description' => $r['board_description']);
	}
}
	$theme['body'] = '
		<ul>
			<li><a href="#tabs-1">List</a></li>
			<li><a href="#tabs-2">New</a></li>
			<li><a href="#tabs-3">Categories</a></li>
		</ul>
		<div id="tabs-1">
			<table align="center" id="boardslist" class="display">
				<thead>
					<tr>
						<th>Category</th>
						<th>ID</th>
						<th>Name</th>
						<th>Description</th>				
					</tr>
				</thead>
				<tbody>';
	$catcount = call('sql_num_rows', $query);
	if($catcount == 0)
		$theme['body'] .= '
					<tr>
						<td colspan="4">
							No Boards
						</td>
					</tr>';
	else {
		foreach($cats as $cat) {
			foreach ($cat['boards'] as $p) {
				$theme['body'] .= '
					<tr id="'.$p['id'].'">
						<td>
							'.$cats[$p['id']]['cat_name'].'
						</td>
						<td>
							'.$p['id'].'
						</td>
						<td>
							<a href="'.$settings['site_url'].'/index.php?act=viewboard&amp;id='.$p['id'].'" target="_blank">
								'.$p['board_name'] . '
							</a>
						</td>
						<td>
							<span class="small-text">
								'. $p['board_description'].'
							</span>
						</td>
					</tr>';
			}
		}
	}
$theme['body'] .= '
				</tbody>
			</table>
		</div>
		<div id="tabs-2">
		<form action="" method="post" class="boards">
			<fieldset class="ui-widget ui-widget-content ui-corner-all">
				<legend class="ui-widget ui-widget-header ui-corner-all">Board Information</legend>
				<ul>
					<li>
						<label for="boardName">'.$ADMIN_LANG["board_name"].':</label>
						<input type="text" name="board_name" id="boardName" class="required" />
					</li>
					<li>
						<label for="boardDescription">'.$ADMIN_LANG["board_description"].':</label>
						<textarea name="board_description" id="boardDescription" cols="36" rows="2" class="required"></textarea>
					</li>
					<li>
						<label for="boardCategory">'.$ADMIN_LANG["board_category"].':</label> 
						<select id="boardCategory" name="category">';
	foreach ($cats as $cat) {
		$theme['body'] .= '
							<option value="'.$cat['cat_id'].'">
								'.$cat['cat_name'].'
							</option>';
	}
	$theme['body'] .= '
						</select>
					</li>
			  	</ul>
			</fieldset>
			<fieldset class="ui-widget ui-widget-content ui-corner-all">
				<legend class="ui-widget ui-widget-header ui-corner-all">'.$ADMIN_LANG["board_permissions"].'</legend>
					<ul>
						<li>
							<div class="permissions" style="max-width: 200px; overflow: hidden; float: left; z-index: 1001;">
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
								</ul>
							</div>';
	foreach($member as $m) {
		$theme['body'] .= '
							<div id="permissions-'.$m['membergroup_id'].'">
								<table class="permissions ui-widget-content ui-corner-all" style="display: block; width: 100%;">
									<thead>
										<tr>
											<th>Setting</th>
											<th style="text-align: center;">Yes</th>
											<th style="text-align: center;">No</th>
										</tr>
									</thead>
									<tbody>';
		foreach($perms as $perm => $value) {
			$permname = ucwords(str_replace('_', ' ', $perm));
			$inputvalue = $perms[$perm][$m['membergroup_id']];
			$theme['body'] .= '
										<tr>
											<td style="width: 450px;">
												'.$permname.':
											</td> 
											<td style="text-align: center;" class="yes">
												<label for="permission['.$m['membergroup_id'].']['.$perm.']_yes">
													<input type="radio" id="permission['.$m['membergroup_id'].']['.$perm.']_yes" name="permission['.$m['membergroup_id'].']['.$perm.']" value="2" '.($inputvalue == 2 ? 'checked="checked" ' : '').'/>
												</label>
											</td>
											<td style="text-align: center;" class="no">
												<label for="permission['.$m['membergroup_id'].']['.$perm.']_no">
													<input type="radio" id="permission['.$m['membergroup_id'].']['.$perm.']_no" name="permission['.$m['membergroup_id'].']['.$perm.']" value="1" '.($inputvalue == 1 ? 'checked="checked" ' : '').'/>
												</label>
											</td>
										</tr>';
		}
		$theme['body'] .= '
									</tbody>
								</table>
							</div>';
	}
	$theme['body'] .= '
						</li>
					</ul>
				</fieldset>
				<fieldset class="ui-widget ui-widget-content ui-corner-all">
					<legend class="ui-widget ui-widget-header ui-corner-all">'.$ADMIN_LANG["topic_creation"].'</legend>
					<ul>
						<li>
							<label for="sticky">'.$ADMIN_LANG["sticky_topic"].'</label>
							<input type="checkbox" id="sticky" name="sticky" />
						</li>
						<li>
							<label for="lock">'.$ADMIN_LANG["lock_topic"].'</label>
							<input type="checkbox" id="lock" name="lock" />
						</li>
					</ul>
				</fieldset>
				<fieldset class="submit">
					<input type="submit" value="'.$ADMIN_LANG["btn_add_board"].'" name="submit" />
					<input name="Reset" type="reset" value="'.$ADMIN_LANG["btn_reset"].'" />
				</fieldset>
			</form>
			</div>';
?>