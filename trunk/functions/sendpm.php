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

function sendpm($to, $title, $message, $token, $draft='', $id='') {
	global $user, $SENDPM_LANG, $error, $error_die;
	call('checktoken', $token);
	if (empty($to) && $draft=='') {
		$error[] = $FUNCTIONS_LANG["e_pm_specify_username"];
		return false;
	}
	if (empty($title) && $draft=='') {
		$error[] = $FUNCTIONS_LANG["e_pm_specify_subject"];
		return false;
	}
	if (empty($message) && $draft=='') {
		$error[] = $FUNCTIONS_LANG["e_pm_specify_message"];
		return false;
	}
	if (!$user['send_pms'] && $draft=='') {
		$error_die[] = $FUNCTIONS_LANG["e_pm_send_permissions"];
		return false;
	}
	if (!errors() && $draft=='') {
		$username = explode(", ", $to);
		if($username != false) {
			$first = 0;
			foreach ($username as $recipient) {
				++$first;
				$sql = call('sql_query', "SELECT id FROM users WHERE user = '$recipient'");
				if(call('sql_num_rows', $sql) == 0) {
					$error[] = $recipient . ': ' . $FUNCTIONS_LANG["e_pm_invalid_user"];
					return false;
				} else {
					$fetch = call('sql_fetch_array',$sql);
					if($first==1 && $id!='') {
						$sql = call('sql_query', "UPDATE pm set to_send='" . $fetch['id'] . "', title='$title', message='$message', time_sent='" . time() . "', mark_sent='1', mark_delete='0' WHERE id='" . $id . "'");
					} else {
						$sql_2 = call('sql_query', "INSERT INTO pm (to_send, sender, title, message, time_sent, mark_sent) VALUES ('" . $fetch['id'] . "', '".$user['id']."', '$title', '$message', '" . time() . "', '1')");
					}
				}
			}
		} else {
			$sql = call('sql_query', "SELECT id FROM users WHERE user = '$to'");
			$fetch = call('sql_fetch_array',$sql);
			if($id!=''){
				$sql_2 = call('sql_query', "INSERT INTO pm (to_send, sender, title, message, time_sent, mark_sent) VALUES ('" . $fetch['id'] . "', '".$user['id']."', '$title', '$message', '" . time() . "', '1')");
			} else {
				$sql = call('sql_query', "UPDATE pm set to_send='" . $fetch['id'] . "', title='$title', message='$message', time_sent='" . time() . "', mark_sent='1', mark_delete='0' WHERE id='" . $id . "'");
			}
		}
		if($sql_2) {
			return true;
		}
	} elseif (!errors() && $draft!='') {
		if($id=='') {
			$sql = call('sql_query', "INSERT INTO pm (to_send, sender, title, message, time_sent, mark_delete) VALUES ('$to', '".$user['id']."', '$title', '$message', '" . time() . "', '1')");
		} else  {
			$sql = call('sql_query', "UPDATE pm set to_send='$to', title='$title', message='$message', time_sent='" . time() . "' WHERE id='" . $id . "'");
		}
		if($sql) {
			return true;
		}
	}
}
?>