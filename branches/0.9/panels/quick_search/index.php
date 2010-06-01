<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

    QuickSearch Panel - 04/06/09 - Paul Wratt
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

$head .= '
<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>
<script type="text/javascript">
// check function insertion, mat clash with "ready"
$(document).ready(function(){
	$("#qs_search").focus(function(){
		if($("#qs_search").val()=="'.$PANEL_LANG['qs_text_search_for'].'")
			$("#qs_search").val("");
	});
});
</script>';

$body .= '<div class="panel-header">'.theme('title', $PANEL_LANG['qs_title']).'</div>'.theme('start_content_panel').'
<div id="qs_panel"><form id="quick_search" class="search-form qs-form" method="get" action=""><input type="hidden" name="act" value="search">
<table id="qs_area" class="search-area qs-area" border="0" cellpadding="0" cellspacing="0">
  <tr id="qs_row" class="qs-row">
    <td id="qs_input_area" class="qs-input-area"><input id="qs_hidden" type="hidden" value=""><input id="qs_search" class="text search-text qs-text" type="text" name="search" value="'.$PANEL_LANG['qs_text_search_for'].'" /></td>
  </tr>
  <tr id="qs_row" class="qs-row">
    <td id="qs_input_area" class="qs-input-area"><input id="qs_submit" class="button search-btn qs-btn" type="submit" value="'.$PANEL_LANG['qs_btn_go'].'" /></td>
  </tr>
</table>
</form></div>'.theme('end_content');
?>