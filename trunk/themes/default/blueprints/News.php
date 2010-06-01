<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if (!isset($_GET['readmore'])) {
	$theme['title'] = $NEWS_LANG["title"];
	// how many rows to show per page
	$rowsPerPage = 5;
	// by default we show first page
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
		$pageNum = $_GET['page'];
	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
	$theme['body'] = theme('title', $NEWS_LANG["theme_title"]).theme('start_content');
	$fetch = call('news');
	if($fetch != false) {
		$theme['head'] = '<link rel="alternate" type="application/rss+xml" title="Latest News" href="'.$settings['site_url'].'/index.php?act=feeds&type=news&export=rss" />';
		foreach($fetch as $r) {
			$theme['body'] .= '<div class="newstable"><h2>'.(@file_exists($r['cat_image']) ? '<img src="' . $r['cat_image'] . '" alt="' . $r['cat_name'] . '" title="'.$r['cat_name'].'" />' : '').'<a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '">'.$r['subject'].'</a></h2>
			<div class="news-info"><span class="news-date">'.call('dateformat', $r['time_created']).'</span>
			<span class="news-author">'.call('userprofilelink', $r['created_by']).'</span>
			<span class="news-views">'.$r['views'].' '.$NEWS_LANG["views"].'</span>
			<span class="news-comments"><a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '#comments">'.$r['comments'].' '.$NEWS_LANG["comments"].'</a></span>
			<div class="news-clear"></div>
			</div>
			<div class="news-content">' . nl2br(html_entity_decode(htmlspecialchars_decode($r['content'], ENT_QUOTES))).'</div>
			</div>';
		}
		$theme['body'] .='<div align="center" class="news-pagination">'.call('pagination', $pageNum, $rowsPerPage, 'SELECT visibility FROM news WHERE (start_time="0" || start_time<='.time().') AND (end_time="0" || end_time>='.time().')', '?act=news&page=', 3, 'visibility').'</div>';
	}
}
if(isset($_GET['readmore'])) {
	$fetch = call('getnews', $_GET['readmore']);
	if($fetch != false) {
		foreach($fetch as $r){
			$theme['head'] = call('seokeyword', $r['content']) . call('seodescription', $r['content']);
			$theme['title'] = $r['subject'];
			$theme['body'] = theme('title', $NEWS_LANG["theme_title"]) . theme('start_content');
			$theme['body'] .= '<div class="newstable"><h2>'.(@file_exists($r['cat_image']) ? '<img src="' . $r['cat_image'] . '" alt="' . $r['cat_name'] . '" title="'.$r['cat_name'].'" />' : '').'<a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '">'.$r['subject'].'</a></h2>
			<div class="news-info"><span class="news-date">'.call('dateformat', $r['time_created']).'</span>
			<span class="news-author">'.call('userprofilelink', $r['created_by']).'</span>
			<span class="news-views">'.$r['views'].' '.$NEWS_LANG["views"].'</span>
			<span class="news-comments"><a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '#comments">'.$r['comment_count'].' '.$NEWS_LANG["comments"].'</a></span>
			<div class="news-clear"></div>
			</div>
			<div class="news-content">' . nl2br(html_entity_decode(htmlspecialchars_decode($r['content'], ENT_QUOTES))).'<br />'.nl2br(html_entity_decode(htmlspecialchars_decode($r['extended'], ENT_QUOTES))).'</div>
			</div>';
			if($r['ratings'] == '1')
				$theme['body'] .= call('displayrating', $_GET['readmore'], 'news'); 
			if($r['comments'] == '1')
				$theme['body'] .= call('displaycomment', $_GET['readmore'], 'news');
		}
	}
}
$theme['body'] .= theme('end_content');
?>