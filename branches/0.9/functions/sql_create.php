<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html

   Added by Paul Wratt - 02/03/2009 - (dynamic) multiple database support
*/

function sql_create($table_name, $col_names, $col_defs, $primary_keys='', $unique_keys='', $col_null='', $col_defaults='', $optimized_4_search='', $hivolume=false){
/* Generic CREATE TABLE function, catering for most possibilities

   table_name = name of table to be created
     EG        eoCMS_users
   col_names = corresponding column names
     EG        id,user_name,user_pass,user_email,user_address,user_state,user_country
   col_defs = corresponding column definitions
     NUMBERS   int, smallint, tinyint, mediumint, bigint, decimal(?,?), numeric(?,?), float, boolean
     STRINGS   char(?), varchar(?), nvarchar(?), text, mediumtext, blob, clob
     EG        int,varchar(50),varchar(50),varchar(225),varchar(225),char(2),varchar(50)
   primary_keys = a list of columns to be PRIMARY KEY's in the table
     EG        id
   unique_keys = a list of columns to be UNIQUE index's in the table
     EG        
   col_null = a list of columns to be NULL (instead of NOT NULL) **
     EG        user_state
   col_defaults = corresponding list of default values
     EG        ,,,none,not entered,,unknown
   optimized_4_search = a list of columns to be optimized for searches (not including the PRIMARY KEY) **
     EG        user_name,user_pass,user_email,user_country
   hivolume = is the table to be created, going to have a high volume of transactions per second (or per minute) **
     EG        true

   the above EG's would create a table "eoCMS_users", with 7 columns:
     "id" being of an integer type, is the AUTO_INCREMENT (PRIMARY) KEY starting at 1
     "user_state" being allowed to be INSERTed without any data (NULL instead of NOT NULL)
     "user_email" being set to "none" when empty on an INSERT
     "user_addres" being set to "not entered" when empty on an INSERT
     "user_country" being set to "unknown" when empty on an INSERT
     "user_name", "user_pass", "user_email", "user_country" being created to optimize search timings
     and finally, allow the table to be optimized for high volume access **

   NOTES: you can NOT have a PRIMARY KEY and UNIQUE for the same column, PRIMARY KEY is UNIQUE by default, and will override the same UNIQUE
   **     (if possible by the supporting database, otherwise ignored)
*/
  global $con;
  if(defined('DB_DEBUG')) { if(DB_DEBUG) echo "sql_create(\r\n\ttable name\t\t\t='$table_name',\r\n\tcolumn names\t\t\t='$col_names',\r\n\tcolumn definitions\t\t='$col_defs',\r\n\tPRIMARY KEYS\t\t\t='$primary_keys',\r\n\tUNIQUE/KEYS\t\t\t='$unique_keys',\r\n\twith NULL\t\t\t='$col_null',\r\n\tdefault values\t\t\t='$col_defaults',\r\n\toptimized columns\t\t='$optimized_4_search',\r\n\thigh volume (if available)\t=".($hivolume?'true':'false')."\r\n)\r\n\r\n"; }
  if(defined('DB_TYPE')) {
	include('functions/database/'.DB_TYPE.'/sql_create.php');
  }
	return $sql;
}
?>