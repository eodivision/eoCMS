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
if(function_exists('mssql_rows_affected')){
	$sql = mssql_rows_affected($con);
}else{
	$result = mssql_query("SELECT @@ROWCOUNT AS rows", $con);
	$rows = mssql_fetch_assoc($result);
	$sql = $rows['rows'];
	mssql_free_result($result);
	mssql_free_result($rows);
}
?>