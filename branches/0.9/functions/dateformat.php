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