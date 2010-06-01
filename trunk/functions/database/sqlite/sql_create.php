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
if($col_defaults) $defaults = explode(",",$col_defaults);

$coma = "";
$data  = "CREATE TABLE `".$table_name."` (";
for($col_num=0;$col_num<count($cols);$col_num++){
	$data .= $coma."\r\n";
	$data .= " ".$cols[$col_num]."  ";
	if($col_num==0 && $primary_key){
		if(in_array($cols[$col_num],$pkeys)){
			if(strpos(strtoupper($defs[$col_num]),'INT')) $data .= " INTEGER PRIMARY KEY";
			else $data .= $defs[$col_num];
		}
	}else{
		$data .= $defs[$col_num];
		if($unique_keys && in_array($cols[$col_num],$ukeys)){
			$data .= " UNIQUE ";
		}elseif($col_defaults){
			if($defaults[$col_num]!='') {
				$data .= " DEFAULT '".$defaults[$col_num]."' ";
			}
		}
	}
	$coma = ",";
}
$data .= "\r\n);\r\n\r\n";
if(defined('DB_DEBUG')) { if(DB_DEBUG) echo $data; }
else $sql = mysql_query($data);
?>