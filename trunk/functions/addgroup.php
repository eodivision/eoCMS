<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
  function addgroup($stuff, $name, $image, $colour) {
$sql = call('sql_query', "INSERT INTO membergroups (name, image, colour) VALUES('$name', '$image', '$colour')");
$group_id = call('sql_insert_id');
$val = call('sql_query', "SELECT * FROM permissions WHERE membergroup_id = '4'");
while($r = call('sql_fetch_array', $val)) {
$stuff[$r['variable']] = $stuff[$r['variable']] == 'on' ? 1 : 0; 
}
foreach($stuff as $key => $value) {
$sql = call('sql_query', "INSERT INTO permissions (membergroup_id, variable, value) VALUES ('$group_id', '$key', '$value')");
}
return true;
}
?>