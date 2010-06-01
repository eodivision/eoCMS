<?php
# set defaults
$register_keys = '';
$textarea_name = 'dataBox';
$smiley_image_path = './images/emoticons/';
$bbcode_image_path = './images/';

$bbcode_array = array();
$smiley_array = array();

function getStandard($path='./bbcodepress/',$type='-simple'){
	loadStandard($path,$type);
	return retStandard();
}

function loadStandard($path='./bbcodepress/',$type='-simple'){
	global $bbcode_array, $smiley_array;
	$bbcode_array = loadButtons($path . 'bbcode' . $type . '.lst');
	$smiley_array = loadButtons($path . 'smiley' . $type . '.lst');
}

function loadButtons($lst){
	$temp_list = explode("\r\n",file_get_contents($lst));
	$br = $ibr = 0;
	foreach($arr as $item){
		$temp_arr = explode(",",$item);
		$temp_id = $temp_arr[0];
		if (strstr($item,"br")==$temp_id){
			$eval .= 'br' . ($br++) . ' => "",';
		}elseif(strstr($item,"ibr")==$item){
			$eval .= 'ibr' . ($br++) . ' => "",';
		}else{
			array_shift($temp_arr);
			$eval .= $temp_arr[0] . ' => array("' . implode('","',$temp_arr) . '"),';
		}
	}
	eval('$arr = array(' . $eval . '"");');
	return is_array($arr) ? $arr : array();
}

function retStandard(){
	global $bbcode_array, $smiley_array;
	$ret .= retHtmlButtons($smiley_array,'smiley');
	$ret .= retHtmlButtons($bbcode_array,'button');
	return $ret;
}

function retHtmlButtons($arr,$type){
	global $textarea_name,$smiley_image_path;
	# type = button or smiley
	foreach($arr as $key => $button){
		if(is_array($button)){
			if($button[3] != ''){
				$register_keys .= $textarea_name . '_cp.registerKey("' . $button[3] . '","' . $button[1] . '");' . "\r\n";
				$title = $button[0] . ' - ' . $button[3];
			}else{
				$title = $button[0];
			}
			$ret .= '<img class="bbcode-' . $type . '" width="20" height="20" style="padding:0px;" src="' . image_path . $button[2] . '" title=" ' . $title . ' " alt="' . $key . '" onClick="' . $textarea_name . '_cp.insertText(\'' . $button[1] . '\');" />' . "\r\n";
		}else{
			if (strstr($key,"br")==$key)
				$ret .= '<br />' . "\r\n";
			elseif (strstr($key,"ibr")==$key)
				$ret .= '<img class="bbcode-divider" width="2" height="20" style="padding-left:10px;padding-right:10px;" />' . "\r\n";
		}
	}
	return $ret;
}

function writeRegKeysJS(){
	global $textarea_name, $register_keys ;
	if($register_keys){
		$js_insert = "<script id=\"{$textarea_name}_regKeys\" src=\"{$textarea_name}_regKeys.js\" ></script>\r\n";
		$js_include = "./bbcodepress/{$textarea_name}_regKeys.js";
		if(not file_exists($js_include)) 
			file_put_contents($js_include, "function {$textarea_name}_Keys(){\r\n" . $register_keys . "\r\n}\r\n");
	}
	$register_keys = '';
	return $js_insert;
}

function replaceRegKeysJS(){
	global $textarea_name, $register_keys ;
	if(file_put_contents($js_include, "function {$textarea_name}_Keys(){\r\n" . $register_keys . "\r\n}\r\n"))
		return true;
	else
		return false;
}

?>