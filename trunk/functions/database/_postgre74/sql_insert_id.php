<?php
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

    PostgreSQL support by Paul Wratt - 2009/02/19
*/
function pg_insert_id($pg,$query){// $pg - string connection, $query - sql command
	$regExp = preg_match_all("/nextval\('([a-zA-Z0-9_]+)'\)/",$query,$array);
	$sequence = $array[1][0];
	$select = "SELECT currval('$sequence')";
	$load = pg_query($pg,$select);
	$id = pg_fetch_array($load,null,PGSQL_NUM);
	return $id[0];
}
$sql = pg_insert_id($con,$data);
?>