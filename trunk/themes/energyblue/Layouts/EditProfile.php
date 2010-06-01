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

if($user['guest']) {
	$error_die[] = 'You must be logged in to use this feature';
}
$PROFILE_LANG = call('uselangfile','profile');
$fetch = call('displayuserinfo', $user['id']);
$members = call('sql_fetch_array',call('sql_query', "SELECT name, image FROM membergroups WHERE membergroup_id = '" . $fetch['membergroup'] ."'"));
$title = $EDITPROFILE_LANG["title"];
$check = false;
$body = theme('title', $EDITPROFILE_LANG["theme_title"]) . theme('start_content') . '<div id="editprofileLinks" class="editprofile-links" align="center">
	[&nbsp;<a id="editAccLink" class="text-link editprofile-link editprofile-account-link" href="'.$settings['site_url'].'/index.php?act=editaccount"><strong>' . str_replace(' ','&nbsp;',$EDITPROFILE_LANG["account_settings"]) . '</strong></a>&nbsp;] 
	[&nbsp;<a id="editMiscLink" class="text-link editprofile-link editprofile-misc-link" href="'.$settings['site_url'].'/index.php?act=editmiscellaneous"><strong>' . str_replace(' ','&nbsp;',$EDITPROFILE_LANG["misc_settings"]) . '</strong></a>&nbsp;] [&nbsp;<a id="editLookLink" class="text-link editprofile-link editprofile-look-link" href="'.$settings['site_url'].'/index.php?act=editlook"><strong>' . str_replace(' ','&nbsp;',$EDITPROFILE_LANG["look_settings"]) . '</strong></a>&nbsp;]
	'.($user['admin_panel'] ? ' [&nbsp;<a id="editAdminLink" class="text-link editprofile-link editprofile-admin-link" href="'.$settings['site_url'].'/index.php?act=userpreferances"><strong>' . str_replace(' ','&nbsp;',$EDITPROFILE_LANG["user_settings"]) . '</strong></a>&nbsp;]' : '').'</div>';
if(!empty($fetch['birthday'])) {
	list($day, $month, $year) = explode("/", $fetch['birthday']);
	$age = date('Y') - $year;
	if(date('m')<$month)
		$age--;
	elseif(date('m')==$month && date('d')<$day)
		$age--;
} else
	$age = '';
if($fetch['offset'] >= 0)
	$localtime = time() + ($fetch['offset'] * 3600);
else
	$localtime = time() - (str_replace("-", "", $fetch['offset']) * 3600);
$localtime = date('h:i:s A', $localtime);
$days = floor($fetch['time_online'] / 86400);
$hours = floor(($fetch['time_online'] % 86400) / 3600);
$total_time = '';
if($days > 0)
	$total_time .= $days.$PROFILE_LANG["time_online_days"];
if($hours > 0)
	$total_time .= $hours.$PROFILE_LANG["time_online_hours"];
$total_time .= floor(($fetch['time_online'] % 3600) / 60) . $PROFILE_LANG["time_online_mins"];
$body .= '<table id="profile" width="100%" class="profile-area">
<tr>
	<td class="profile-avatar-contact" width="10%" valign="top"><div class="profile-username">'.$fetch['user'].'</div>
		<div class="profile-rank"><img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/'.$members['image'].'" alt="'.$members['name'].'" title="'.$members['name'].'" /></div>
		<div class="profile-avatar-area">'.(!empty($fetch['avatar']) ? '<img id="profileAvatarImg" src="'.$fetch['avatar'].'" alt="'.$PROFILE_LANG["avatar"].'" class="profile-avatar-img" />' : '').'</div>
		<div class="contact-info">'.($fetch['show_email'] == '1' ? call('hide_email', $fetch['email'], '', '', true) : '').
		(!empty($fetch['icq']) ? '<a href="http://www.icq.com/whitepages/about_me.php?uin=' . $fetch['icq'] . '" target="_blank" class="link link-img profile-contact-icq"><img src="http://status.icq.com/online.gif?img=5&amp;icq=' . $fetch['icq'] . '" alt="' . $fetch['icq'] . '" width="18" height="18" border="0" class="profile-contact-img profile-contact-icq-img" /></a>' : '').
		(!empty($fetch['aim']) ? '<a href="aim:goim?screenname=' . urlencode(strtr($fetch['aim'], array(' ' => '%20'))) . '&amp;message=Hello" target="_blank" class="link link-img profile-contact-aim"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_aim.gif" border="0" class="profile-contact-img profile-contact-aim-img" /></a>' : '').
		(!empty($fetch['yim']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . urlencode($fetch['yim']) . '" target="_blank" class="link link-img profile-contact-yim"><img src="http://opi.yahoo.com/online?u=' . urlencode($fetch['yim']) . '&amp;m=g&amp;t=0" alt="' . $fetch['yim'] . '" border="0" class="profile-contact-img profile-contact-yim-img" /></a>' : '').
		(!empty($fetch['msn']) ? '<a href="http://members.msn.com/' . $fetch['msn'] . '" target="_blank" class="link link-img profile-contact-msn"><img src="'.$settings['site_url'].'/themes/' . $settings['site_theme'] . '/images/profile_msn.gif" alt="' . $fetch['msn'] . '" border="0" class="profile-contact-img profile-contact-msn-img" /></a>' : '').' 
		</div>
		'.($fetch['posts'] != 0 ? '<div class="profile-details profile-titles profile-info-posts"><a href="'.$settings['site_url'].'/index.php?act=profile&amp;opt=showposts&amp;id='.$fetch['id'].'">'.$PROFILE_LANG['show_posts'].'</a></div><br />' : '').'</td>
		<td class="profile-details-area">
			'.(!empty($fetch['location']) ? '<div class="profile-details profile-titles profile-info-loc">'.$PROFILE_LANG["location"].':</div>
				<div class="profile-details profile-values profile-info-loc-v">' . $fetch['location'].'</div>' : '');
			if($fetch['gender']) {
				$body.='<div class="profile-details profile-titles profile-info-gender">'.$PROFILE_LANG["gender"].':</div>
              <div class="profile-details profile-values profile-info-gender-v">';
if($fetch['gender'] == '1')
   $body.='Male';
elseif($fetch['gender'] == '2')
   $body.='Female';
}
$body.='</div>
			<div class="profile-details profile-titles profile-info-reg">'.$PROFILE_LANG["date_registered"].':</div>
	  			<div class="profile-details profile-values profile-info-reg-v">'.call('dateformat', $fetch['regdate']).'</div>
			<div class="profile-details profile-titles profile-info-active">'.$PROFILE_LANG["last_active"].':</div>
	  			<div class="profile-details profile-values profile-info-active-v">'.($fetch['lastlogin']==0 ? call('dateformat', $fetch['regdate']) : call('dateformat', $fetch['lastlogin'])).'</div>
			<div class="profile-details profile-titles profile-info-posts">'.$PROFILE_LANG["forum_posts"].':</div>
	 			<div class="profile-details profile-values profile-info-posts-v">' . $fetch['posts'] . '</div>
	  		'.(!empty($fetch['birthday']) ? '<div class="profile-details profile-titles profile-info-dob">'.$PROFILE_LANG["date_of_birth"].':</div>
	  			<div class="profile-details profile-values profile-info-dob-v">'.date('jS F, Y', mktime(0,0,0,$month,$day,$year)).'</div>
			<div class="profile-details profile-titles profile-info-age">'.$PROFILE_LANG["age"].':</div>
	  			<div class="profile-details profile-values profile-info-age-v">'.$age.'</div>' : '').'
	  		<div class="profile-details profile-titles profile-info-status">'.$PROFILE_LANG["status"].':</div>
	  			<div class="online profile-details profile-values profile-info-status-v">'.call('search_online', $fetch['id']).'</div>
	  		<div class="profile-details profile-titles profile-info-time">'.$PROFILE_LANG["local_time"].':</div>
	  			<div class="text-datetime profile-details profile-values profile-info-time-v">'.$localtime.'</div>
			<div class="profile-details profile-titles profile-info-totaltime">'.$PROFILE_LANG["time_online"].':</div>
	  			<div class="text-datetime profile-details profile-values profile-info-totaltime-v">'.$total_time.'</div>
	  </td>
</tr>
</table>'.theme('end_content');
?>