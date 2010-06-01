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

function footer()
{
global $settings, $_QUERIES, $begintime;
	# output timings
	$time = explode(" ", microtime());
	$time = $time[1] + $time[0];
	$endtime = $time; # define end time
	$totaltime = ($endtime - $begintime); # decrease to get total time
	$totaltime = round($totaltime, 2);
	$body = '</div><br class="clearfloat" />
  <div class="footerbg"><div class="footer-right"><div class="footer-left"><div class="footer">' . (isset($settings['footer'])? html_entity_decode(htmlspecialchars_decode($settings['footer'], ENT_QUOTES), ENT_QUOTES):'') . '<p>Powered By <a href="http://eocms.com" target="_blank">eoCMS</a> Copyright &copy; 2007-' . date('Y') . '
</p></div></div></div></div></div></div></div>
<p><div class="body-bottom"><div style="position:relative; float: right; margin-right: 3px;"></div>
  </body>
</html>';
return $body;
}
?>
