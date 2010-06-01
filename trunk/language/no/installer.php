<?php
/**
 * Installer language file.
 * Norwegian Version
 * Preliminary Version : 1.00
 * 19/03/09 - Paul Wratt (http://translation.babylon.com/English/to-Norwegian)
 */

$INSTALLER_LANG = array();

$INSTALLER_LANG["failed_connect"]       = 'eoCMS var ikke i stand til å koble til database';
$INSTALLER_LANG["failed_database"]      = 'eoCMS var ikke i stand til å velge database';

$INSTALLER_LANG["install_eocms"]        = 'Installer eoCMS';
$INSTALLER_LANG["install_eocms_title"]  = 'Installer eoCMS';
$INSTALLER_LANG["install_check"]        = 'Installerings Sjekk';
$INSTALLER_LANG["legend_permissions"]   = 'Tillatelser';
$INSTALLER_LANG["config_writable"]      = 'Konfig Klikk';
$INSTALLER_LANG["directory_writable"]   = 'Katalogen Klikk';
$INSTALLER_LANG["cache_writable"]       = 'Cache Klikk';
$INSTALLER_LANG["php_version"]          = 'PHP Versjon';
$INSTALLER_LANG["use_captcha"]          = 'Bruk Captcha';
$INSTALLER_LANG["needs_gd"]             = 'trenger se bibliotek';
$INSTALLER_LANG["passed"]               = 'vedtog';
$INSTALLER_LANG["failed"]               = 'havererte';
$INSTALLER_LANG["try_again"]            = 'Prøv Igjen';
$INSTALLER_LANG["next"]                 = 'Neste';
$INSTALLER_LANG["back"]                 = 'Tilbake';
$INSTALLER_LANG["refresh"]              = 'Vederkveg';
$INSTALLER_LANG["retry"]                = 'Prøv På Nytt';
$INSTALLER_LANG["delete"]               = 'Slett';
$INSTALLER_LANG["continue"]             = 'Fortsætte';
$INSTALLER_LANG["warning"]              = 'Advarsel';
$INSTALLER_LANG["install"]              = 'Installer';
$INSTALLER_LANG["write_config"]         = 'Skrive Konfigurering til Fil';

$INSTALLER_LANG["legend_defaults"]      = 'Normalt setter';
$INSTALLER_LANG["language"]             = 'Språk';
$INSTALLER_LANG["installation"]         = 'Installasjon';
$INSTALLER_LANG["child_board"]          = 'Barnet bord';
$INSTALLER_LANG["first_time"]           = 'efterlate tom for første gang installasjon';

$INSTALLER_LANG["database_selection"]   = 'Database Markering';
$INSTALLER_LANG["legend_databases"]     = 'Databaser';
$INSTALLER_LANG["choose_database"]      = 'Velg en Database Type til bruk med <b>eoCMS</b>';
$INSTALLER_LANG["databases"]            = 'databaser';
$INSTALLER_LANG["not_installed"]        = 'ikke installert';
$INSTALLER_LANG["not_supported"]        = 'ikke støttet';
$INSTALLER_LANG["js_not_installed"]     = 'Utvalgte database støtte har ikke vært installere i PHP ennå\\nFør fortsætte, vennligst oppdatere dine PHP installasjon\\nfortsette?';
$INSTALLER_LANG["js_not_supported"]     = 'Støtte for dette database er for tiden umøblert\\nHvis du ønsker å bidra til, eller anmodning en ikke støttet database, gå til';
$INSTALLER_LANG["js_choose_database"]   = 'Vennligst velg en database';
$INSTALLER_LANG["function_cant_read"]   = 'ikke i stand til å lese database støtte';

$INSTALLER_LANG["database_settings"]    = 'Innstillinger';
$INSTALLER_LANG["legend_settings"]      = 'Innstillinger';
$INSTALLER_LANG["HOST"]                 = 'Värdnavn';
$INSTALLER_LANG["USER"]                 = 'Brukernavn';
$INSTALLER_LANG["PASS"]                 = 'Passord';
$INSTALLER_LANG["NAME"]                 = 'Database';
$INSTALLER_LANG["PREFIX"]               = 'Seg';
$INSTALLER_LANG["LOCATION"]             = 'Database';

$INSTALLER_LANG["verification"]         = 'Verifikasjonen';
$INSTALLER_LANG["legend_error"]         = 'Feil';
$INSTALLER_LANG["error_check_install"]  = 'sjekk din installasjon av eoCMS';
$INSTALLER_LANG["error_check_perm"]     = 'sjekk filen og mappe de av eders installasjon av eoCMS';
$INSTALLER_LANG["error_cant_read_conf"] = 'Ikke i stand til å lese Database Konfigurering';
$INSTALLER_LANG["legend_testing"]       = 'Database Tester';
$INSTALLER_LANG["error_cant_test"]      = 'Ikke i stand til å teste Database Konfigurering';
$INSTALLER_LANG["error_no_test"]        = 'Ingen tester funnet for Database Forbindelse';
$INSTALLER_LANG["db_connection"]        = 'Forbindelse';
$INSTALLER_LANG["db_database"]          = 'Database';
$INSTALLER_LANG["legend_version"]       = 'Database Versjon';
$INSTALLER_LANG["db_version"]           = 'Versjon';
$INSTALLER_LANG["error_cant_version"]   = 'Ikke i stand til å sjekke versjon av utvalgte Database';
$INSTALLER_LANG["error_no_version"]     = 'Ingen tester funnet for Database Versjon';
$INSTALLER_LANG["error_version_error"]  = 'Et problem ble påtruffet mens check Database Versjon';
$INSTALLER_LANG["js_double_check"]      = 'Vennligst pass på at det er ingen røde tekst på denne siden før fortsatte\\nHvis enten forbindelse test, eller Database test havererte, dette vil også\\nvære sant når de konfigurering er skrevet til fil og vil derfor mislykkes på\\noppstart. Hvis dette skjer og du ønsker å gjenta p?innretningen, er du nødt til å\\ntømme innholdet i passende "config.php".\\n\\nEr du sikker på at du ønsker å skrive den konfigurering filen?';

$INSTALLER_LANG["configuration"]        = 'Konfigurering';
$INSTALLER_LANG["error_cant_write_conf"]= 'Ikke i stand til å skrive Database Konfigurering';
$INSTALLER_LANG["legend_success"]       = 'Suksess';
$INSTALLER_LANG["db_config_write"]      = 'Database Konfigurering skrevet til fil';

$INSTALLER_LANG["db_structure"]         = 'Database Struktur';
$INSTALLER_LANG["error_db_read_file"]   = 'Ikke i stand til å lese Database Struktur';
$INSTALLER_LANG["legend_creating"]      = 'Å Skape';
$INSTALLER_LANG["error_db_file_size"]   = 'Ikke i stand til å lese Database Struktur Størrelse';
$INSTALLER_LANG["file_size"]            = 'Filstørrelse';
$INSTALLER_LANG["bytes"]                = 'bytes';
$INSTALLER_LANG["error_db_process"]     = 'Ikke i stand til å skape Database Struktur';
$INSTALLER_LANG["error_check_sql"]      = 'Sjekk de SQL å';
$INSTALLER_LANG["sql_lines"]            = 'Linjer Behandlet';
$INSTALLER_LANG["lines"]                = 'linjer';
$INSTALLER_LANG["line"]                 = 'line';
$INSTALLER_LANG["sql_statements"]       = 'Udtalelser Behandlet';
$INSTALLER_LANG["statements"]           = 'udtalelser';
$INSTALLER_LANG["sql_failed"]           = 'Udtalelser Havererte';
$INSTALLER_LANG["legend_sql_errors"]    = 'SQL feil';

$INSTALLER_LANG["db_default_settings"]  = 'Standardinnstillinger';
$INSTALLER_LANG["db_defaults"]          = 'Database Normalt setter';
$INSTALLER_LANG["legend_enter_defaults"]= 'Fyll Standardinnstillinger';
$INSTALLER_LANG["defaults_site_name"]   = 'Siden Navn';
$INSTALLER_LANG["defaults_url"]         = 'URL til hvor eoCMS er installert';
$INSTALLER_LANG["defaults_check"]       = 'Ingen inn / og inkluderer http://';
$INSTALLER_LANG["defaults_admin_user"]  = 'Admin Brukernavn';
$INSTALLER_LANG["defaults_admin_pass"]  = 'Admin Passord';
$INSTALLER_LANG["defaults_verify_pass"] = 'Passord Verifikasjonen';
$INSTALLER_LANG["defaults_admin_email"] = 'Admin E-post';

$INSTALLER_LANG["def_err_site_name"]    = 'Du gjorde ikke inn et navn for din nettside';
$INSTALLER_LANG["def_err_url"]          = 'Du gjorde ikke inn en URL til hvor eoCMS blir installert';
$INSTALLER_LANG["def_err_user"]         = 'Du gjorde ikke inn en brukernavn';
$INSTALLER_LANG["def_err_pass"]         = 'Passord må være 6 figurer eller lenger!';
$INSTALLER_LANG["def_err_admin"]        = 'Ditt brukernavn er altfor lenge, det må være under 16 figurer';
$INSTALLER_LANG["def_err_match"]        = 'De passord gikk til ikke Match';
$INSTALLER_LANG["def_err_email"]        = 'E-postadresse inn er ikke gyldig';

$INSTALLER_LANG["error_db_read_def"]    = 'Ikke i stand til å lese Database Normalt setter';
$INSTALLER_LANG["legend_inserting"]     = 'Inn';
$INSTALLER_LANG["error_db_def_size"]    = 'Ikke i stand til å lese Database Ingen Mål';
$INSTALLER_LANG["error_db_insert_def"]  = 'Ikke i stand til å tilføye Database Normalt setter';

$INSTALLER_LANG["install_finished"]     = 'Innretningen har avsluttet. Takk for at du ved å bruke eoCMS. Vennligst slett <i>install.php</i> fra eders server for sikkerhet grunner. Du kan nå tilgang din nettside, men du kan automatisk slett <i>install.php</i> og gå til din nettside ved å klikke på en av de følgende.';
$INSTALLER_LANG["continue"]             = 'Fortsætte til nettside';
$INSTALLER_LANG["continue_delete"]      = 'Slett og fortsette å nettside';

$INSTALLER_LANG['error_db_redo']        = 'Skyldes feil mens du prøver å sette din informasjon til database, det nå må bli erstattet med en ren installasjon. Dette krever går tilbake et skritt eller to.';
$INSTALLER_LANG["redo_db"]              = 'Ersätta Database';

?>