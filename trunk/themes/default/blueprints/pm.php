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

if(isset($_GET['opt']) && $_GET['opt'] == 'delete') {
	$check = call('deletepm', $_GET['id']);
	if($check==true && !errors()) {
		call('redirect', 'index.php?act=pm'.(isset($_GET['box']) ? '&box='.$_GET['box'] : ''));
	} else {
		$error[] = $PM_LANG["error_delete"];
	}
}
$theme['head'] = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/pms.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#select_all").click(function() {
					var checked_status = this.checked;
					$("input[name=\'pms[]\']").each(function() {
						this.checked = checked_status;
						if(this.checked)
							$("#pm_"+this.value).addClass("inline");
						else
							$("#pm_"+this.value).removeClass("inline");
					});
				});
				$("#checked").change(function() {
					$("#withselected").submit();
				});
			});
			function pmselect(id) {
				$("#pm_"+id).toggleClass("inline");
			}
		</script>';
if(isset($_POST['pms'])) {
	foreach($_POST['pms'] as $delete) {
		$moderation = call('deletepm', $delete);
	}
}
if(!isset($_GET['box']) || empty($_GET['box']) || $_GET['box'] == 'in') {
	$theme['title'] = $PM_LANG["title"];
	if($user['guest']) {
		$error_die[] = $PM_LANG["error_die"];
	}
	$theme['body'] = theme('title', $PM_LANG["theme_title_inbox"]).theme('start_content').'
										<table class="pm-tools-top">
											<tr>
												<td class="imagebutton" align="left">
													<a href="'.$settings['site_url'].'/index.php?act=sendpm" title=" '.$PM_LANG["t_new_message"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_new_message"])).'</a>
												</td>
												<td class="imagebutton" align="left">
													<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=out" title=" '.$PM_LANG["t_messages_sent"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_outbox"])).'</a>
												</td>
												<td class="imagebutton" align="left">
													<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=draft" title=" '.$PM_LANG["t_draft_messages"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_drafts"])).'</a>
												</td>
											</tr>
										</table>
										<div id="showmessage"></div>
										<form method="post" action="" id="withselected">
											<table border="0" width="100%" cellpadding="0" cellspacing="0">
												<tr class="titlebg">
													<td width="1%">
														<input type="checkbox" id="select_all" />
													</td>
													<th>
														'.$PM_LANG["subject"].'
													</th>
													<th>
														'.$PM_LANG["date"].'
													</th>
													<th>
														'.$PM_LANG["from"].'
													</th>
													<th>
														'.$PM_LANG["options"].'
													</th>
												</tr>';
	$fetch = call('listpms', $user['id']);
	if($fetch==false) {
		$theme['body'] .= '
												<tr class="subtitlebg">
													<td colspan="5">
														'.$PM_LANG["no_messages"].'
													</td>
												</tr>';
	} else {
		foreach($fetch as $p) {
			$theme['body'] .= '
												<tr class="subtitlebg" id="pm_'.$p['id'].'">
													<td>
														<input type="checkbox" name="pms[]" value="'.$p['id'].'" onclick="pmselect(\''.$p['id'].'\');" value="'.$p['id'].'" />
													</td>
													<td>
														<a onclick="showPM(\''.$p['id'].'\');" href="javascript: void(0)">'.$p['title'].'</a>'.($p['mark_read']=='0' ? ' NEW' : '').'
													</td>
													<td>
														<span class="small-text">'.call('dateformat', $p['time_sent']).'</span>
													</td>
													<td>
														'.call('userprofilelink', $p['sender']).'
													</td>
													<td>
														<a href="'.$settings['site_url'].'/index.php?act=pm&amp;id=' . $p['id'] . '&amp;opt=delete" title=" '.$PM_LANG["t_delete"].' " onclick="return confirm(\''.$PM_LANG["js_h_delete"].'\')">
															<img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" style="border:none;" alt=" '.$PM_LANG["t_delete"].' " />
														</a>
														<span class=image-list-spacer>&nbsp;&nbsp;</span>
													</td>
												</tr>';
		}
	}
	$pagination = call('pagination', isset($_GET['page']), 20, 'SELECT COUNT(id) AS numrows FROM pm WHERE to_send = "'.$user['id'].'" AND mark_delete = "0"', '?act=pm&page=', 3);
	$theme['body'] .= '
												<tr>
													<td colspan="3">
														'.$pagination.'
													</td>
													<td colspan="2" align="right">
														<select name="checked" id="checked">
															<option value="">'.$PM_LANG["o_with_selected"].'</option>
															<option value="delete">'.$PM_LANG["o_delete"].'</option>
														</select>
													</td>
												</tr>
											</table>
											<table class="pm-tools-bottom">
												<tr>
													<td class="imagebutton" align="left">
														<a href="'.$settings['site_url'].'/index.php?act=sendpm" title=" '.$PM_LANG["t_new_message"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_new_message"])).'</a>
													</td>
													<td class="imagebutton" align="left">
														<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=out" title=" '.$PM_LANG["t_messages_sent"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_outbox"])).'</a>
													</td>
													<td class="imagebutton" align="left">
														<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=draft" title=" '.$PM_LANG["t_draft_messages"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_drafts"])).'</a>
													</td>
												</tr>
											</table>
										</form>'.theme('end_content');
} elseif(isset($_GET['box']) && $_GET['box']=='out') {
	$theme['title'] = $PM_LANG["theme_title_outbox"];
	if($user['guest']) {
		$error_die[] = $PM_LANG["error_die"];
	}
	$theme['body'] = theme('title', $PM_LANG["theme_title_outbox"]).theme('start_content').'
								<table class="pm-tools-top">
									<tr>
										<td class="imagebutton" align="left">
											<a href="'.$settings['site_url'].'/index.php?act=sendpm" title=" '.$PM_LANG["t_new_message"].' ">
											'.(str_replace(' ','&nbsp;',$PM_LANG["btn_new_message"])).'</a>
										</td>
										<td class="imagebutton" align="left">
											<a href="'.$settings['site_url'].'/index.php?act=pm" title=" '.$PM_LANG["t_messages_received"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_inbox"])).'</a>
										</td>
										<td class="imagebutton" align="left">
											<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=draft" title=" '.$PM_LANG["t_draft_messages"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_drafts"])).'</a>
										</td>
									</tr>
								</table>
								<div id="showmessage"></div>
								<form method="post" action="" id="withselected">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr class="titlebg">
											<td width="1%">
												<input type="checkbox" id="select_all" />
											</td>
											<th>
												'.$PM_LANG["subject"].'</th>
											<th>
												'.$PM_LANG["date"].'
											</th>
											<th>
												'.$PM_LANG["to"].'
											</th>
											<th>
												'.$PM_LANG["options"].'
											</th>
										</tr>';
	$fetch = call('listoutpms', $user['id']);
	if($fetch==false){
		$theme['body'] .= '
										<tr class="subtitlebg">
											<td colspan="5">
												'.$PM_LANG["no_messages"].'
											</td>
										</tr>';
	} else {
		foreach($fetch as $p) {
			$theme['body'] .= '
										<tr class="subtitlebg" id="pm_'.$p['id'].'">
											<td>
												<input type="checkbox" name="pms[]" value="'.$p['id'].'" onclick="pmselect(\''.$p['id'].'\');" value="'.$p['id'].'" />
											</td>
											<td>
												<a onclick="showPM(\'' . $p['id'] . '\');" href="javascript: void(0)">' . $p['title'] . '</a>'.($p['mark_read']=='0' ? ' NOT READ' : '').'
											</td>
											<td>
												<span class="small-text">' . call('dateformat', $p['time_sent']) . '</span>
											</td>
											<td>
												'.call('userprofilelink', $p['to_send']).'
											</td>
											<td>
												<a href="'.$settings['site_url'].'/index.php?act=pm&amp;id=' . $p['id'] . '&amp;opt=delete" title=" '.$PM_LANG["t_delete"].' " onclick="return confirm(\''.$PM_LANG["js_h_delete"].'\')">
													<img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" style="border:none;" alt="'.$PM_LANG["t_delete"].'" />
												</a>
												<span class=image-list-spacer>&nbsp;&nbsp;</span>
											</td>
										</tr>';
		}
	}
	$pagination = call('pagination', isset($_GET['page']), 20, 'SELECT COUNT(id) AS numrows FROM pm WHERE sender = "'.$user['id'].'" AND mark_sent_delete = "0"', '?act=pm&box=out&page=', 3);
	$theme['body'] .= '
										<tr>
											<td colspan="4">
												'.$pagination.'
											</td>
											<td>
												<select name="checked" id="checked">
													<option value="">'.$PM_LANG["o_with_selected"].'</option>
													<option value="delete">'.$PM_LANG["o_delete"].'</option>
												</select>
											</td>
										</tr>
									</table>
									<table class="pm-tools-bottom">
										<tr>
											<td class="imagebutton" align="left">
												<a href="'.$settings['site_url'].'/index.php?act=sendpm" title=" '.$PM_LANG["t_new_message"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_new_message"])).'</a>
											</td>
											<td class="imagebutton" align="left">
												<a href="'.$settings['site_url'].'/index.php?act=pm" title=" '.$PM_LANG["t_messages_received"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_inbox"])).'</a>
											</td>
											<td class="imagebutton" align="left">
												<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=draft" title=" '.$PM_LANG["t_draft_messages"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_drafts"])).'</a>
											</td>
										</tr>
									</table>
								</form>'.theme('end_content');
} elseif(isset($_GET['box']) && $_GET['box']=='draft') {
	$theme['title'] = $PM_LANG["theme_title_drafts"];
	if($user['guest']) {
		$error_die[] = $PM_LANG["error_die"];
	}
	$theme['body'] = theme('title', $PM_LANG["theme_title_drafts"]).theme('start_content').'
								<table class="pm-tools-top">
									<tr>
										<td class="imagebutton" align="left"><a href="'.$settings['site_url'].'/index.php?act=sendpm" title=" '.$PM_LANG["t_new_message"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_new_message"])).'</a></td>
										<td class="imagebutton" align="left"><a href="'.$settings['site_url'].'/index.php?act=pm" title=" '.$PM_LANG["t_messages_received"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_inbox"])).'</a></td>
										<td class="imagebutton" align="left"><a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=out" title=" '.$PM_LANG["t_messages_sent"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_outbox"])).'</a></td>
									</tr>
								</table>
								<div id="showmessage"></div>
								<form method="post" action="" id="withselected">
									<table border="0" width="100%" cellpadding="0" cellspacing="0">
										<tr class="titlebg">
											<td width="1%">
												<input type="checkbox" id="select_all" />
											</td>
											<th>
												'.$PM_LANG["subject"].'
											</th>
											<th>
												'.$PM_LANG["saved"].'
											</th>
											<th>
												'.$PM_LANG["to"].'
											</th>
											<th>
												'.$PM_LANG["options"].'
											</th>
										</tr>';
	$fetch = call('listdraftpms', $user['id']);
	if($fetch==false) {
		$theme['body'] .= '
										<tr class="subtitlebg">
											<td colspan="5">
												'.$PM_LANG["no_messages"].'
											</td>
										</tr>';
	} else {
		foreach($fetch as $p) {
			$theme['body'] .= '
										<tr class="subtitlebg" id="pm_'.$p['id'].'">
											<td>
												<input type="checkbox" name="pms[]" value="'.$p['id'].'" onclick="pmselect(\''.$p['id'].'\');" value="'.$p['id'].'" />
											</td>
											<td>
												<a onclick="showPM(\''.$p['id'].',draft\');" href="javascript: void(0)">'.$p['title'].'</a>
											</td>
											<td>
												<span class="small-text">'.call('dateformat', $p['time_sent']).'</span>
											</td>
											<td>
												'.$p['to_send'].'
											</td>
											<td>
												<a href="'.$settings['site_url'].'/index.php?act=pm&amp;id=' . $p['id'] . '&amp;opt=delete" title=" '.$PM_LANG["t_delete"].' " onclick="return confirm(\''.$PM_LANG["js_h_delete"].'\')">
													<img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/delete.png" style="border:none;" alt="'.$PM_LANG["t_delete"].'" />
												</a>
												<span class=image-list-spacer>&nbsp;&nbsp;</span>
												<a href="'.$settings['site_url'].'/index.php?act=sendpm&amp;id=' . $p['id'] . '&amp;opt=edit" title=" '.$PM_LANG["t_edit"].'">
													<img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/listedit.png" style="border:none;" alt="'.$PM_LANG["t_edit"].'" />
												</a>
											</td>
										</tr>';
		}
	}
	$pagination = call('pagination', isset($_GET['page']), 20, 'SELECT COUNT(id) AS numrows FROM pm WHERE sender = "'.$user['id'].'" AND mark_sent_delete = "0"  AND mark_sent = "0"', '?act=pm&box=draft&page=', 3);
	$theme['body'] .= '
										<tr>
											<td colspan="4">
												'.$pagination.'
											</td>
											<td>
												<select name="checked" id="checked">
													<option value="">'.$PM_LANG["o_with_selected"].'</option>
													<option value="delete">'.$PM_LANG["o_delete"].'</option>
												</select>
											</td>
										</tr>
									</table>
									<table class="pm-tools-bottom">
										<tr>
											<td class="imagebutton" align="left">
												<a href="'.$settings['site_url'].'/index.php?act=sendpm" title=" '.$PM_LANG["t_new_message"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_new_message"])).'</a>
											</td>
											<td class="imagebutton" align="left">
												<a href="'.$settings['site_url'].'/index.php?act=pm" title=" '.$PM_LANG["t_messages_received"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_inbox"])).'</a>
											</td>
											<td class="imagebutton" align="left">
												<a href="'.$settings['site_url'].'/index.php?act=pm&amp;box=out" title=" '.$PM_LANG["t_messages_sent"].' ">'.(str_replace(' ','&nbsp;',$PM_LANG["btn_outbox"])).'</a>
											</td>
										</tr>
									</table>
								</form>'.theme('end_content');
}
?>