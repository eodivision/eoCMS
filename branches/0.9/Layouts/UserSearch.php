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