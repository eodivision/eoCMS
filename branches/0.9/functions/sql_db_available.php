<?php
/*

  Gathers database information, tests for PHP module installation
  Returns list or <OPTION> for an HTML <SELECT> object

*/
function sql_db_available($language,$only_available=false){
	$xdbDir = "./functions/database/";
	$xdbFolders = array();
	$only_available = false;
	$out = '';
	if(is_dir($xdbDir)){
		if($xdbDH = opendir($xdbDir)){
			while (($xdbFile = readdir($xdbDH))){
				if(is_dir($xdbDir.$xdbFile) && $xdbFile!="." && $xdbFile!="..") {
# fix for svn folders, and "work in progress" database support for live or FINAL versions
					if(strstr($xdbFile,'.')==$xdbFile || strstr($xdbFile,'_')==$xdbFile) continue;
					$xdbFolders[] = $xdbFile;
				}
			}
			closedir($xdbDH);
		}
	}

	foreach($xdbFolders as $db){
		if($dbFH = @fopen($xdbDir.$db."/install.description.php","r")){
			$dbOpt = array();
			while(!feof($dbFH)){
				$line = trim(str_replace('\r\n','',fgets($dbFH)));
				if(strlen($line)==0 || $line=='' || $line=='<?php' || $line=='?>' || $line=='/*' || $line=='*/' || strstr($line,'/*')==$line || strstr($line,'#')==$line || strstr($line,'//')==$line || strpos($line,'*/')>0) continue;
				$dbOpt[] = $line;
			}
			fclose($dbFH);
			$test = (function_exists($dbOpt[1]) || class_exists($dbOpt[1]));
			if($test){
				$out .= "<option value=\"$db\">".$dbOpt[0]."</option>\r\n";
			}elseif(!$only_available){
				$out .= "<option value=\"$db\">".$dbOpt[0]." (".$language['not_installed'].")</option>\r\n";
			}
		}else{
			if(!$only_available){
				$out .= "<option value=>$db (".$language['not_supported'].")</option>\r\n";
			}
		}
	}
	if(!$only_available && $out==''){
		$out .= "<option value=>".$language['function_cant_read']."</option>\r\n";
	}
	return $out;
}

?>