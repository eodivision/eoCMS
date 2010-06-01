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
$databaseinfo =  "$"."db['host'] = '".$_REQUEST['host']."';\n";
$databaseinfo .= "$"."db['user'] = '".$_REQUEST['user']."';\n";
$databaseinfo .= "$"."db['pass'] = '".$_REQUEST['pass']."';\n";
$databaseinfo .= "$"."db['name'] = '".$_REQUEST['db']."';\n";
$databaseinfo .= "$"."con = "."mysql_connect("."$"."db['host'],"." $"."db['user'],"." $"."db['pass']);\n";
$databaseinfo .= "if(!"."$"."con)\n";
$databaseinfo .= "\t die('eoCMS was unable to connect to the database');\n";
$databaseinfo .= "$"."select = "."mysql_select_db("."$"."db['name']);\n";
$databaseinfo .= "if(!"."$"."select)\n";
$databaseinfo .= "\tdie('eoCMS was unable to select to the database'."."$"."db['name']);\n";
$databaseinfo .= "unset("."$"."db);";
?>