<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
define('IN_ECMS', true);
define('INSTALLER', true);
define('IN_PATH', realpath('.') . '/');
require('framework.php');
if(!isset($_REQUEST['ajax']) && !defined('CACHE')) {
	define('CACHE', 'cache');
	//set admin to true
	$user['admin_panel'] = true;
	//clear the cache folder in case someone is reinstalling, if the cache exists, old data will be used which we dont want
	call('clearcache');
}
if(!isset($settings['site_url'])) {
	$settings['site_url'] = str_replace('/ajaxinstall.php', '', $_SERVER['REQUEST_URI']);
	$settings['site_url'] = 'http://'.$_SERVER['HTTP_HOST'].$settings['site_url'];	
}
if(isset($_GET['delete'])) {
	$siteurl = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
	$siteurl = str_replace('ajaxinstall.php', '', $siteurl); 
	header("Location: $siteurl");
	@unlink("install.php");
	@unlink("installer.php");
	@unlink("autoinstall.php");
	@unlink("autoinstaller.php");
	@unlink("installprogress.php");
	@unlink("ajaxinstall.php");
	@unlink("dbinstall.php");
	exit;
}
include('dbinstall.php');
$table_count = count($tables) - 1;
$insert_count = count($inserts) - 1;
if(defined('COOKIE_LANG')) 
	include_once(IN_PATH.'language/'.COOKIE_LANG.'/installer.php');
if(isset($_REQUEST['lang'])) 
	include_once(IN_PATH.'language/'.$_REQUEST['lang'].'/installer.php');
if(!isset($INSTALLER_LANG) || !is_array($INSTALLER_LANG)) 
	include_once(IN_PATH.'language/en/installer.php');
if(!isset($_REQUEST['ajax']) || $_REQUEST['ajax'] == null) {
	$head = '
	<style type="text/css">
	a{cursor: pointer;}
	</style>
	<script type="text/javascript">
	/*
 * jQuery AjaxQ - AJAX request queueing for jQuery
 *
 * Version: 0.0.1
 * Date: July 22, 2008
 *
 * Copyright (c) 2008 Oleg Podolsky (oleg.podolsky@gmail.com)
 * Licensed under the MIT (MIT-LICENSE.txt) license.
 *
 * http://plugins.jquery.com/project/ajaxq
 * http://code.google.com/p/jquery-ajaxq/
 */

jQuery.ajaxq = function (queue, options)
{
	// Initialize storage for request queues if it\'s not initialized yet
	if (typeof document.ajaxq == "undefined") document.ajaxq = {q:{}, r:null};

	// Initialize current queue if it\'s not initialized yet
	if (typeof document.ajaxq.q[queue] == "undefined") document.ajaxq.q[queue] = [];
	
	if (typeof options != "undefined") // Request settings are given, enqueue the new request
	{
		// Copy the original options, because options.complete is going to be overridden

		var optionsCopy = {};
		for (var o in options) optionsCopy[o] = options[o];
		options = optionsCopy;
		
		// Override the original callback

		var originalCompleteCallback = options.complete;

		options.complete = function (request, status)
		{
			if(status == "success") {
				// Dequeue the current request
				document.ajaxq.q[queue].shift ();
				document.ajaxq.r = null;
				
				// Run the original callback
				if (originalCompleteCallback) originalCompleteCallback (request, status);
	
				// Run the next request from the queue
				if (document.ajaxq.q[queue].length > 0) document.ajaxq.r = jQuery.ajax (document.ajaxq.q[queue][0]);
			}
		};

		// Enqueue the request
		document.ajaxq.q[queue].push (options);

		// Also, if no request is currently running, start it
		if (document.ajaxq.q[queue].length == 1) document.ajaxq.r = jQuery.ajax (options);
	}
	else // No request settings are given, stop current request and clear the queue
	{
		if (document.ajaxq.r)
		{
			document.ajaxq.r.abort ();
			document.ajaxq.r = null;
		}

		document.ajaxq.q[queue] = [];
	}
}
	function step(step) {
		if(step == 1) {
			$("#ajax").slideToggle();
			$("#install").html("'.$INSTALLER_LANG["js_perms"]	.'");
			$.ajax({url: "ajaxinstall.php", type: "GET", data: "ajax=permchecks", success: function(data){$("#install").html(data); $("#ajax").slideToggle();}});
		}
		if(step == 2) {
			$("#ajax").slideToggle();
			$("#install").html("'.$INSTALLER_LANG["js_dbtypes"].'");
			$.ajax({url: "ajaxinstall.php", type: "GET", data: "ajax=dbtypes", success: function(data){$("#install").html(data); $("#ajax").slideToggle();	}});
		}
		if(step == 3) {
			$("#ajax").slideToggle();	
			$.ajax({url: "ajaxinstall.php", type: "GET", data: "ajax=dbinfo&db="+$("#dbtype").val(), success: function(data){$("#install").html(data); $("#ajax").slideToggle();	}});
		}
		if(step == 4) {
			$("#ajax").slideToggle();
			var dbtype = $("#dbtype").val();
			var db = $("#db").val();
			if(dbtype == "mysql")
				var user = $("#user").val();
				var pass = $("#pass").val();
				var host = $("#host").val();
				$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=connection&dbtype="+dbtype+"&host="+host+"&user="+user+"&pass="+pass+"&db="+db, success: function(data){if(data == "'.$INSTALLER_LANG["error_connect"].'"){var d = $("#install").html(); $("#install").html("<span style=\"color:red;\"><b>'.$INSTALLER_LANG["error_connect"].'</b></span>"+d); $("#ajax").slideToggle();} if(data == 1){$("#install").html("'.$INSTALLER_LANG["js_config"].'");$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=config&dbtype="+dbtype+"&host="+host+"&user="+user+"&pass="+pass+"&db="+db, success: function(data){$("#install").html(data); $("#ajax").slideToggle();}});}
																																																																																																																																																	 }});
			if($("#dbtype").val() == "sqlite")
				$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=connection&dbtype="+dbtype+"&location="+db, 
success: function(data){if(data == "'.$INSTALLER_LANG["error_connect"].'"){var d = $("#install").html(); $("#install").html("<span style=\"color:red;\"><b>'.$INSTALLER_LANG["error_connect"].'</b></span>"+d);} if(data == 1){$("#install").html("'.$INSTALLER_LANG["js_config"].'");$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=config&dbtype="+dbtype+"&user="+user+"&pass="+pass+"&db="+db, success: function(data){$("#install").html(data); $("#ajax").slideToggle();}});}
	}});
		}
		if(step == 5) {
			$("#ajax").slideToggle();
			$("#install").html(\'Creating Tables <br /><div id="progress"><span id="count"></span> out of '.($table_count+1).' created</div>\');';
			if(file_exists('installprogress.php'))
				$file = eval(file_get_contents('installprogress.php', 'FILE_TEXT', NULL, 16));
			if(isset($tables_created) && $tables_created < $table_count)
				$i = $tables_created;
			else
				$i = 0;
			$head.='
			for(i = '.$i.'; i <= '.$table_count.'; i++) {
				$.ajaxq("tables", {url: "ajaxinstall.php", type: "GET", data: "ajax=tables&i="+i, success: function(data){ $("#count").html(data); if(data == '.$table_count.') {
					$("#ajax").slideToggle();
					$("#install").html("Tables Created<br /><a onclick=\"step(\'6\');\">'.$INSTALLER_LANG["next"].'</a>");
				}}, error: function (XMLHttpRequest, textStatus, errorThrown) { i-2; }});
			}
		}
		if(step == 6) {
			$("#ajax").slideToggle();
			$.ajax({url: "ajaxinstall.php", type: "GET", data: "ajax=settings", success: function(data){$("#install").html(data); $("#ajax").slideToggle();	}});
		}
		if(step == 7) {
			if($("#settings").valid()) {
				$("#ajax").slideToggle();
				var sitename = $("#sitename").val();
				var url = $("#url").val();
				var username = $("#username").val();
				var password = $("#password").val();
				var vpassword = $("#vpassword").val();
				var email = $("#email").val();
				$.ajax({url: "ajaxinstall.php", type: "POST", data: "ajax=insertsettings&sitename="+sitename+"&url="+url+"&username="+username+"&password="+password+"&vpassword="+vpassword+"&email="+email, success: function(data){
				if(data == 1)
					$("#install").html("Settings Inserted<br /><a onclick=\"step(\'8\');\">'.$INSTALLER_LANG["next"].'</a>");
				else
					$("#install").html(data); $("#ajax").slideToggle();}});
			}
		}
		if(step == 8) {
			$("#ajax").slideToggle();
			$("#install").html(\'Inserting... <br /><div id="progress"><span id="count"></span> out of '.($insert_count+1).' finished</div>\');';
			if(file_exists('installprogress.php'))
				$file = eval(file_get_contents('installprogress.php', 'FILE_TEXT', NULL, 16));
			if(isset($inserts_done) && $inserts_done < $insert_count)
				$s = $inserts_done;
			else
				$s = 0;
			$temp = array_keys($inserts);
			for($i = $s; $i <= $insert_count; $i++) {
				$head .='$.ajaxq("inserts", {url: "ajaxinstall.php", type: "GET", data: "ajax=inserts&i='.$temp[$i].'", success: function(data){ $("#count").html(data);';
				if((count($temp) - 1) == $i) {																																			  
					$head .= ' if(data == '.$insert_count.') {
						$("#ajax").slideToggle();
						$("#install").html("Inserts Completed<br /><a onclick=\"step(\'9\');\">'.$INSTALLER_LANG["next"].'</a>");
					}';
				}
				$head .= '}});
				';	
			}
			$head.='
		}
		if(step == 9) {
			$("#install").html("'.$INSTALLER_LANG["install_finished"].'<br /><a href=\"ajaxinstall.php?delete\">'.$INSTALLER_LANG["continue_delete"].'</a><br /><br /><a href=\"index.php\">'.$INSTALLER_LANG["continue"].'</a>");	
		}
	}
</script>
<script type="text/javascript" src="' . $settings['site_url'] . '/js/jquery.validate.js"></script><script type="text/javascript">
$(document).ready(function() {
	$("#settings").validate({onsubmit: false});
});</script>';
	echo theme('head', '| '.$INSTALLER_LANG['install_eocms_title'], $head).'<body><div style="width: 500px; margin-left: auto; margin-right: auto;">'.theme('title', $INSTALLER_LANG['install_eocms']).theme('start_content').'<div id="ajax" style="display: none;"><img src="'.$settings['site_url'].'/themes/'.$settings['site_theme'].'/images/ajaxloader.gif" alt="" /></div>';
	if(!file_exists('installprogress.php'))
		echo '<div id="install"><a onclick="step(\'1\')">'.$INSTALLER_LANG["install_eocms"].'</a><div id="progressbar"></div>';
	else {
		$file = file_get_contents('installprogress.php', 'FILE_TEXT', NULL, 16);
		$file = eval($file);
		echo '<div id="install">'.$INSTALLER_LANG["prev_install"].'<br /><a onclick="step(\''.$step.'\')">'.$INSTALLER_LANG["continue_install"].'</a> <a onclick="step(\'1\')">'.$INSTALLER_LANG["start_again"].'</a></div>';
	}
	echo '</div>'.theme('end_content').'</div>';
} else {
	//mail/fsockopen test
	function get_url_title($url, $timeout = 2)
	{
		$url = parse_url($url);
		if(!in_array($url['scheme'],array('','http')))
			return;
		$fp = @fsockopen($url['host'], ($url['port'] > 0 ? $url['port'] : 80), $errno, $errstr, $timeout);
		if (!$fp)
			return false;
		else {
			fputs ($fp, "GET /".(isset($url['path']) ? $url['path'] : '').(isset($url['query']) ? '?'.$url['query'] : '')." HTTP/1.0\r\nHost: ".$url['host']."\r\n\r\n");
			$d = '';
		   
			while (!feof($fp))
			{
				$d .= fgets ($fp,2048);
	
				if(preg_match('~(</head>|<body>|(<title>\s*(.*?)\s*</title>))~i', $d, $m))
					break;
			}
			fclose ($fp);
	
			return $m[3];
		}
	}
	switch($_REQUEST['ajax']) {
		//step 1
		case 'permchecks';
		$writable = true;
		echo '<fieldset><legend><b>'.$INSTALLER_LANG['legend_permissions'].'</b></legend><table width="100%"><tr><td colspan="2" align="left">* '.$INSTALLER_LANG["not_required"].'</td></tr>
		<tr><td align="left">'.$INSTALLER_LANG['directory_writable'].':</td>';
		if(is_writable(IN_PATH)) {
			echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
			$writable = false;
		}
	
		echo '<tr><td align="left">'.$INSTALLER_LANG['config_writable'].':</td>';
		if(is_writable(IN_PATH.'config.php')) {
			if($fl = fopen(IN_PATH.'config.php', 'a')) {
				echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
				fclose($fl);
			}else{
				echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
				$writable = false;
			}
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
			$writable = false;
		}
	
		echo '<tr><td align="left">'.$INSTALLER_LANG['cache_writable'].':</td>';
		if(is_writable(IN_PATH.'cache')) {
			if($fl = fopen(IN_PATH.'cache/tables.php', 'a')) {
				echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
				fclose($fl);
			}else{
				echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
				$writable = false;
			}
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
			$writable = false;
		}
		echo '<tr><td align="left">'.$INSTALLER_LANG['tables_writable'].':</td>';
		if(is_writable(IN_PATH.'config.php')) {
			if($fl = fopen(IN_PATH.'config.php', 'a')) {
				echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
				fclose($fl);
			}else{
				echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
				$writable = false;
			}
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
			$writable = false;
		}
		echo '<tr><td align="left">'.$INSTALLER_LANG['php_version'].' (> 5.0.0):</td>';
		if(version_compare(PHP_VERSION, '5.0.0', '>')) {
			echo '<td align="right"><span style="color:green;"><b>v'.PHP_VERSION.' '.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
		}else{
			echo '<td align="right"><span style="color:red;"><b>v'.PHP_VERSION.' '.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
		}
	
		echo '<tr><td align="left">'.$INSTALLER_LANG['use_captcha'].' (PHP GD)*:</td>';
		if(function_exists('gd_info')) {
			$gd_extension = gd_info();
			echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['needs_gd'].' '.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
		}
	$message = "Line 1\nLine 2\nLine 3";
	if(@mail('mailcheck@somecrappyfakedomainname.whatver.comm', 'test subject', $message, "FROM: test@somecrappyfakedomainname.whatever.com") == false)
		$sendmail = false;
	else
		$sendmail = true;
	if(get_url_title("http://news.bbc.co.uk") == false)
		$fsockopen = false;
	else
		$fsockopen = true;
		
		echo '<tr><td align="left">'.$INSTALLER_LANG["sendmail"].'*:</td>';
		if($sendmail) {
			echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
		}
		echo '<tr><td align="left">'.$INSTALLER_LANG["fsockopen"].'*:</td>';
		if($fsockopen) {
			echo '<td align="right"><span style="color:green;"><b>'.$INSTALLER_LANG['passed'].'</b></span></td></tr>'."\r\n";
		}else{
			echo '<td align="right"><span style="color:red;"><b>'.$INSTALLER_LANG['failed'].'</b></span></td></tr>'."\r\n";
		}
		if(!$writable)
			echo '<tr><td colspan="2"><a onclick="step(\'1\');">'.$INSTALLER_LANG["try_again"].'</a></td></tr>'."\r\n";
		else
			echo '<tr><td colspan="2"><a onclick="step(\'2\');">'.$INSTALLER_LANG["next"].'</a></td></tr>'."\r\n";
		echo '</table></fieldset>'."\r\n";
		break;
		//step 2
		case 'dbtypes';
			//create file stating permissions already checked
			$file = file_put_contents('installprogress.php', "<?php die(); ?>\n".'$step = 2;');
			$dbOptions = call('sql_db_available',$INSTALLER_LANG);
			echo '<fieldset><legend><b>'.$INSTALLER_LANG['legend_databases'].'</b></legend><table width="100%">
<tr><td colspan="2">&nbsp;</b></td></tr>
<tr><td colspan="2">'.$INSTALLER_LANG['choose_database'].'</td></tr>
<tr><td colspan="2">&nbsp;</b></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['databases'].' :</td><td align="right"><select id="dbtype">'.$dbOptions.'</select></td></tr>
<tr><td colspan="2"><a onclick="step(\'3\');">'.$INSTALLER_LANG['next'].'</a></b></td></tr>
</table></fieldset>';
		break;
		//step 3
		case 'dbinfo';
			if(file_exists('installprogress.php')) {
				$file = file_get_contents('installprogress.php', 'FILE_TEXT', NULL, 16);
				$file = eval($file);
				if(isset($db) && !empty($db))
					$_REQUEST['db'] = $db;
			} else {
				//add which database was chosen
				$file = file_put_contents('installprogress.php', "<?php die(); ?>\n".'$step = 3; $db = "'.$_REQUEST['db'].'";');
			}
			switch($_REQUEST['db']) {
				case 'mysql';
					echo '<fieldset><legend><b>MySQL '.$INSTALLER_LANG['legend_settings'].'</b></legend><table width="100%">
<tr><td colspan="2">&nbsp;</b></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['HOST'].' :</td><td align="right"><input type="text" value="localhost" id="host" /></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['USER'].' :</td><td align="right"><input type="text" id="user" /></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['PASS'].' :</td><td align="right"><input type="password" id="pass" /></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['NAME'].' :</td><td align="right"><input type="text" value="eocms" id="db" /><input type="hidden" value="'.$_REQUEST['db'].'" id="dbtype" /></td></tr>
<tr><td colspan="2"><a onclick="step(\'2\');">'.$INSTALLER_LANG['back'].'</a> <a onclick="step(\'4\');">'.$INSTALLER_LANG['next'].'</a></b></td></tr>
</table></fieldset>';
				break;
				case 'sqlite';
				echo '<fieldset><legend><b>SQLite '.$INSTALLER_LANG['legend_settings'].'</b></legend><table width="100%">
<tr><td colspan="2">&nbsp;</b></td></tr>
<tr><td align="left">'.$INSTALLER_LANG['LOCATION'].' :</td><td align="right"><input type=text value="'.(isset($location) ? $location : 'eocms_'.substr(str_shuffle("qwertyuiopasdfghjklmnbvcxz0987612345"),0,4).'.db').'" id="db" /><input type="hidden" value="'.$_REQUEST['db'].'" id="dbtype" /></td></tr>
<tr><td colspan="2"><a onclick="step(\'2\');">'.$INSTALLER_LANG['back'].'</a> <a onclick="step(\'4\');">'.$INSTALLER_LANG['next'].'</a></b></td></tr>
</table></fieldset>';
				break;
			}
		break;
		//step 4
		case 'connection';
			if(file_exists('installprogress.php')) {
				$file = file_get_contents('installprogress.php', 'FILE_TEXT', NULL, 16);
				$file = eval($file);
				if(isset($db) && !empty($db))
					$_REQUEST['dbtype'] = $db;
				if(isset($host) && !empty($host))
					$_REQUEST['host'] = $host;
				if(isset($dbuser) && !empty($dbuser))
					$_REQUEST['user'] = $dbuser;
				if(isset($pass) && !empty($pass))
					$_REQUEST['pass'] = $pass;
				if(isset($name) && !empty($name))
					$_REQUEST['db'] = $dbname;
				if(isset($location) && !empty($location))
					$_REQUEST['location'] = $location;
			}
			switch($_REQUEST['dbtype']) {
				case 'mysql';
					//add datbase details
					$file = file_put_contents('installprogress.php', "<?php die(); ?>\n".'$step = 3; $db = "'.$_REQUEST['dbtype'].'"; $host = "'.$_REQUEST['host'].'"; $dbuser = "'.$_REQUEST['user'].'"; $pass = "'.$_REQUEST['pass'].'"; $dbname = "'.$_REQUEST['db'].'";');
					$con = @mysql_connect($_REQUEST['host'], $_REQUEST['user'], $_REQUEST['pass']);
					if(!$con)
						echo $INSTALLER_LANG["error_connect"];
					if($con) {
						$select = mysql_select_db($_REQUEST['db']);
						if(!$select)
							echo $INSTALLER_LANG["error_connect"];
						else
							echo '1';
					}
				break;
				case 'sqlite';
					//add datbase details
					$file = file_put_contents('installprogress.php', "<?php die(); ?>\n".'$step = 3; $db = "'.$_REQUEST['dbtype'].'"; $location = "'.$_REQUEST['location'].'";');
					$con = @sqlite_open($_REQUEST['location'], 0666, $sqliteerror);
					if(!$con)
						echo $INSTALLER_LANG["error_connect"];
					else
						echo '1';
				break;
			}
		break;
		//step 5
		case 'config';
			switch($_REQUEST['dbtype']) {
				case 'mysql';
					$databaseinfo =  "define('DB_HOST', '".$_REQUEST['host']."');\n";
					$databaseinfo .= "define('DB_USER', '".$_REQUEST['user']."');\n";
					$databaseinfo .= "define('DB_PASS', '".$_REQUEST['pass']."');\n";
					$databaseinfo .= "define('DB_NAME', '".$_REQUEST['db']."');\n";
					$databaseinfo .= "$"."con = "."mysql_connect(DB_HOST, DB_USER, DB_PASS);\n";
					$databaseinfo .= "if(!";
					$databaseinfo .= "$"."con)\n";
					$databaseinfo .= "\t die('eoCMS was unable to connect to the database');\n";
					$databaseinfo .= "$"."select = "."mysql_select_db(DB_NAME);\n";
					$databaseinfo .= "if(!";
					$databaseinfo .= "$"."select)\n";
					$databaseinfo .= "die('eoCMS was unable to select to the database'.DB_NAME);";
				break;
				case 'sqlite';
					$databaseinfo =  "define('DB_LOCATION', '".$_REQUEST['db']."');\n";
					$databaseinfo .= "$"."con = "."sqlite_open(DB_LOCATION, 0666, "."$"."sqliteerror);\n";
					$databaseinfo .= "if(!";
					$databaseinfo .= "$"."con)\n";
					$databaseinfo .= "\t die('eoCMS was unable to connect to the database');\n";
				break;
			}
			$database_config = "<?php\r\n";
			$database_config .= "define('COOKIE_LANG',   'en');\r\n";
			$database_config .= "define('COOKIE_NAME',   'eocms');\r\n";
			$database_config .= "define('COOKIE_CHILD',  '');\r\n";
			$database_config .= "define('CACHE','cache');\r\n";
			$database_config .= "define('FINAL',true);\r\n";
			$database_config .= "$"."_QUERIES=0;\r\n";
			$database_config .= "define('DB_TYPE', '".$_POST['dbtype']."');\r\n";
			$database_config .= $databaseinfo;
			$database_config .= "\r\n?>";
			$fl = fopen('config.php', 'w') or die("Unable to open file");
			if(fwrite($fl, $database_config)) {
				fclose($fl);
				echo $INSTALLER_LANG["db_config_write"].'<br /><a onclick="step(\'5\');">'.$INSTALLER_LANG["next"].'</a>';
			} else
				echo $INSTALLER_LANG["error_cant_write_conf"].'<br /><a onclick="step(\'4\');">'.$INSTALLER_LANG["try_again"].'</a>';
		break;
		case 'tables';
			include('dbinstall.php');
			require('config.php');
			$query = call('create_sql', array($tables[$_REQUEST['i']] => $sql[$tables[$_REQUEST['i']]]));
			$q = @call('sql_query', $query[0]);
			file_put_contents('installprogress.php', ' $tables_created = "'.$_REQUEST['i'].'";', FILE_APPEND);
			echo $_REQUEST['i']+1;
			if($_REQUEST['i'] == (count($tables) - 1))
				$file = file_put_contents('installprogress.php', ' $step = 6;', FILE_APPEND);
		break;
		//step 6
		case 'settings';
			echo '<fieldset><legend><b>'.$INSTALLER_LANG['legend_enter_defaults'].'</b></legend><form action="" id="settings"><table align="center" width="100%">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_site_name'].' :</td><td align="right"><input type="text" id="sitename" class="required" name="sitename" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_url'].' :</td><td align="right"><input type="text" id="url" name="url" value="'.str_replace('?ajax=settings', '', $settings['site_url']).'" class="required" /></td></tr>
				<tr><td align="left">&nbsp;</td><td align="right">('.$INSTALLER_LANG['defaults_check'].')</td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_admin_user'].' :</td><td align="right"><input type="text" id="username" name="username" class="required" maxlength="16" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_admin_pass'].' :</td><td align="right"><input type="password" name="password" id="password" class="required" minlength="6" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_verify_pass'].' :</td><td align="right"><input type="password" name="vpassword" id="vpassword" class="required" minlength="6" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_admin_email'].' :</td><td align="right"><input type="text" name="email" id="email" class="required email" /></td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				</table></form></fieldset>
				<a onclick="step(\'7\');">'.$INSTALLER_LANG["next"].'</a>';
		break;
		//step 7
		case 'insertsettings';
			require('config.php');
			$error = array();
			if (empty($_POST['sitename']))
				$error[] = $INSTALLER_LANG["def_err_site_name"];
			if (empty($_POST['url']))
				$error[] = $INSTALLER_LANG["def_err_url"];
			if (empty($_POST['username']))
				$error[] = $INSTALLER_LANG["def_err_user"] ;
			if (strlen($_POST['password']) < 6)
				$error[] = $INSTALLER_LANG["def_err_pass"];
			if (strlen($_POST['username']) > 16)
				$error[] = $INSTALLER_LANG["def_err_admin"];
			if ($_POST['password'] != $_POST['vpassword'])
				$error[] = $INSTALLER_LANG["def_err_match"];
			if (!preg_match("/^([a-z0-9._-](\+[a-z0-9])*)+@[a-z0-9.-]+\.[a-z]{2,6}$/i", $_POST['email']))
				$error[] = $INSTALLER_LANG["def_err_email"];
			if(isset($error) && !empty($error)) {
				foreach($error as $errors) {
					echo '<span class="error">'.$errors.'</span><br />';	
				}
				echo '<fieldset><legend><b>'.$INSTALLER_LANG['legend_enter_defaults'].'</b></legend><form action="" id="settings"><table align="center" width="100%">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_site_name'].' :</td><td align="right"><input type="text" id="sitename" class="required" name="sitename" value="'.(isset($_POST['sitename']) ? $_POST['sitename'] : '').'" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_url'].' :</td><td align="right"><input type="text" id="url" name="url"  value="'.(isset($_POST['url']) ? $_POST['url'] : str_replace('?ajax=settings', '', $settings['site_url'])).'" class="required" /></td></tr>
				<tr><td align="left">&nbsp;</td><td align="right">('.$INSTALLER_LANG['defaults_check'].')</td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_admin_user'].' :</td><td align="right"><input type="text" id="username" name="username" class="required" maxlength="16"  value="'.(isset($_POST['username']) ? $_POST['username'] : '').'" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_admin_pass'].' :</td><td align="right"><input type="password" name="password" id="password" class="required" minlength="6" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_verify_pass'].' :</td><td align="right"><input type="password" name="vpassword" id="vpassword" class="required" minlength="6" /></td></tr>
				<tr><td align="left">'.$INSTALLER_LANG['defaults_admin_email'].' :</td><td align="right"><input type="text" name="email" id="email" class="required email"  value="'.(isset($_POST['email']) ? $_POST['email'] : '').'" /></td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				</table></form></fieldset>
				<a onclick="step(\'7\');">'.$INSTALLER_LANG["next"].'</a>';
			}
			else {
				call('sql_query', "INSERT INTO users (user, pass, email, ip, regdate, lastlogin, membergroup) VALUES('".$_POST['username']."', '".md5($_POST['password'])."', '".$_POST['email']."', '".call('visitor_ip')."', '".time()."', 'Never', '4');");
				//lets log them in
				call('login', $_POST['username'], md5($_POST['password']), 'on');
				if(!isset($dbDEFAULTS_LANG)) {
					if(defined('COOKIE_LANG')) 
						include_once('language/'.COOKIE_LANG.'/dbdefaults.php');
					if(isset($_REQUEST['lang'])) 
						include_once('language/'.$_REQUEST['lang'].'/dbdefaults.php');
					if(!is_array($dbDEFAULTS_LANG)) 
						include_once('language/en/dbdefaults.php');	
				}
				$array = array("INSERT INTO settings (id, variable, value, help_text) VALUES (5, 'site_name', '".$_REQUEST['sitename']."', '{h_sitename}');", 
"INSERT INTO settings (id, variable, value, help_text) VALUES (6, 'site_url', '".$_REQUEST['url']."', '{h_url}');");
				$array = replaceLang($dbDEFAULTS_LANG, $array);
				foreach($array as $query) {
					call('sql_query', $query);
				}
				$file = file_put_contents('installprogress.php', ' $step = 8;', FILE_APPEND);
				echo 1;
			}
		break;
		case 'inserts';
			require('config.php');
			unset($settings);
			$settings = call('getsettings');
			include('dbinstall.php');
			$inserts = replaceLang($dbDEFAULTS_LANG, $inserts);
			foreach($inserts as $table => $array) {
				if($table == $_REQUEST['i'])
					foreach($array as $query) {
						$q = call('sql_query', $query);
				}
			}
			$temp = array_keys($inserts);
			file_put_contents('installprogress.php', ' $inserts_done = "'.array_search($_REQUEST['i'], $temp).'";', FILE_APPEND);
			echo array_search($_REQUEST['i'], $temp);
			if($_REQUEST['i'] == 'emoticons') {
				$file = file_put_contents('installprogress.php', ' $step = 9;', FILE_APPEND);
				//set admin to true
				$user['admin_panel'] = true;
				//clear the cache folder again as they might not press the delete installer link
				call('clearcache');
			}
		break;
	}
}
?>