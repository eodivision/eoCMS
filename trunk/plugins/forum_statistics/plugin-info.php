<?php
/*eoCMS Plugin
Version: 0.8
Date Created: 8th August 2009
Copyright info below
*/
$plugin['name'] = 'Forum Statistics';
$plugin['version'] = '0.2';
$plugin['eocms_version'] = '0.9.0';
$plugin['layout'] = '';
$plugin['layout_include'] = array('after' => array('forum' => 'Stats.php'));
$plugin['admin']['control'] = '';
$plugin['author']['name'] = 'confuser';
$plugin['author']['site'] = 'http://eocms.com';
$plugin['description']['short'] = 'Display statistical information underneath the forum index';
$plugin['description']['long'] = 'Display statistical information underneath the forum index';
?>