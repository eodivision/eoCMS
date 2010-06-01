<?php
/**
 * Installer language file.
 * English Version
 * Preliminary Version : 1.00
 * 19/03/09 - Paul Wratt
 */

$INSTALLER_LANG = array();

$INSTALLER_LANG["failed_connect"]       = 'eoCMS was unable to connect to the database';
$INSTALLER_LANG["failed_database"]      = 'eoCMS was unable to select the database';

$INSTALLER_LANG["install_eocms"]        = 'Install eoCMS';
$INSTALLER_LANG["prev_install"]			= 'Previous eoCMS installation detected that was not finished';
$INSTALLER_LANG["continue_install"]		= 'Continue?';
$INSTALLER_LANG["start_again"]			= 'Start again?';
$INSTALLER_LANG["install_eocms_title"]  = 'Install eoCMS';
$INSTALLER_LANG["install_check"]        = 'Installation Check';
$INSTALLER_LANG["legend_permissions"]   = 'Permissions';
$INSTALLER_LANG["config_writable"]      = 'Config Writable';
$INSTALLER_LANG["directory_writable"]   = 'Directory Writable';
$INSTALLER_LANG["sendmail"]   			= 'Sendmail';
$INSTALLER_LANG["fsockopen"]   			= 'SMTP';
$INSTALLER_LANG["cache_writable"]       = 'Cache Writable';
$INSTALLER_LANG["tables_writable"]       = 'Cache/tables.php Writable';
$INSTALLER_LANG["php_version"]          = 'PHP Version';
$INSTALLER_LANG["use_captcha"]          = 'Use Captcha';
$INSTALLER_LANG["needs_gd"]             = 'needs GD library';
$INSTALLER_LANG["passed"]               = 'Passed';
$INSTALLER_LANG["failed"]               = 'Failed';
$INSTALLER_LANG["try_again"]            = 'Try Again';
$INSTALLER_LANG["not_required"]   		= 'Not Required';
$INSTALLER_LANG["next"]                 = 'Next';
$INSTALLER_LANG["back"]                 = 'Back';
$INSTALLER_LANG["refresh"]              = 'Refresh';
$INSTALLER_LANG["retry"]                = 'Retry';
$INSTALLER_LANG["delete"]               = 'Delete';
$INSTALLER_LANG["continue"]             = 'Continue';
$INSTALLER_LANG["warning"]              = 'Warning';
$INSTALLER_LANG["install"]              = 'Install';
$INSTALLER_LANG["write_config"]         = 'Write Configuration to File';

$INSTALLER_LANG["legend_defaults"]      = 'Defaults';
$INSTALLER_LANG["language"]             = 'Language';
$INSTALLER_LANG["installation"]         = 'Installation';
$INSTALLER_LANG["child_board"]          = 'Child Board';
$INSTALLER_LANG["first_time"]           = 'leave blank for first time installation';

$INSTALLER_LANG["database_selection"]   = 'Database Selection';
$INSTALLER_LANG["legend_databases"]     = 'Databases';
$INSTALLER_LANG["choose_database"]      = 'Choose a Database Type to use with <b>eoCMS</b>';
$INSTALLER_LANG["databases"]            = 'Databases';
$INSTALLER_LANG["not_installed"]        = 'not installed';
$INSTALLER_LANG["not_supported"]        = 'not supported yet';
$INSTALLER_LANG["js_not_installed"]     = 'Selected database support has not been install in PHP yet\\nBefore continuing, please update your PHP Installation\\nContinue?';
$INSTALLER_LANG["js_not_supported"]     = 'Support for this database is currently unfinished\\nIf you would like to contribute to, or request an\\nunsupported database, go to';
$INSTALLER_LANG["js_choose_database"]   = 'Please choose a database';
$INSTALLER_LANG["function_cant_read"]   = 'unable to read database support';

$INSTALLER_LANG["database_settings"]    = 'Settings';
$INSTALLER_LANG["legend_settings"]      = 'Settings';
$INSTALLER_LANG["HOST"]                 = 'Hostname';
$INSTALLER_LANG["USER"]                 = 'Username';
$INSTALLER_LANG["PASS"]                 = 'Password';
$INSTALLER_LANG["NAME"]                 = 'Database';
$INSTALLER_LANG["PREFIX"]               = 'Prefix';
$INSTALLER_LANG["LOCATION"]             = 'Database';

$INSTALLER_LANG["error_connect"]		= 'Unable to connect to the database';

$INSTALLER_LANG["verification"]         = 'Verification';
$INSTALLER_LANG["legend_error"]         = 'Error';
$INSTALLER_LANG["error_check_install"]  = 'check your installation of eoCMS';
$INSTALLER_LANG["error_check_perm"]     = 'check the file and folder permissions of your installation of eoCMS';
$INSTALLER_LANG["error_cant_read_conf"] = 'Unable to read Database Configuration';
$INSTALLER_LANG["legend_testing"]       = 'Database Tests';
$INSTALLER_LANG["error_cant_test"]      = 'Unable to test Database Configuration';
$INSTALLER_LANG["error_no_test"]        = 'No tests found for Database Connection';
$INSTALLER_LANG["db_connection"]        = 'Connection';
$INSTALLER_LANG["db_database"]          = 'Database';
$INSTALLER_LANG["legend_version"]       = 'Database Version';
$INSTALLER_LANG["db_version"]           = 'Version';
$INSTALLER_LANG["error_cant_version"]   = 'Unable to check the version of selected Database';
$INSTALLER_LANG["error_no_version"]     = 'No tests found for Database Version';
$INSTALLER_LANG["error_version_error"]  = 'A problem was encountered while checking Database Version';
$INSTALLER_LANG["js_double_check"]      = 'Please make sure there is NO RED TEXT on this page before continuing\\nIf either Connection test, or Database test failed, this will also be true\\nwhen the configuration is written to file and will therefore fail on startup\\nIf this happens and you wish to redo the installation, you will need to\\nempty the contents of the appropriate "config.php"\\n\\nAre you sure you want to write the Configuration file?';

$INSTALLER_LANG["configuration"]        = 'Configuration';
$INSTALLER_LANG["error_cant_write_conf"]= 'Unable to write Database Configuration';
$INSTALLER_LANG["legend_success"]       = 'Success';
$INSTALLER_LANG["db_config_write"]      = 'Database Configuration written to file';

$INSTALLER_LANG["db_structure"]         = 'Database Structure';
$INSTALLER_LANG["error_db_read_file"]   = 'Unable to read Database Structure';
$INSTALLER_LANG["legend_creating"]      = 'Creating';
$INSTALLER_LANG["error_db_file_size"]   = 'Unable to read Database Structure Size';
$INSTALLER_LANG["file_size"]            = 'File Size';
$INSTALLER_LANG["bytes"]                = 'bytes';
$INSTALLER_LANG["error_db_process"]     = 'Unable to create Database Structure';
$INSTALLER_LANG["error_check_sql"]      = 'check the SQL syntax';
$INSTALLER_LANG["sql_lines"]            = 'Lines Processed';
$INSTALLER_LANG["lines"]                = 'lines';
$INSTALLER_LANG["line"]                 = 'line';
$INSTALLER_LANG["sql_statements"]       = 'Statements Processed';
$INSTALLER_LANG["statements"]           = 'statements';
$INSTALLER_LANG["sql_failed"]           = 'Statements Failed';
$INSTALLER_LANG["legend_sql_errors"]    = 'SQL Errors';

$INSTALLER_LANG["db_default_settings"]  = 'Default Settings';
$INSTALLER_LANG["db_defaults"]          = 'Database Defaults';
$INSTALLER_LANG["legend_enter_defaults"]= 'Enter Default Settings';
$INSTALLER_LANG["defaults_site_name"]   = 'Site Name';
$INSTALLER_LANG["defaults_url"]         = 'URL to where eoCMS is installed';
$INSTALLER_LANG["defaults_check"]       = 'no trailing / and include http://';
$INSTALLER_LANG["defaults_admin_user"]  = 'Admin Username';
$INSTALLER_LANG["defaults_admin_pass"]  = 'Admin Password';
$INSTALLER_LANG["defaults_verify_pass"] = 'Verify Password';
$INSTALLER_LANG["defaults_admin_email"] = 'Admin Email';

$INSTALLER_LANG["def_err_site_name"]    = 'You did not enter a name for your site';
$INSTALLER_LANG["def_err_url"]          = 'You did not enter a url to where eoCMS is being installed';
$INSTALLER_LANG["def_err_user"]         = 'You did not enter a username';
$INSTALLER_LANG["def_err_pass"]         = 'password must be 6 characters or longer!';
$INSTALLER_LANG["def_err_admin"]        = 'Your username is too long, it must be below 16 characters';
$INSTALLER_LANG["def_err_match"]        = 'The passwords entered to do not match';
$INSTALLER_LANG["def_err_email"]        = 'The email address entered is not valid';

$INSTALLER_LANG["error_db_read_def"]    = 'Unable to read Database Defaults';
$INSTALLER_LANG["legend_inserting"]     = 'Inserting';
$INSTALLER_LANG["error_db_def_size"]    = 'Unable to read Database Defaults Size';
$INSTALLER_LANG["error_db_insert_def"]  = 'Unable to insert Database Defaults';

$INSTALLER_LANG["install_finished"]     = 'Installation has finished. Thank you for using eoCMS. Please delete <i>install.php</i> from your server for security reasons. You can now access your site, however you can automatically delete <i>install.php</i> and go to your site by clicking one of the following.';
$INSTALLER_LANG["continue"]             = 'Continue to Site';
$INSTALLER_LANG["continue_delete"]      = 'Delete and Continue to Site';

$INSTALLER_LANG['error_db_redo']        = 'Due to errors while trying to insert your information into the database, it now needs to be replaced with a clean installation. This requires going back a step or two.';
$INSTALLER_LANG["redo_db"]              = 'Replace Database';

$INSTALLER_LANG["js_perms"]				= 'Checking Permissions';
$INSTALLER_LANG["js_dbtypes"]			= 'Grabbing Available Database Types';
$INSTALLER_LANG["js_config"]			= 'Creating config';

?>