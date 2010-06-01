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
echo '<fieldset><legend><b>MySQL '.$INSTALLER_LANG['legend_settings'].'</b></legend><table width="100%">
<tr><td colspan="2">&nbsp;</b></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['HOST'].' :</td><td align="right"><input type="text" value="localhost" id="host" /></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['USER'].' :</td><td align="right"><input type="text" id="user" /></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['PASS'].' :</td><td align="right"><input type="password" id="pass" /></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['NAME'].' :</td><td align="right"><input type="text" value="eocms" id="db" /><input type="hidden" value="'.$_REQUEST['db'].'" id="dbtype" /></td></tr>
<tr><td colspan="2"><a onclick="step(\'2\');">'.$INSTALLER_LANG['back'].'</a> <a onclick="step(\'4\');">'.$INSTALLER_LANG['next'].'</a></b></td></tr>
</table></fieldset>';
?>