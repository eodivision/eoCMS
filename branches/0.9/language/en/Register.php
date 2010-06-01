<?php
/**
 * Register language file.
 * English Version
 * Preliminary Version : 1.00
 * 16/04/09 - Paul Wratt
 */

$REGISTER_LANG = array();

$REGISTER_LANG["error_die"]           = 'You do not have permission to view this page!';
$REGISTER_LANG["error_die_no_reg"]    = 'Registration has been disabled, please try again later.';

$REGISTER_LANG["title"]               = 'Register';

$REGISTER_LANG["theme_title"]         = 'Success!';
$REGISTER_LANG["body_content"]        = 'Thank you for registering.  You may now login.';
$REGISTER_LANG["body_content_admin"]  = 'Thank you for registering.  An admin will activate your account soon.';
$REGISTER_LANG["body_content_email"]  = 'Thank you for registering.  In order for your account to become active, you must verify your E-mail address.  We have sent you an E-mail to '.(isset($_POST['email']) ? $_POST['email'] : '').' containing  a link to activate your account.  Once clicked, your account will be activated. Please check your junk mail or spam folder if you do not receive the email';

$REGISTER_LANG["username"]            = 'Username';
$REGISTER_LANG["password"]            = 'Password';
$REGISTER_LANG["verify_password"]     = 'Verify Password';
$REGISTER_LANG["email_address"]       = 'Email Address';

$REGISTER_LANG["title_listen"]        = 'Listen to audio version';
$REGISTER_LANG["title_audio"]         = 'Audio Vesion';
$REGISTER_LANG["title_reload"]        = 'Reload Image';
$REGISTER_LANG["image_text"]          = 'Enter the text in the image';

$REGISTER_LANG["tos"]                 = 'Terms of Service';
$REGISTER_LANG["tos_agree"]           = 'I Agree';

$REGISTER_LANG["btn_register"]        = 'Register';

?>