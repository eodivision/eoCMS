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
'.$totaltime.' seconds Queries: '.$_QUERIES.'
  </body>
</html>';
return $body;
}
?>