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

function output_popup($head = '', $body = '', $title = '')
{
	echo theme('head', stripslashes($title), $head) . '<body>' . theme('start_content') . stripslashes($body) . theme('end_content') . '</body></html>';
}
?>