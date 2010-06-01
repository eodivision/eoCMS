<?php
/*

// contains the raw SQL required to create (or re-create) the eoCMS database structure. 
// grouped, the first line or code should contain the code required to drop the db table 
// if it exists, the next few line are used to create the table itself, BOTH statements 
// require a semi-colon (;) to end the statement, eg: 
DROP TABLE IF EXISTS `activation_keys`;
CREATE TABLE `activation_keys` (
  `user_id` int(225) NOT NULL auto_increment,
  `key_number` varchar(32) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

// NOTES:
//        ALWAYS ; followed by \r\n to end the statement
//        {_var_} substitution as defined in "install.description.php"
//        space separate blocks of DROP and CREATE TABLE
//        the first line of the file is always '< ? p h p' (as above)
//        the second line of the file is always '/ *' (as above)
//        the second to last line of the file is always '* /' (as below)
//        the last line of the file is always '? >' (as below)
//        there is NO \r\n (end of line charcters) following '? >' (as below)

*/
?>