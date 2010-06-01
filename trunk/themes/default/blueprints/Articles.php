<?php
/* eoCMS is a content management system written in php
    Copyright (C) 2007 - 2009  James Mortemore, Ryan Matthews
    http://www.eocms.com
	This work is licensed under the Creative Commons Attribution-Share Alike 3.0 United States License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/us/ 			    or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
	Additional license terms at http://eocms.com/license.html
*/
if(!(defined("IN_ECMS"))) { die("Hacking Attempt..."); }
if(!isset($_GET['sa']))
	$_GET['sa'] = '';
switch($_GET['sa']) {
	default;
		$theme['title'] = $ARTICLES_LANG["title"]  ;
		$theme['body'] = theme('title', $ARTICLES_LANG["theme_title"]  ) . theme('start_content');
		$fetch = call('article_cats');
		if($fetch != false) {
			$theme['body'].= '<table cellpadding="0" cellspacing="0" width="100%"><tr>';
			$i=0;
			foreach($fetch as $r) {
				if ($i != 0 && ($i % 2 == 0))
					$theme['body'].='</tr><tr>';
				$theme['body'].= '<td width="50%"><a href="'.$settings['site_url'].'/index.php?act=articles&amp;sa=cat&amp;cat='.$r['cat_id'].'">'.$r['name'].'</a> <span class="small-text">('.$r['articles'].')</span><br /><span class="small-text">'.$r['description'].'</span></td>';
				$i++;
			}
	 		$theme['body'].= '</tr></table>';
		} else
			$theme['body'] .= $ARTICLES_LANG["no_cat_defined"];
	break;
	case'cat';
		$fetch = call('article_cat', $_GET['cat']);
		if($fetch != false) {
			$catname = call('sql_fetch_array', call('sql_query', "SELECT name FROM article_categories WHERE id = '".$_GET['cat']."'"));
			$theme['title'] = $catname['name'];
			$theme['body'] = theme('title', $catname['name']) . theme('start_content');
			foreach($fetch as $p) {
				$theme['body'].= '<div class="subtitlebg"><a href="'.$settings['site_url'].'/index.php?act=articles&amp;sa=article&amp;article='.$p['id'].'">'.$p['subject'].'</a></div><em>'.html_entity_decode(htmlspecialchars_decode($p['summary'], ENT_QUOTES)).'</em><br /><br />';
				}
		}
	break;
	case'article';
		$fetch = call('article', $_GET['article']);
		if($fetch != false) {
			foreach($fetch as $p) {
				$theme['body'] = theme('title', $p['subject']) . theme('start_content');
				$theme['title'] = $p['subject'];
				$theme['body'].= nl2br(html_entity_decode(htmlspecialchars_decode($p['full_article'], ENT_QUOTES))).'<div class="article-info">
				<div class="article-author">'.$p['author'].'</div>
				<div class="article-date">'.$p['time_created'].'</div>
				<div class="article-views">'.$p['views'].'</div>
				</div>
				<br />';
				if($p['ratings'] == '1')
					$theme['body'] .= call('displayrating', $_GET['article'], 'articles'); 
				if($p['comments'] == '1')
					$theme['body'] .= call('displaycomment', $_GET['article'], 'articles');
			}
		}
	break;
}
$theme['body'] .= theme('end_content');
?>