<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
$title = $USERSEARCH_LANG["title"];
$head = '<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.js"></script><script type="text/javascript">function AddUser(username) {
var to = window.opener.$("#' . $_GET['area'] . '").val()
if(to != "") {
window.opener.$("#' . $_GET['area'] . '").val(to + ", " +username);
}
else {
window.opener.$("#' . $_GET['area'] . '").val(username);
}
}</script>';
$body = $USERSEARCH_LANG["body_content"].': <form method="get" action="index.php?act=finduser"><input type="hidden" name="area" value="' . $_GET['area'] . '" /><input type="hidden" name="act" value="finduser" /><input type="text" name="search" /><br /><input type="submit" value="'.$USERSEARCH_LANG["btn_search"].'" /></form>';
if(isset($_GET['search'])) {
$fetch = call('searchuser', $_GET['search']);
if($fetch != false) {
$body .= '<table><tr>
    <td class="titlebg" width="1%">'.$USERSEARCH_LANG["results"].'</td></tr>';
foreach($fetch as $r){
  $body .= '<tr>
    <td><a href="#" onclick="AddUser(\'' . $r['user'] . '\')">' . $r['user'] . '</a></td>
  </tr>';
}
$body .= '</table>';
}
else {
$body .= $USERSEARCH_LANG["body_content_none"];
}
}
?>