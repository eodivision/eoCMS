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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if($user['admin_panel']) {
	$head .= '
	<style type="text/css">
	.panel-header .ui-icon {
		float: right;
	}
	.ui-sortable-placeholder {
		border: 1px dotted black;
		visibility: visible !important;
		height: 50px !important;
	}
	.ui-sortable-placeholder * {
		visibility: hidden;
	}
	</style>
	<script type="text/javascript" src="' . $settings['site_url'] . '/panels/quick_admin/jquery.ui.js"></script>
	<script type="text/javascript">
	<!--
	function disablepanels() {
	$(".sidebar").sortable("disable");
	$(".panel-header").css({ "cursor": "auto" });
	$("#managepanels").html(\'<a style="cursor: pointer" onclick="panels();">'.$PNL_LANG['qa_move_panels'].'</a>\');
	$(".sidebar").enableSelection();
}
	$(window).load(disablepanels);
function panels() {
	$(".sidebar").sortable("enable");
	$(".panel-header").css({ "cursor": "move" });
	$("#managepanels").html(\'<a style="cursor: pointer" onclick="disablepanels();">'.$PNL_LANG["qa_disable_move_panels"].'</a>\');
	$(".sidebar").disableSelection();
}
$(function() {
	$(".sidebar").sortable({
		connectWith: ".sidebar",
		handle: ".panel-header",
		start: function(event, ui) {
		if(ui.item.width() > "23")
			ui.item.width("23%");
		 },
		update: function(event, ui) {
			$("#ajax").html("Loading...");
			$("#ajax").slideToggle();
			var left = $("#sidebar1").sortable("serialize");
			var right = $("#sidebar2").sortable("serialize");
			var upper = $("#panel_upper").sortable("serialize");
			var lower = $("#panel_lower").sortable("serialize");
			$.ajax({type:"POST", url:"index.php?act=ajax", data:"m=order&type=panels&left="+escape(left)+"&right="+escape(right)+"&upper="+escape(upper)+"&lower="+escape(lower),
  success:function(data){
	$("#ajax").slideToggle();	
  }, dataType: "text", error: function (XMLHttpRequest, textStatus, errorThrown) {
		$("#ajax").html("'.$PNL_LANG["error_save"].' "+XMLHttpRequest.status+". '.$PNL_LANG["try_again"].'");  
  }});
    		}
		})
});
// -->
</script>';
	$body .= '<div class="panel-header">'.theme('title', $PNL_LANG['qa_title']).'</div>'.theme('start_content_panel').'<div id="managepanels"><a style="cursor: pointer" onclick="panels();">'.$PNL_LANG['qa_move_panels'].'</a></div>'.theme('end_content');
}
?>