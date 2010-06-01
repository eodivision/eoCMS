<?php
if(isset($settings['seo_links']) && $settings['seo_links']) {
	if(!isset($userprofilelink))
		global $userprofilelink;
	function clean_url($url) {
		$url = htmlspecialchars_decode($url, ENT_QUOTES);
		$disallowed = array('ç','æ','œ','á','é','í','ó','ú','à','è','ì','ò','ù','ä','ë','ï','ö','ü','ÿ','â','ê','î','ô','û','å','e','i','ø','u','Ú','ñ','Ñ');
		$change = array('c','ae','oe','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','y','a','e','i','o','u','a','e','i','o','u','U','n','n');
		$url = str_replace($disallowed, $change, $url); //Replace characters
		$url = preg_replace('/\s\s+/', ' ', $url); //Reokace multiple spaces
		$url = str_replace(' ', '-', $url); //Replace spaces
		$url = strtolower($url); //Make it all lowercase
		$url = preg_replace('([^a-zA-Z0-9-])', '', $url); //Remove junk, only keep letters
		$url = preg_replace('/^-+/', '', $url); //Remove - at begining and end
		$url = preg_replace('/-+$/', '', $url);
		$url = preg_replace('/--+/', '-', $url); //Remove any repeated - like ---
		return $url;
	}
	function board_url($url) {
		if(is_numeric($url[2])) {
			$sql = call('sql_query', "SELECT id, board_name FROM forum_boards", 'cache');
			$count = count($sql) - 1;
			for($i = 0; $i <= $count; $i++) {
				if($sql[$i]['id'] == $url[2])
					return 'forum/'.clean_url($sql[$i]['board_name']).'.'.$sql[$i]['id'].(isset($url[5]) && $url[5] > 1 ? '.p'.$url[5] : '').'/';
			}
		}
	}
	function topic_url($url) {
		if(is_numeric($url[2])) {
			$sql = call('sql_query', "SELECT topic_id, topic_title FROM forum_topics", 'cache');
			$count = count($sql) - 1;
			for($i = 0; $i <= $count; $i++) {
				if($sql[$i]['topic_id'] == $url[2]) {
					return 'forum/'.clean_url($sql[$i]['topic_title']).'.'.$url[2].(isset($url[5]) && $url[5] > 1 ? '.p'.$url[5] : '').'.html'.(isset($url[6]) ? $url[6] : '');
					break;
				}
			}
		}
	}
	function article_url($url) {
		if(is_numeric($url[3])) {
			$sql = call('sql_query', "SELECT subject FROM articles WHERE id = '".$url[3]."'");
			$fetch = call('sql_fetch_array', $sql);
			return 'article/'.clean_url($fetch['subject']).'.'.$url[3].'.html';
		}
	}
	function article_cat_url($url) {
		if(is_numeric($url[3])) {
			$sql = call('sql_query', "SELECT name FROM article_categories WHERE id = '".$url[3]."'");
			$fetch = call('sql_fetch_array', $sql);
			return 'articles/category/'.clean_url($fetch['name']).'.'.$url[3].'.html';
		}
	}
	function news_url($url) {
		if(is_numeric($url[2])) {
			$sql = call('sql_query', "SELECT subject FROM news WHERE id = '".$url[2]."'");
			$fetch = call('sql_fetch_array', $sql);
			return 'news/'.clean_url($fetch['subject']).'.'.$url[2].'.html';
		}
	}
	function profile_url($url) {
		global $userprofilelink;
		if(is_numeric($url[2])) {
			if(isset($userprofilelink)) {
				foreach($userprofilelink as $fetch) {
					if($fetch['id'] == $url[2])
						return 'profile/'.clean_url($fetch['user']).'.'.$url[2].'.html';
				}
			} else {
				$sql = call('sql_query', "SELECT user FROM users WHERE id = '".$url[2]."'");
				$fetch = call('sql_fetch_array', $sql);
				return 'profile/'.clean_url($fetch['user']).'.'.$url[2].'.html';
			}
		}
	}
	//Redirect old urls permanently
	if(strpos($_SERVER['REQUEST_URI'], 'act=articles&sa=article') !== false) {
		$newurl = preg_replace_callback('/act=articles&(amp;)?sa=article&(amp;)?article=([.0-9]{1,})/', 'article_url', $_SERVER['REQUEST_URI']);
	}
	elseif(strpos($_SERVER['REQUEST_URI'], 'act=articles&sa=cat') !== false) {
		$newurl = preg_replace_callback('/act=articles&(amp;)?sa=cat&(amp;)?cat=([.0-9]{1,})/', 'article_cat_url', $_SERVER['REQUEST_URI']);
	}
	elseif(strpos($_SERVER['REQUEST_URI'], 'act=articles') !== false) {
		$newurl = $settings['site_url'].'/articles/';
	}
	if(strpos($_SERVER['REQUEST_URI'], 'act=forum') !== false) {
		$newurl = $settings['site_url'].'/forum/';
	}
	if(strpos($_SERVER['REQUEST_URI'], 'act=news&readmore') !== false) {
		$newurl = preg_replace_callback('/act=news&(amp;)?readmore=([.0-9]{1,})/', 'news_url', $_SERVER['REQUEST_URI']);
	}
	elseif(strpos($_SERVER['REQUEST_URI'], 'act=news') !== false) {
		$newurl = $settings['site_url'].'/news/';
	}
	if(strpos($_SERVER['REQUEST_URI'], 'act=profile') !== false && strpos($_SERVER['REQUEST_URI'], 'showposts') === false) {
		$newurl = preg_replace_callback('/act=profile&(amp;)?id=([.0-9]{1,})/', 'profile_url', $_SERVER['REQUEST_URI']);
	}
	if(strpos($_SERVER['REQUEST_URI'], 'act=viewboard') !== false) {
		$newurl = preg_replace_callback('/act=viewboard&(amp;)?id=([.0-9]{1,})(&(amp;)?page=([0-9]*))?(\#[0-9]*)?/', 'board_url', $_SERVER['REQUEST_URI']);
	}
	if(strpos($_SERVER['REQUEST_URI'], 'act=viewtopic') !== false) {
		$newurl = preg_replace_callback('/act=viewtopic&(amp;)?id=([.0-9]{1,})(&(amp;)?page=([0-9]*))?(\#[0-9]*)?/', 'topic_url', $_SERVER['REQUEST_URI']);
	}
	if(isset($newurl)) {
		$newurl = str_replace($_SERVER['SCRIPT_NAME'].'?', '', $newurl);
		header('HTTP/1.1 301 Moved Permanently'); 
		header('Location:'. $newurl); // The new URL lecation
	}
	//Article rewrites
	$output = preg_replace_callback('/index\.php\?act=articles&(amp;)?sa=article&(amp;)?article=([.0-9]{1,})/', 'article_url', $output);
	$output = preg_replace_callback('/index\.php\?act=articles&(amp;)?sa=cat&(amp;)?cat=([.0-9]{1,})/', 'article_cat_url', $output);
	//Forum rewrites
	$output = preg_replace_callback('/index\.php\?act=viewboard&(amp;)?id=([.0-9]{1,})(&(amp;)?page=([0-9]*))?(\#[0-9]*)?/', 'board_url', $output);
	$output = preg_replace_callback('/index\.php\?act=viewtopic&(amp;)?id=([.0-9]{1,})(&(amp;)?page=([0-9]*))?(\#[0-9]*)?/', 'topic_url', $output);
	//News rewrite
	$output = preg_replace_callback('/index\.php\?act=news&(amp;)?readmore=([.0-9]{1,})/', 'news_url', $output);
	$output = preg_replace('/index\.php\?act=news&(amp;)?page=([0-9]*)/', 'news/\2/', $output);
	//Profile rewrite
	$output = preg_replace_callback('/index\.php\?act=profile&(amp;)?id=([.0-9]{1,})/', 'profile_url', $output);
	//act rewrite
	$acts = array('index.php?act=forum',
				  'index.php?act=articles',
				  'index.php?act=news');
	$actreplace = array('forum/',
						'articles/',
						'news/');
	$output = str_replace($acts, $actreplace, $output);
}
?>