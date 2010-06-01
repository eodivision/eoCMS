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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }

if(isset($_POST['submit'])) {
	$check = call('addforumcat', $_POST['cat_name']);
	if($check === true)
		$theme['success'] = theme('start_content').'Category Successfully Added'.theme('end_content');
} else {
	$query = call('sql_query', "SELECT * FROM forum_categories ORDER BY item_order ASC");
	$theme['title'] = 'New Forum Category';
	$theme['head'] = '
					<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script>';
	$theme['head_js'] = '
					$(function() {
						$("#'.$theme['window_id'].' .forumcategories").validate();
					});';
	$theme['body'] = '
	<form action="" method="post" class="forumcategories">
		<div class="admin-panel">
			'.theme('start_content').'
				<table class="admin-table">
					<tr>
  						<td>
							Name:
						</td>
  					</tr>
  					<tr>
    					<td>
							<input type="text" name="cat_name" class="required" />
						</td>
  					</tr>
  					<tr>
  						<td  colspan="2" align="center">
							<input type="submit" name="submit" value="Add Category" />
							<input name="Reset" type="reset" value="Reset" />
						</td>
  					</tr>
				</table>
			'.theme('end_content').'
		</div>
	</form>';
}
?>