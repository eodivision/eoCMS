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
function addpoll($id, $type, $question, $options, $token) {
	global $user, $error, $error_die;
	call('checktoken', $token);
	if(!$user['create_poll']) {
		$error[] = 'You do not have permission to create a poll';
		return false;
	}
	if(empty($question)) {
		$error[] = 'Please enter a question';
		return false;
	}
	if(!is_array($options)) { //oh poop, possible hacker
		$error_die[] = 'Invalid	poll';
		return false;
	}
	if(empty($options) || count($options) < 2) { //cant have a poll with only one choice!
		$error[] = 'Please enter at least 2 options';
		return false;
	}
	if(!errors()) {
		//insert the poll
		$sql = call('sql_query', "INSERT INTO polls (poll_type, type_id, question, author, author_id, ip, post_time) VALUES ('$type', '$id', '$message', '" . $user['user'] . "', '".$user['id']."', '" . call('visitor_ip') . "', '" . time() . "' ) ");
		$poll_id = call('sql_insert_id'); //get the poll id
		$option_id = 1; //set the first option id to 1, helps us to indentify which option is which
		foreach($options as $option) {
			//run through all the options and insert them
			$query = call('sql_query', "INSERT INTO poll_options (poll_id, option_id, label) VALUES ('$poll_id', '$option_id', '$option')");
			$option_id++; //increment the option_id
		}
		if($sql)
			return true;
	}
}
?>