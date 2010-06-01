<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons 
	Attribution-Share Alike 3.0 United States License. 
	To view a copy of this license, visit 
	http://creativecommons.org/licenses/by-sa/3.0/us/
	or send a letter to Creative Commons, 171 Second Street, 
	Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
function cache($function) {
	global $user, $functioncache;
	if(!isset($functioncache)) {
		$functioncache = file_get_contents('./'.CACHE.'/functions.php', NULL, NULL, 16);
		$functioncache = unserialize($functioncache);	
	}
	if(!array_key_exists($function, $functioncache) && !array_key_exists($user['id'], $functioncache[$function])) {
		$arg = func_get_args();
		$num = func_num_args();
		switch($num) {
		   case'1';
				$return = call($arg[0]);
		   break;
		   case'2';
				$return = call($arg[0], $arg[1]);
		   break;
		   case'3';
				$return = call($arg[0], $arg[1], $arg[2]);
		   break;
		   case'4';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3]);
		   break;
		   case'5';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4]);
		   break;
		   case'6';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5]);
		   break;
		   case'7';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
		   break;
		   case'8';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]);
		   break;
		   case'9';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8]);
		   break;
		   case'10';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9]);
		   break;
		   case'11';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10]);
		   break;
		   case'12';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11]);
		   break;
		   case'13';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12]);
		   break;
		   case'14';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13]);
		   break;
		   case'15';
				$return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14]);
		   break;
		   case'16';
				 $return = call($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8], $arg[9], $arg[10], $arg[11], $arg[12], $arg[13], $arg[14], $arg[15]);
		   break;
		}
		if(is_array($return))
			$return = serialize($return);
		$name = md5($return); //hash of the returned value which will be the cache file name
		$location = './'.CACHE.'/';
		$file = $name.'.php';
		if(@file_exists($location . $file) && @is_readable($location . $file)) {
			$data = unserialize(file_get_contents($location . $file, NULL, NULL, 16)); 
			return $data;
		} else {
			//Save the returned data
			$OUTPUT = "<?php die(); ?>\n".$return;
			file_put_contents($location . $file, $OUTPUT);
			//add it to the functions.php cache file
			$functionfile = './'.CACHE.'/functions.php';
			$contents = file_get_contents($file, 'FILE_TEXT', NULL, 16); 
			$tables = unserialize($contents);
			$tables2 = array($arg[0]=>array($user['membergroup_id'] => $name));
			if(strlen($contents) <= 15)
				$data = $tables2;
			else
				$data = array_merge($tables, $tables2);
			$OUTPUT = "<?php die(); ?>\n".serialize($data);
			file_put_contents($functionfile, $OUTPUT);
		}
	} else {
		foreach($functioncache as $functions => $membergroup) {
			if(isset($membergroup[$user['membergroup_id']])) {
				$file = './'.CACHE.'/'.$membergroup[$user['membergroup_id']].'.php';
				if(@file_exists($file) && @is_readable($file)) {
					$data = unserialize(file_get_contents($file, NULL, NULL, 16));
					return $data;
				}	
			}
		}
	}
}
?>