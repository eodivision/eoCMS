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

function bdateformat($data,$today=true) {
	global $user, $settings, $FUNCTIONS_LANG;
# Today's date
	$tday = date('d/F/Y');
# Yesterday's date
	$yday = date('d/F/Y', strtotime('-1 day'));
# Tomorrow's date
	$mday = date('d/F/Y', strtotime('+1 day'));
# Date in question
	$rowdate = date('d/F/Y', $data);
# Month according to language and settings
	$month = (isset($settings['date_month']) ? $settings['date_month'] : 'l');
	$langmonth = $FUNCTIONS_LANG['d_'.$month.'_'.date('n', $data)];
# output date format
	$format = (isset($settings['date_format']) ? $settings['date_format'] : 'X jS, Y');
	$format = str_replace('F','X',$format);
	$format = str_replace('M','X',$format);
# Display date
	if ($rowdate==$tday && $today){
		$ddate = '<b>' . $FUNCTIONS_LANG['d_today'] . '</b>';
	} elseif($rowdate==$yday && $today){
		$ddate = '<b>' . $FUNCTIONS_LANG['d_yesterday'] . '</b>';
	} elseif($rowdate==$mday && $today){
		$ddate = '<b>' . $FUNCTIONS_LANG['d_tomorrow'] . '</b>';
	} else {
		$ddate = str_replace('X',$langmonth,date($format, $data));
	}
	return $ddate;
}
?>