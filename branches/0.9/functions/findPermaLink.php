<?php

function findPermaLink($permalink){
	if(strpos($permalink,'-')>0) {
		$words = explode('-',$permalink);
	} else {
		$words[] = $permalink;
	}
	$sql = call('sql_query',"SELECT topic_id, thread_title FROM forum_topics WHERE thread_title LIKE '". $words[0] ."%'");
	if(!$sql) return false;
	$row_count = call('sql_num_rows',$sql);
	$found = false;
	for($i=0;$i<$row_count;$i++){
		$fetch = call('sql_fetch_assoc',$sql);
		$found = '';
		foreach($words as $word){
			if(strpos($fetch['thread_title'],$word)===false) { $found = false; break; }
		}
		if($found!==false) { $found = true; break; } 
	}
	if($found===true){
		return array('id' => $fetch['topic_id']);
	}
	return false;
}

?> 
