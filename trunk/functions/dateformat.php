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

function dateformat($data,$user=true,$today=true) {
	global $user, $settings, $FUNCTIONS_LANG;
# Today's date
	if(!$user['guest'] && $user) {
		$offset = $user['offset'];
	} else {
		$offset = 0;
	}
	if ($offset>=0) {
		$tdata = $data + ($offset *3600);
	} else {
		$tdata = $data - (str_replace("-", "", $offset) *3600);
	}
	$tday = date('d/F/Y');
# Yesterday's date
	$yday = date('d/F/Y', strtotime('-1 day'));
# Tomorrow's date
	$mday = date('d/F/Y', strtotime('+1 day'));
# Date in question
	$rowdate = date('d/F/Y', $tdata);
# Date according to language and settings
	$month = (isset($settings['date_month']) ? $settings['date_month'] : 'l');
	$langmonth = $FUNCTIONS_LANG['d_'.$month.'_'.date('n', $tdata)];
	$langampm = $FUNCTIONS_LANG['d_'.date('a', $tdata)];
	if(isset($settings['date_ampm'])){
		switch($settings['date_ampm']){
		case 'l':
			$langampm = strtolower($langampm);
			break;
		case 'n':
			$langampm = '';
			break;
		}
	}
# output date format
	$format = (isset($settings['date_format']) ? $settings['date_format'] : 'X jS, Y, g:i:s x');
	$format = str_replace('F','X',$format);
	$format = str_replace('M','X',$format);
	$format = str_replace('a','x',$format);
	$format = str_replace('A','x',$format);
# Display date
	if ($rowdate==$tday && $today){
		$ddate = '<b>' . $FUNCTIONS_LANG['d_today'] . '</b> ' . $FUNCTIONS_LANG['d_at'] . ' ' . date('g:i:s ', $tdata).$langampm . '';
	} elseif($rowdate==$yday && $today){
		$ddate = '<b>' . $FUNCTIONS_LANG['d_yesterday'] . '</b> ' . $FUNCTIONS_LANG['d_at'] . ' ' . date('g:i:s ', $tdata).$langampm . '';
	} elseif($rowdate==$mday && $today){
		$ddate = '<b>' . $FUNCTIONS_LANG['d_tomorrow'] . '</b> ' . $FUNCTIONS_LANG['d_at'] . ' ' . date('g:i:s ', $tdata).$langampm . '';
	} else {
		$ddate = $ddate = str_replace('x',$langampm,str_replace('X',$langmonth,date($format, $tdata)));
	}
	return $ddate;
}
?>