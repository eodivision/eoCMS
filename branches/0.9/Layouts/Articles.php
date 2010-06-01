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
if(!isset($_GET['sa']))
	$_GET['sa'] = '';
switch($_GET['sa']) {
	default;
		$title = $ARTICLES_LANG["title"]  ;
		$body = theme('title', $ARTICLES_LANG["theme_title"]  ) . theme('start_content');
		$fetch = call('article_cats');
		if($fetch != false) {
			$body.= '<table cellpadding="0" cellspacing="0" width="100%"><tr>';
			$i=0;
			foreach($fetch as $r) {
				if ($i != 0 && ($i % 2 == 0))
					$body.='</tr><tr>';
				$body.= '<td width="50%"><a href="'.$settings['site_url'].'/index.php?act=articles&amp;sa=cat&amp;cat='.$r['cat_id'].'">'.$r['name'].'</a> <span class="small-text">('.$r['articles'].')</span><br /><span class="small-text">'.$r['description'].'</span></td>';
				$i++;
			}
	 		$body.= '</tr></table>';
		} else
			$body .= $ARTICLES_LANG["no_cat_defined"];
	break;
	case'cat';
		$fetch = call('article_cat', $_GET['cat']);
		if($fetch != false) {
			$catname = call('sql_fetch_array', call('sql_query', "SELECT name FROM article_categories WHERE id = '".$_GET['cat']."'"));
			$title = $catname['name'];
			$body = theme('title', $catname['name']) . theme('start_content');
			foreach($fetch as $p) {
				$body.= '<div class="subtitlebg"><a href="'.$settings['site_url'].'/index.php?act=articles&amp;sa=article&amp;article='.$p['id'].'">'.$p['subject'].'</a></div><em>'.html_entity_decode(htmlspecialchars_decode($p['summary'], ENT_QUOTES)).'</em><br /><br />';
				}
		}
	break;
	case'article';
		$fetch = call('article', $_GET['article']);
		if($fetch != false) {
			foreach($fetch as $p) {
				$body = theme('title', $p['subject']) . theme('start_content');
				$title = $p['subject'];
				$body.= nl2br(html_entity_decode(htmlspecialchars_decode($p['full_article'], ENT_QUOTES))).'<div class="article-info">
				<div class="article-author">'.$p['author'].'</div>
				<div class="article-date">'.$p['time_created'].'</div>
				<div class="article-views">'.$p['views'].'</div>
				</div>
				<br />';
				if($p['ratings'] == '1')
					$body .= call('displayrating', $_GET['article'], 'articles'); 
				if($p['comments'] == '1')
					$body .= call('displaycomment', $_GET['article'], 'articles');
			}
		}
	break;
}
$body .= theme('end_content');
?>