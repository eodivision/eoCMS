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
//javascript connection step
//mysql
$js =  'if(dbtype == "mysql") {
			var user = $("#user").val();
			var pass = $("#pass").val();
			var host = $("#host").val();
			$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=connection&dbtype="+dbtype+"&host="+host+"&user="+user+"&pass="+pass+"&db="+db, success: function(data){if(data == "'.$INSTALLER_LANG["error_connect"].'"){var d = $("#install").html(); $("#install").html("<span style=\"color:red;\"><b>'.$INSTALLER_LANG["error_connect"].'</b></span>"+d); $("#ajax").slideToggle();} if(data == 1){$("#install").html("'.$INSTALLER_LANG["js_config"].'");$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=config&dbtype="+dbtype+"&host="+host+"&user="+user+"&pass="+pass+"&db="+db, success: function(data){$("#install").html(data); $("#ajax").slideToggle();}});}
			}});
		}';
//sqlite
$js .= 'if($("#dbtype").val() == "sqlite") {
			$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=connection&dbtype="+dbtype+"&location="+db, 
success: function(data){if(data == "'.$INSTALLER_LANG["error_connect"].'"){var d = $("#install").html(); $("#install").html("<span style=\"color:red;\"><b>'.$INSTALLER_LANG["error_connect"].'</b></span>"+d);} if(data == 1){$("#install").html("'.$INSTALLER_LANG["js_config"].'");$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=config&dbtype="+dbtype+"&user="+user+"&pass="+pass+"&db="+db, success: function(data){$("#install").html(data); $("#ajax").slideToggle();}});}
			}});
		}';

?>