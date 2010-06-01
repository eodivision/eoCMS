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
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if (!isset($_GET['readmore'])) {
	$title = $NEWS_LANG["title"];
	// how many rows to show per page
	$rowsPerPage = 5;
	// by default we show first page
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
		$pageNum = $_GET['page'];
	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
	$body = theme('title', $NEWS_LANG["theme_title"]).theme('start_content');
	$fetch = call('news');
	if($fetch != false) {
		$head = '<link rel="alternate" type="application/rss+xml" title="Latest News" href="'.$settings['site_url'].'/index.php?act=feeds&type=news&export=rss" />';
		foreach($fetch as $r) {
			$body .= '<div class="newstable"><h2>'.(@file_exists($r['cat_image']) ? '<img src="' . $r['cat_image'] . '" alt="' . $r['cat_name'] . '" title="'.$r['cat_name'].'" />' : '').'<a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '">'.$r['subject'].'</a></h2>
			<div class="news-info"><span class="news-date">'.call('dateformat', $r['time_created']).'</span>
			<span class="news-author">'.call('userprofilelink', $r['created_by']).'</span>
			<span class="news-views">'.$r['views'].' '.$NEWS_LANG["views"].'</span>
			<span class="news-comments"><a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '#comments">'.$r['comments'].' '.$NEWS_LANG["comments"].'</a></span>
			<div class="news-clear"></div>
			</div>
			<div class="news-content">' . nl2br(html_entity_decode(htmlspecialchars_decode($r['content'], ENT_QUOTES))).'</div>
			</div>';
		}
		$body .='<div align="center" class="news-pagination">'.call('pagination', $pageNum, $rowsPerPage, 'SELECT visibility FROM news WHERE (start_time="0" || start_time<='.time().') AND (end_time="0" || end_time>='.time().')', '?act=news&page=', 3, 'visibility').'</div>';
	}
}
if(isset($_GET['readmore'])) {
	$fetch = call('getnews', $_GET['readmore']);
	if($fetch != false) {
		foreach($fetch as $r){
			$head = call('seokeyword', $r['content']) . call('seodescription', $r['content']);
			$title = $r['subject'];
			$body = theme('title', $NEWS_LANG["theme_title"]) . theme('start_content');
			$body .= '<div class="newstable"><h2>'.(@file_exists($r['cat_image']) ? '<img src="' . $r['cat_image'] . '" alt="' . $r['cat_name'] . '" title="'.$r['cat_name'].'" />' : '').'<a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '">'.$r['subject'].'</a></h2>
			<div class="news-info"><span class="news-date">'.call('dateformat', $r['time_created']).'</span>
			<span class="news-author">'.call('userprofilelink', $r['created_by']).'</span>
			<span class="news-views">'.$r['views'].' '.$NEWS_LANG["views"].'</span>
			<span class="news-comments"><a href="'.$settings['site_url'].'/index.php?act=news&readmore=' . $r['news_id'] . '#comments">'.$r['comment_count'].' '.$NEWS_LANG["comments"].'</a></span>
			<div class="news-clear"></div>
			</div>
			<div class="news-content">' . nl2br(html_entity_decode(htmlspecialchars_decode($r['content'], ENT_QUOTES))).'<br />'.nl2br(html_entity_decode(htmlspecialchars_decode($r['extended'], ENT_QUOTES))).'</div>
			</div>';
			if($r['ratings'] == '1')
				$body .= call('displayrating', $_GET['readmore'], 'news'); 
			if($r['comments'] == '1')
				$body .= call('displaycomment', $_GET['readmore'], 'news');
		}
	}
}
$body .= theme('end_content');
?>