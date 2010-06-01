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

   Modified by Paul Wratt - 02/03/2009 - (dynamic) multiple database support
*/
$data .= "DROP TABLE IF EXISTS `".$table_name."`;\r\n";
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
$data  = "CREATE TABLE `".$table_name."` (";
for($col_num=0;$col_num<count($cols);$col_num++){
	$data .= $coma."\r\n";
	$data .= " `".$cols[$col_num]."`  ";
	$data .= $defs[$col_num];
	if($optimized_4_search) {
		if(in_array($cols[$col_num],$optimize)){
			if(strpos(strtoupper($defs[$col_num]),'CHAR')) $data .= " collate latin1_general_ci ";
		}
	}
	if($col_null) { if(in_array($cols[$col_num],$null)) $data .= " NULL "; }
	else $data .= " NOT NULL ";
	if($primary_key){
		if(in_array($cols[$col_num],$pkeys)){
			if(strpos(strtoupper($defs[$col_num]),'INT')) $data .= " AUTO_INCREMENT ";
		}
	}
	if($col_defaults) {
		if($defaults[$col_num]!='') {
			if(strpos(strtoupper($defs[$col_num]),'GETDATE')) $data .= " DEFAULT (getdate()) ";
			elseif(strpos(strtoupper($defs[$col_num]),'GETTIME')) $data .= " DEFAULT (gettime()) ";
			else $data .= " DEFAULT '".$defaults[$col_num]."' ";
		}
	}
	$coma = ",";
}
if($primary_keys) $data .= $coma."\r\n PRIMARY KEY (`".implode('`,`',$pkeys)."`) ";
if($unique_keys) $data .= $coma."\r\n KEY (`".implode('`,`',$ukeys)."`) ";
if($hivolume) $data .= "\r\n) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
else $data .= "\r\n) ENGINE=MyISAM DEFAULT CHARSET=latin1;\r\n\r\n";
if(defined('DB_DEBUG')) { if(DB_DEBUG) echo $data; }
else $sql = mysql_query($data);
?>