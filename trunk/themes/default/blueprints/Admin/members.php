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
$sql = call('sql_query', "SELECT u.id, u.email, m.name, u.ip, u.regdate, u.lastlogin, u.posts, u.user FROM users u LEFT JOIN membergroups m ON membergroup_id=membergroup ORDER BY id ASC");
while($r = call('sql_fetch_array', $sql)) {
		$users[] = array(
					'id' => $r[0],
					'username' => $r[7],
					'email' => $r[1],
					'name' => $r[2],
					'ip' => $r[3],
					'regdate' => date('M j o', $r[4]),
					'lastlogin' => (is_numeric($r[5]) ? date('M j o', $r[5]) : 'Never'),
					'posts' => $r[6]);
}
$theme['title'] = 'Members List';
$theme['head'] = '
					<script type="text/javascript" src="'.$settings['site_url'].'/js/jquery.dataTables.min.js"></script>';
$theme['head_js'] = '
				$(function() {
					 $("#'.$theme['window_id'].' .admin-table tbody tr").click(function() {
						if($(this).hasClass("row_selected") )
							$(this).removeClass("row_selected");
						else
							$(this).addClass("row_selected");
					});
					usertable_'.$theme['window_id'].' = $("#'.$theme['window_id'].' .admin-table").dataTable();
					$("#'.$theme['window_id'].'").width($("#'.$theme['window_id'].' .admin-table").width());
					$("#'.$theme['window_id'].' .window_title_text").width("100%");
				});
				function GetSelectedRow(oTableLocal) {
					var aReturn = new Array();
					var aTrs = oTableLocal.fnGetNodes();
					for (var i=0 ; i<aTrs.length ; i++)	{
						if($(aTrs[i]).hasClass("row_selected")) {
							aReturn.push(aTrs[i]);
						}
					}
					return aReturn;
				}';
$theme['body'] = '
		<div class="admin-panel">
			'.theme('start_content').'
				<table class="admin-table admin-edit admin-delete">
					<thead>
						<tr class="admin-subtitlebg">
							<th>ID</th>
							<th>Username</th>
							<th>Email Address</th>
							<th>Membergroup</th>
							<th>IP Address</th>
							<th>Date Registered</th>
							<th>Last Online</th>
							<th>Posts</th>							
						</tr>
					</thead>
					<tbody>';
foreach($users as $p) {
	$theme['body'] .= '
						<tr>
							<td>'.$p['id'].'</td>
							<td>'.$p['username'].'</td>
							<td>'.$p['email'].'</td>
							<td>'.$p['name'].'</td>
							<td>'.$p['ip'].'</td>
							<td>'.$p['regdate'].'</td>
							<td>'.$p['lastlogin'].'</td>
							<td>'.$p['posts'].'</td>							
						</tr>';
}
$theme['body'] .= '
					</tbody>
					<tfoot>
						<tr>
							<td><div class="delete-icon-fade" title="Delete Selected">
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			'.theme('end_content').'
		</div>';
?>