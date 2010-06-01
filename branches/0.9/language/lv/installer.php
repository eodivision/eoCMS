<?php
/**
 * Installer language file.
 * Latvian Version
 * Preliminary Version : 1.00
 * 29/03/09 - Eva http://eva.fromhell.lv
 */


$INSTALLER_LANG = array();

$INSTALLER_LANG["failed_connect"]       = 'Nesanāca konektēties datu bāzei';
$INSTALLER_LANG["failed_database"]      = 'Neizdevās izvēlēties datu bāzi';

$INSTALLER_LANG["install_eocms"]        = 'Instalēt eoCMS';
$INSTALLER_LANG["install_eocms_title"]  = 'Instalēt eoCMS';
$INSTALLER_LANG["install_check"]        = 'Instalācijas pārbaude';
$INSTALLER_LANG["legend_permissions"]   = 'Atļaujas';
$INSTALLER_LANG["config_writable"]      = 'Konfigurācija Rakstāma';
$INSTALLER_LANG["directory_writable"]   = 'Direktorija Rakstāma';
$INSTALLER_LANG["cache_writable"]       = 'Cache Rakstāma';
$INSTALLER_LANG["php_version"]          = 'PHP Versija';
$INSTALLER_LANG["use_captcha"]          = 'Lietot Captcha';
$INSTALLER_LANG["needs_gd"]             = 'ir vajadzīga GD library';
$INSTALLER_LANG["passed"]               = 'IR ok';
$INSTALLER_LANG["failed"]               = 'Neizdevās';
$INSTALLER_LANG["try_again"]            = 'Mēģināt Velreiz';
$INSTALLER_LANG["next"]                 = 'Tālāk';
$INSTALLER_LANG["back"]                 = 'Atpakaļ';
$INSTALLER_LANG["refresh"]              = 'Atjaunot';
$INSTALLER_LANG["retry"]                = 'Atkārtot';
$INSTALLER_LANG["delete"]               = 'Dzēst';
$INSTALLER_LANG["continue"]             = 'Turpināt';
$INSTALLER_LANG["warning"]              = 'Brīdinājums';
$INSTALLER_LANG["install"]              = 'Instalēt';
$INSTALLER_LANG["write_config"]         = 'Ierakstīt konfigurāciju failā';

$INSTALLER_LANG["legend_defaults"]      = 'Standarta';
$INSTALLER_LANG["language"]             = 'Valoda';
$INSTALLER_LANG["installation"]         = 'Instalācija';
$INSTALLER_LANG["child_board"]          = 'Forums';
$INSTALLER_LANG["first_time"]           = 'Atstājiet tukšu ja instalējiet pirmo reizi';

$INSTALLER_LANG["database_selection"]   = 'Datubāzes Izvēle';
$INSTALLER_LANG["legend_databases"]     = 'Datubāzes';
$INSTALLER_LANG["choose_database"]      = 'Izvēlieties datubāzi ko izmantos <b>eoCMS</b>';
$INSTALLER_LANG["databases"]            = 'datubāze';
$INSTALLER_LANG["not_installed"]        = 'neienstelējās';
$INSTALLER_LANG["not_supported"]        = 'nav atbalstīts';
$INSTALLER_LANG["js_not_installed"]     = 'Izvēlētās datubāzes atbalsts vel netiek atbalstīts ar PHP\\nContinue?';
$INSTALLER_LANG["js_not_supported"]     = 'Support for this database is currently unfinished\\nIf you would like to contribute to, or request an\\nunsupported database, go to';
$INSTALLER_LANG["js_choose_database"]   = 'Lūdzu izvēlaties datubāzi';
$INSTALLER_LANG["function_cant_read"]   = 'neizdevās izlasīt datubāzes atbalstu';

$INSTALLER_LANG["database_settings"]    = 'Settings';
$INSTALLER_LANG["legend_settings"]      = 'Settings';
$INSTALLER_LANG["HOST"]                 = 'Hostname';
$INSTALLER_LANG["USER"]                 = 'Username';
$INSTALLER_LANG["PASS"]                 = 'Password';
$INSTALLER_LANG["NAME"]                 = 'Database';
$INSTALLER_LANG["PREFIX"]               = 'Prefix';
$INSTALLER_LANG["LOCATION"]             = 'Database';

$INSTALLER_LANG["verification"]         = 'Verification';
$INSTALLER_LANG["legend_error"]         = 'Kļūda';
$INSTALLER_LANG["error_check_install"]  = 'Pātbaudiet jūsu instalāciju priekš eoCMS';
$INSTALLER_LANG["error_check_perm"]     = 'check the file and folder permissions of your installation of eoCMS';
$INSTALLER_LANG["error_cant_read_conf"] = 'Neizdevās izlasīt datubāzes Konfigurāciju';
$INSTALLER_LANG["legend_testing"]       = 'Datubāzes tests';
$INSTALLER_LANG["error_cant_test"]      = 'Unable to test Database Configuration';
$INSTALLER_LANG["error_no_test"]        = 'No tests found for Database Connection';
$INSTALLER_LANG["db_connection"]        = 'Savienojums';
$INSTALLER_LANG["db_database"]          = 'Datubāzi';
$INSTALLER_LANG["legend_version"]       = 'Database Version';
$INSTALLER_LANG["db_version"]           = 'Versija';
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

?>