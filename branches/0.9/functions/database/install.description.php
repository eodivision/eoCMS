<?php
/*    Descriptions                 Examples (ONLY ONE PER LINE)
_Long_Database_Name_            (MySQL, MS-SQL Server, SQLite)
_PHP_test_function_or_class_    (mysql_connect, mssql_connect, sqlite_query)
_List_of_db_options_1_per_line  (HOST, USER, PASS, NAME, PREFIX, LOCATION)

//    db_options descriptions
HOST                            = hostname, name or url of the database server
USER                            = username, to access the database service
PASS                            = password, to access the database service
NAME                            = the database name to be accessed by the database service
PREFIX                          = on some database installations, the database name is a sub object of a higher structure, ie for MS-SQL the default is [dbo].[NAME]
LOCATION                        = used when the database service is file base (in place of HOST, and often without USER, PASS, & NAME), eg for SQLite

//    double check
  to recap:
    the folder name has no spaces, or other non A-Z or 0-9 characters, preferably all lowercase, and is considered a "short description" or "keyword"
    the first line of this file is the < ? php line (this helps secure the file from outside influence or viewing)
    the second line of this file is the / *  line (this helps secure the file from php influence or errors)
    the next line (the first line of the configuration) is the long (or full) name of the database server or service
    the next line (the second line of the configuration) is the function or class to be checked via PHP
    the next few lines (the third until the second last line of the configuration) contains all the required input options to get the database to function "1 per line"
    the second last line of this file is the * /  line
    the last line of this file is the ? > line

  notes:
    blank lines are allowed
    // comments are allowed
    # comments are allowed
    as of (11-March-09) a install.description.php will contain a list of no less than 3 valid entries, and no more than 8 valid entries
    the only valid end of line markers (allowed at the moment) are \r\n
    see folder contents for working examples
*/
?>