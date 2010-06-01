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
  if (!(defined("IN_ECMS"))) {
      die("Hacking Attempt...");
  }
  //check if the type is set and if the file exists
  if (isset($_GET['type']) && file_exists('./feeds/' . $_GET['type'] . '.php')) {
      //set $feeds as an array
      $feeds = array();
      //lets include it
      include('./feeds/' . $_GET['type'] . '.php');
      //now we check the export type
      //if it is not set then we will use rss2 by default
      if (!isset($_GET['export'])) {
          $_GET['export'] = 'rss2';
      }
      switch ($_GET['export']) {
          //make the default rss 2 for when the export value is not valid
          default:
              case'rss2';
              header("Content-type: text/xml");
              echo '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="' . $atom_link . '&amp;export=rss2" rel="self" type="application/rss+xml" />
<generator>eoCMS</generator>
<title>' . $settings['site_name'] . '</title>
<description>Latest Information from ' . $settings['site_name'] . '</description>
<link>' . $settings['site_url'] . '</link>';
              //now we go through the items
              foreach ($feeds as $item) {
                  echo '<item>
<link><![CDATA[' . $item['link'] . ']]></link>
<title><![CDATA[' . $item['title'] . ']]></title>
<description><![CDATA[' . $item['description'] . ']]></description>
<category><![CDATA[' . $item['category'] . ']]></category>
<pubDate>' . date("r", $item['date']) . '</pubDate>
<guid><![CDATA[' . $item['guid'] . ']]></guid>
</item>';
              }
              echo '
</channel>
</rss>';
              break;
              break;
              case'rss';
              header("Content-type: text/xml");
              echo '<?xml version="1.0" encoding="UTF-8"?><rss version="0.92">
<channel>
<generator>eoCMS</generator>
<title>' . $settings['site_name'] . '</title>
<description>Latest Information from ' . $settings['site_name'] . '</description>
<link>' . $settings['site_url'] . '</link>';
              //now we go through the items
              foreach ($feeds as $item) {
                  echo '<item>
<link><![CDATA[' . $item['link'] . ']]></link>
<title><![CDATA[' . $item['title'] . ']]></title>
<description><![CDATA[' . $item['description'] . ']]></description>
<category><![CDATA[' . $item['category'] . ']]></category>
<pubDate>' . date("r", $item['date']) . '</pubDate>
<guid><![CDATA[' . $item['guid'] . ']]></guid>
</item>';
              }
              echo '
</channel>
</rss>';
              break;
              case'json';
              header("content-type: application/x-javascript");
              $json = 'var feed = [';
              $i = 0;
              foreach ($feeds as $item) {
                  $json .= '{
    title: \'' . urlencode($item['title']) . '\',
    link: \'' . urlencode($item['link']) . '\',
    date: \'' . date('r', $item['date']) . '\',
    description: \'' . urlencode($item['description']) . '\',
    category \'' . urlencode($item['category']) . '\'
  }';
              }
              $json = str_replace("}{", "}, {", $json);
              echo $json . '];';
              break;
      }
  }
  //if the file does not exist then display an error message
  else {
      echo 'The type specified is not valid';
  }
?>