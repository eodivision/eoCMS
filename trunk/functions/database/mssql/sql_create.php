<?php
  if(!defined('DB_TYPE')) exit;
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, version 3 of the License

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    Microsoft SQL Server support by Paul Wratt - 2009/02/19
*/
if(defined('DB_PREFIX')) $dbo = DB_PREFIX;
else $dbo = "[dbo]";

$data  = "IF EXISTS (SELECT * FROM sysobjects WHERE id = object_id(N'".$dbo.".[".$table_name."]')\r\n";
$data .= "AND OBJECTPROPERTY(id, N'IsUserTable') = 1)\r\n";
$data .= "DROP TABLE ".$dbo.".[".$table_name."];\r\n";
if(defined('DB_DEBUG')) { if(DB_DEBUG) echo $data; }
else $sql = mysql_query($data);

$cols = explode(",",$col_names);
$defs = explode(",",$col_defs);
if($primary_keys) $pkeys = explode(",",$primary_keys);
if($unique_keys) $ukeys = explode(",",$unique_keys);
if($col_null) $null = explode(",",$col_null);
if($col_defaults) $defaults = explode(",",$col_defaults);
if($optimized_4_search) $optimize = explode(",",$optimized_4_search);

$coma = "";
$data  = "CREATE ".$dbo.".[".$table_name."] (";
for($col_num=0;$col_num<count($cols);$col_num++){
	$data .= $coma."\r\n";
	$data .= " ".$cols[$col_num]."  ";
	$data .= $defs[$col_num];
	if($col_null) { if(in_array($cols[$col_num],$null)) $data .= " NULL "; }
	else $data .= " NOT NULL ";
	if($primary_key) {
		if(in_array($cols[$col_num],$pkeys)) {
			if(strpos(strtoupper($defs[$col_num]),'INT')) $data .= " IDENTITY(1,1) PRIMARY KEY CLUSTERED ";
			else $data .= " CONSTRAINT PK_".$cols[$col_num]." PRIMARY KEY UNCLUSTERED ";
		}
	}
	if($col_defaults) {
		if($defaults[$col_num]!='') {
			if(strpos(strtoupper($defs[$col_num]),'INT')) $data .= " DEFAULT (".$defaults[$col_num].") ";
			elseif(strpos(strtoupper($defs[$col_num]),'GETDATE')) $data .= " DEFAULT (getdate()) ";
			elseif(strpos(strtoupper($defs[$col_num]),'GETTIME')) $data .= " DEFAULT (gettime()) ";
			else $data .= " DEFAULT ('".$defaults[$col_num]."') ";
		}
	}
	$coma = ",";
}
if($unique_keys) $data .= $coma."\r\n CONSTRAINT U_".$table_name." UNIQUE NONCLUSTERED (".implode(',',$ukeys).") ";
$data .= "\r\n);\r\n\r\n";
if(defined('DB_DEBUG')) { if(DB_DEBUG) echo $data; }
else $sql = mysql_query($data);
?>