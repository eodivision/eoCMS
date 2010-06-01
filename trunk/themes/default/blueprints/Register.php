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
if(!$user['guest'])
	$error_die[] = $REGISTER_LANG["error_die"];
$theme['title'] = $REGISTER_LANG["title"];
if($settings['registration'] != 'on')
	$error_die[] = $REGISTER_LANG["error_die_no_reg"];
$theme['body'] =  theme('title', $REGISTER_LANG["btn_register"]).theme('start_content');
form('register',
	array(
		'submit' => $REGISTER_LANG['btn_register'],
		'callback' => array('function' => 'adduser'),
		'success' => function() {
			global $settings;
			if($settings['register_approval'] == 'email')
				$theme['body'] = theme('title', $REGISTER_LANG["theme_title"]).theme('start_content').$REGISTER_LANG["body_content_email"].theme('end_content');
			elseif($settings['register_approval'] == 'admin')
				$theme['body'] = theme('title', $REGISTER_LANG["theme_title"]).theme('start_content').$REGISTER_LANG["body_content_admin"].theme('end_content');
			elseif($settings['register_approval'] == 'none')
				$theme['body'] = theme('title', $REGISTER_LANG["theme_title"]).theme('start_content').$REGISTER_LANG["body_content"].theme('end_content');
		}
	),
	array(
		'username' => array(
			'type' => 'text',
			'label' => $REGISTER_LANG['username'],
			'validation' => array(
				'min' => '1',
				'max' => '16',
				'disallow' => ',',
				'decode' => true,
				'remote' => array('url' => 'index.php?act=ajax&m=usernamecheck', 'failure' => $REGISTER_LANG["username_taken"]),
				'sql' => array(
					'query' => "SELECT user FROM users WHERE user = '<this.username>'",
					'maxrows' => 0,
					'failure' => $REGISTER_LANG["username_taken"]
				)
			)
		),
		'password' => array(
			'type' => 'password',
			'label' => $REGISTER_LANG['password'],
			'validation' => array(
				'min' => 6
			)
		),
		'vpassword' => array(
			'type' => 'password',
			'label' => $REGISTER_LANG['verify_password'],
			'validation' => array(
				'min' => 6,
				'equalto' => 'password'
			)
		),
		'email' => array(
			'type' => 'email',
			'label' => $REGISTER_LANG['email_address'],
			'validation' => array(
				'sql' => array(
					'query' => "SELECT email FROM users WHERE email = '<this.email>'", 
					'maxrows' => 0,
					'failure' => $REGISTER_LANG["email_taken"]
				)
			)
		),
		'tos' => array(
			'type' => 'checkbox',
			'validation' => array(
				'required' => true
			),
			'show' => empty($settings['tos']) ? false : true,
			'html' => '
				<tr class="subtitlebg">
					<td align="center" colspan="2">
						'.$REGISTER_LANG["tos"].'
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="width: 100%; overflow: auto; max-height: 10em;">
							'.htmlspecialchars_decode($settings['tos']).'
						</div>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						'.$REGISTER_LANG["tos_agree"].' 
						<input type="checkbox" name="tos" />
					</td>
				</tr>'
		),
		'captcha' => array(
			'type' => 'captcha'
		)
	)
);
$theme['body'] .= theme('end_content');
?>