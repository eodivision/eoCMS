<?php
$links = call('adminlinks');

$Xaphire['admin_main']    = array('start','edit','manage','user');
$Xaphire['admin_start']   = array('home','forum','write','layout');
$Xaphire['admin_edit']    = array('news','articles','pages','links','smileys');
$Xaphire['admin_manage']  = array('defaults','boards','plugins','panels','layout');
$Xaphire['admin_user']    = array('activate','users','opts','bans','mods');
$Xaphire['admin_default'] = array('articles','news','mods');

/*
$Xaphire['size'] = $menu_options['size'];
$Xaphire['style'] = $menu_options['style'];
$Xaphire['panels'] = $menu_options['panels'];
$Xaphire['sub_menu_size'] = $menu_options['sub_menu_size'];
$Xaphire['sub_menu_style'] = $menu_options['sub_menu_style'];
$Xaphire['sub_menu_panels'] = $menu_options['sub_menu_panels'];
*/

$Xaphire['size'] = 128;
$Xaphire['style'] = 'default';
$Xaphire['panels'] = 'none';
$Xaphire['sub_menu_size'] = 16;
$Xaphire['sub_menu_style'] = 'top-left';
$Xaphire['sub_menu_panels'] = 'from_settings';

$menu_link = 'index.php?act=admin&amp;opt=';
$menu_auth = '&amp;'.$authid;

if(isset($_GET['opt']) && $_GET['opt']==''){

$xaphire_size = $Xaphire['size'];
$xaphire_large = $xaphire_size + round(($xaphire_size/100)*16);
$xaphire_padding = round(($xaphire_size/100)*8);
$xaphire_under = round(($xaphire_size/100)*16);

$menu = <<<HTML
<table border="0" cellpadding="0" cellspacing="0" style="margin-top: 15px;" width="100%" onClick="hideAdmin();">
<style type="text/css">
.admin-icon {
padding:{$xaphire_padding}px;
float:left;
}
.menu-border{
float:left;
}
.mouse-under{
height:{$xaphire_under}px;
}
</style>
<script language=javascript>
q = '"';
current_Hilte = '';
cur_sub_Hilte = '';
function showAdmin(xObj,xText){
  hideAll();
  xObj.style.display='inline';
  mouseover_text.innerHTML = xText;
}
function showMenuText(xMenuText){
  mouseover_text.innerHTML = xMenuText;
}
function hideAdmin(){
  hideAll();
  a_default.style.display = 'inline';
}
function hideAll(){
  mouseover_text.innerHTML   = '';
  a_starthere.style.display    = 'none';
  a_editcontent.style.display  = 'none';
  a_sitesettings.style.display = 'none';
  a_usersettings.style.display = 'none';
  a_default.style.display      = 'none';
}
function hilite(xWorkWith){
  eval('clearTimeout(h_' + xWorkWith.id + ');');
  xOpacity = parseInt(xWorkWith.getAttribute('op'));
  if(xOpacity==100) return;
  xOpacity = xOpacity + 10;
  if(xOpacity>100) xOpacity = 100;
  if(window.innerWidth){
    xWorkWith.style.opacity = (xOpacity/100).toString();
  }else{
    xWorkWith.style.filter = 'alpha(opacity='+xOpacity+')';
  }
  xWorkWith.setAttribute('op',xOpacity);
  eval('h_' + xWorkWith.id + '=setTimeout(\'hilite(' + xWorkWith.id + ')\',39)');
}
function lolite(xWorkWith){
  eval('clearTimeout(h_' + xWorkWith.id + ');');
  xOpacity = parseInt(xWorkWith.getAttribute('op'));
  if(xOpacity==50) return;
  xOpacity = xOpacity - 5;
  if(xOpacity<50) xOpacity = 50;
  if(window.innerWidth){
    xWorkWith.style.opacity = (xOpacity/100);
  }else{
    xWorkWith.style.filter = 'alpha(opacity='+xOpacity+')';
  }
  xWorkWith.setAttribute('op',xOpacity);
  eval('h_' + xWorkWith.id + '=setTimeout(\'lolite(' + xWorkWith.id + ')\',' + (Math.floor(Math.random()*11)+31) + ')');
}
</script>
  <tr>
    <td width="100%" valign="top"><div class="titlebg" align="center">{$title}</div></td>
  </tr>
  <tr><td height=30 onMouseOver="hideAdmin();">&nbsp;</td></tr>
  <tr>
    <td width="100%" height="100%" valign=top align=center>
	<table align=center id=admin_main><tr>
	  <td><table class="menu-border">
	    <tr><td width={$xaphire_under} height={$xaphire_large} onMouseOver="hideAdmin();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/starthere.png" id=admin_main_start op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_start); showAdmin(a_starthere,'start here, step by step'); h_admin_main_start=setTimeout('hilite(admin_main_start);',15);" onMouseOut="clearTimeout(h_admin_main_start); h_admin_main_start=setTimeout('lolite(admin_main_start);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=starthere></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/fileshare.png" id=admin_main_edit op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_edit); showAdmin(a_editcontent,'edit Site Content'); h_admin_main_edit=setTimeout('hilite(admin_main_edit);',15);" onMouseOut="clearTimeout(h_admin_main_edit); h_admin_main_edit=setTimeout('lolite(admin_main_edit);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=editcontent></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/Service Manager.png" id=admin_main_manage op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_manage); showAdmin(a_sitesettings,'edit Site Settings'); h_admin_main_manage=setTimeout('hilite(admin_main_manage);',15);" onMouseOut="clearTimeout(h_admin_main_manage); h_admin_main_manage=setTimeout('lolite(admin_main_manage);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=sitesettings></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/Login Manager.png" id=admin_main_user op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_user); showAdmin(a_usersettings,'edit User Settings'); h_admin_main_user=setTimeout('hilite(admin_main_user);',15);" onMouseOut="clearTimeout(h_admin_main_user); h_admin_main_user=setTimeout('lolite(admin_main_user);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=usersettings></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_main_start = document.getElementById('admin_main_start'); h_admin_main_start = null;
admin_main_edit = document.getElementById('admin_main_edit'); h_admin_main_edit = null;
admin_main_manage = document.getElementById('admin_main_manage'); h_admin_main_manage = null;
admin_main_user = document.getElementById('admin_main_user'); h_admin_main_user = null;
starthere = document.getElementById('starthere');
editcontent = document.getElementById('editcontent');
sitesettings = document.getElementById('sitesettings');
usersettings = document.getElementById('usersettings');
</script></td></tr></table>
<table width=100%><tr><td height=128 witdh=100% id=mouseover_text align=center valign=middle style="font-size:26pt;"></td></tr></table>
	<table id=a_starthere align=center style="display:none;" op="50"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();start_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('edit HOME page');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}pages&amp;type=edit&amp;id=1&amp;Xaphire=admin_start_forum{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kfm_home.png" id=admin_start_home op="100" onMouseOver="start_lolite('');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=shome height=20></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('add Forum Boards');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}forum&amp;Xaphire=admin_start_forum{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kfm.png" id=admin_start_forum op="100" onMouseOver="start_lolite('forum');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=sforum></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('set Site Defaults');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}articles&amp;Xaphire=admin_start_write{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/lists.png" id=admin_start_write op="100" onMouseOver="start_lolite('write');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=swrite></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('set Layout Defaults');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}sitesettings&amp;Xaphire=admin_start_layout{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/colors.png" id=admin_start_layout op="100" onMouseOver="start_lolite('layout');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=slayout></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();start_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_start_home   = document.getElementById('admin_start_home');   h_admin_start_home   = null;
admin_start_forum  = document.getElementById('admin_start_forum');  h_admin_start_forum  = null;
admin_start_write  = document.getElementById('admin_start_write');  h_admin_start_write  = null;
admin_start_layout = document.getElementById('admin_start_layout'); h_admin_start_layout = null;
shome = document.getElementById('shome');
sforum = document.getElementById('sforum');
swrite = document.getElementById('swrite');
slayout = document.getElementById('slayout');
mouseover_text = document.getElementById('mouseover_text');

function start_rehilite(){
  clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('hilite(admin_start_home);',150);
  clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('hilite(admin_start_forum);',150);
  clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('hilite(admin_start_write);',150);
  clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('hilite(admin_start_layout);',150);
}
function start_lolite(xMatch){
  if(xMatch=='layout'){
    clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('lolite(admin_start_home);',5);
    clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('lolite(admin_start_forum);',5);
    clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('lolite(admin_start_write);',5);
    return;
  }else if(xMatch=='write'){
    clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('lolite(admin_start_home);',5);
    clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('lolite(admin_start_forum);',5);
    clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('lolite(admin_start_layout);',5);
    return;
  }else if(xMatch=='forum'){
    clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('lolite(admin_start_home);',5);
    clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('lolite(admin_start_write);',5);
    clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('lolite(admin_start_layout);',5);
    return;
  }else{
    clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('lolite(admin_start_forum);',5);
    clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('lolite(admin_start_write);',5);
    clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('lolite(admin_start_layout);',5);
  }
}
</script></td></tr></table>
	<table id=a_editcontent align=center style="display:none;"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();edit_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('edit News');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}news&amp;Xaphire=admin_edit_news{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/date.png" id=admin_edit_news op="100" onMouseOver="edit_lolite('');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=enews height=20></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('edit Articles');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}articles&amp;Xaphire=admin_edit_articles{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kcmfontinst.png" id=admin_edit_articles op="100" onMouseOver="edit_lolite('articles');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=articles></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('edit Pages');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}pages&amp;Xaphire=admin_edit_pages{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/netjaxer.png" id=admin_edit_pages op="100" onMouseOver="edit_lolite('pages');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=pages></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('edit Links');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}links&amp;Xaphire=admin_edit_links{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/browser.png" id=admin_edit_links op="100" onMouseOver="edit_lolite('links');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=elinks></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('edit Smileys');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}emoticons&amp;Xaphire=admin_edit_smileys{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/ksmiletris.png" id=admin_edit_smileys op="100" onMouseOver="edit_lolite('smileys');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=smileys></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();edit_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_edit_news     = document.getElementById('admin_edit_news');     h_admin_edit_news     = null;
admin_edit_articles = document.getElementById('admin_edit_articles'); h_admin_edit_articles = null;
admin_edit_pages    = document.getElementById('admin_edit_pages');    h_admin_edit_pages    = null;
admin_edit_links    = document.getElementById('admin_edit_links');    h_admin_edit_links    = null;
admin_edit_smileys  = document.getElementById('admin_edit_smileys');  h_admin_edit_smileys  = null;
enews = document.getElementById('enews');
articles = document.getElementById('articles');
pages = document.getElementById('pages');
elinks = document.getElementById('elinks');
smileys = document.getElementById('smileys');

function edit_rehilite(){
  clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('hilite(admin_edit_news);',150);
  clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('hilite(admin_edit_articles);',150);
  clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('hilite(admin_edit_pages);',150);
  clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('hilite(admin_edit_links);',150);
  clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('hilite(admin_edit_smileys);',150);
}
function edit_lolite(xMatch){
  if(xMatch=='smileys'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    return;
  }else if(xMatch=='links'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
    return;
  }else if(xMatch=='pages'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
    return;
  }else if(xMatch=='articles'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
    return;
  }else{
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
  }
}
</script></td></tr></table>
	<table id=a_sitesettings align=center style="display:none;" op="50"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();manage_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('manage Defaults');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}sitesettings&amp;Xaphire=admin_manage_defaults{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kbackgammon_engine.png" id=admin_manage_defaults op="100" onMouseOver="manage_lolite('');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=defaults height=20></td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('manage Boards');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}forum&amp;Xaphire=admin_manage_boards{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/fileshare.png" id=admin_manage_boards op="100" onMouseOver="manage_lolite('board');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=boards></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('manage Plug-ins');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}plugins&amp;Xaphire=admin_manage_plugins{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/ksirtet.png" id=admin_manage_plugins op="100" onMouseOver="manage_lolite('plugin');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=pplugins></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('manage Panels');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}panels&amp;Xaphire=admin_manage_panels{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kllckety.png" id=admin_manage_panels op="100" onMouseOver="manage_lolite('panel');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=panels></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('manage Layout');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}themes&amp;Xaphire=admin_manage_layout{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/looknfeel.png" id=admin_manage_layout op="100" onMouseOver="manage_lolite('layout');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=llayout></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();manage_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_manage_defaults = document.getElementById('admin_manage_defaults'); h_admin_manage_defaults = null;
admin_manage_boards   = document.getElementById('admin_manage_boards');   h_admin_manage_boards   = null;
admin_manage_plugins  = document.getElementById('admin_manage_plugins');  h_admin_manage_plugins  = null;
admin_manage_panels   = document.getElementById('admin_manage_panels');   h_admin_manage_panels   = null;
admin_manage_layout   = document.getElementById('admin_manage_layout');   h_admin_manage_layout   = null;
defaults = document.getElementById('defaults');
boards   = document.getElementById('boards');
pplugins = document.getElementById('pplugins');
panels   = document.getElementById('panels');
llayout  = document.getElementById('llayout');

function manage_rehilite(){
  clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('hilite(admin_manage_defaults);',150);
  clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('hilite(admin_manage_boards);',150);
  clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('hilite(admin_manage_plugins);',150);
  clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('hilite(admin_manage_panels);',150);
  clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('hilite(admin_manage_layout);',150);
}
function manage_lolite(xMatch){
  if(xMatch=='layout'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',7);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',5);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',3);
    return;
  }else if(xMatch=='panel'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',7);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
    return;
  }else if(xMatch=='plugin'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',7);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
    return;
  }else if(xMatch=='board'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',7);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
    return;
  }else{
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',9);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',7);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
  }
}
</script></td></tr></table>
	<table id=a_usersettings align=center style="display:none;"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin(); users_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('activate Users');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}users&amp;Xaphire=admin_user_activate{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kwallet.png" id=admin_user_activate op="100" onMouseOver="users_lolite('');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=activate></td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('forum Users');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}users&amp;Xaphire=admin_user_users{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kdmconfig.png" id=admin_user_users op="100" onMouseOver="users_lolite('users');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=users></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('user Options');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}themes&amp;Xaphire=admin_user_opts{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kuser.png" id=admin_user_opts op="100" onMouseOver="users_lolite('opts');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=uoptions></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('user IP Bans');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}bans&amp;Xaphire=admin_user_bans{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kgpg.png" id=admin_user_bans op="100" onMouseOver="users_lolite('bans');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=bans></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('user Permissions');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}permissions&amp;Xaphire=admin_user_mods{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/password.png" id=admin_user_mods op="100" onMouseOver="users_lolite('mods');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=upermissions></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin(); users_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_user_activate = document.getElementById('admin_user_activate'); h_admin_user_activate = null;
admin_user_users    = document.getElementById('admin_user_users');    h_admin_user_users = null;
admin_user_opts     = document.getElementById('admin_user_opts');     h_admin_user_opts = null;
admin_user_bans     = document.getElementById('admin_user_bans');     h_admin_user_bans = null;
admin_user_mods     = document.getElementById('admin_user_mods');     h_admin_user_mods = null;
activate = document.getElementById('activate');
users = document.getElementById('users');
uoptions = document.getElementById('uoptions');
bans = document.getElementById('bans');
upermissions = document.getElementById('upermissions');

function users_rehilite(){
  clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('hilite(admin_user_activate);',150);
  clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('hilite(admin_user_users);',150);
  clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('hilite(admin_user_opts);',150);
  clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('hilite(admin_user_bans);',150);
  clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('hilite(admin_user_mods);',150);
}
function users_lolite(xMatch){
  if(xMatch=='mods'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',15);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',10);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',5);
    return;
  }else if(xMatch=='bans'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',15);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',10);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
    return;
  }else if(xMatch=='opts'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',15);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',10);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
    return;
  }else if(xMatch=='users'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',15);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',5);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
    return;
  }else{
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',20);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',15);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',10);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
  }
}
</script></td></tr></table>
	<table id=a_default align=center style=""><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="showMenuText(''); default_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('write An Article');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}articles&amp;Xaphire=admin_default_articles{$menu_auth}"><img class="admin-icon-image" id=admin_default_articles op="100" src="{$theme_path}icons/128x128/kedit.png" onMouseOver="default_lolite('');" onMouseOut="default_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=aarticales></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('add News Item');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}news&amp;Xaphire=admin_default_news{$menu_auth}"><img class="admin-icon-image" id=admin_default_news op="100" src="{$theme_path}icons/128x128/knewsticker.png" onMouseOver="default_lolite('news');" onMouseOut="default_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=nnews></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('moderators');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}permissions&amp;Xaphire=admin_default_mods{$menu_auth}"><img class="admin-icon-image" id=admin_default_mods op="100" src="{$theme_path}icons/128x128/password.png" onMouseOver="default_lolite('mods');" onMouseOut="default_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=mods></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="showMenuText(''); default_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_default_articles = document.getElementById('admin_default_articles'); h_admin_default_articles = null;
admin_default_news = document.getElementById('admin_default_news'); h_admin_default_news = null;
admin_default_mods = document.getElementById('admin_default_mods'); h_admin_default_mods = null;
aarticales = document.getElementById('aarticales');
nnews = document.getElementById('nnews');
mods = document.getElementById('mods');

function default_rehilite(){
  clearTimeout(h_admin_default_articles); h_admin_default_articles=setTimeout('hilite(admin_default_articles);',150);
  clearTimeout(h_admin_default_news);     h_admin_default_news=setTimeout('hilite(admin_default_news);',150);
  clearTimeout(h_admin_default_mods);     h_admin_default_mods=setTimeout('hilite(admin_default_mods);',150);
}
function default_lolite(xMatch){
  if(xMatch=='mods'){
    clearTimeout(h_admin_default_articles); h_admin_default_articles=setTimeout('lolite(admin_default_articles);',5);
    clearTimeout(h_admin_default_news);     h_admin_default_news=setTimeout('lolite(admin_default_news);',5);
    return;
  }else if(xMatch=='news'){
    clearTimeout(h_admin_default_mods);     h_admin_default_mods=setTimeout('lolite(admin_default_mods);',5);
    clearTimeout(h_admin_default_articles); h_admin_default_articles=setTimeout('lolite(admin_default_articles);',5);
    return;
  }else{
    clearTimeout(h_admin_default_mods);     h_admin_default_mods=setTimeout('lolite(admin_default_mods);',5);
    clearTimeout(h_admin_default_news);     h_admin_default_news=setTimeout('lolite(admin_default_news);',5);
  }
}

a_starthere    = document.getElementById('a_starthere');
a_editcontent  = document.getElementById('a_editcontent');
a_sitesettings = document.getElementById('a_sitesettings');
a_usersettings = document.getElementById('a_usersettings');
a_default      = document.getElementById('a_default');

</script></td>
      </tr>
    </table></td>
  </tr>
  <tr><td onMouseOver="hideAdmin();">&nbsp;</td></tr>
</table>
HTML;
} else {
# SUBMENU

$xaphire_size = $Xaphire['sub_menu_size'];
$xaphire_large = $xaphire_size + round(($xaphire_size/100)*16);
$xaphire_padding = round(($xaphire_size/100)*8);
$xaphire_under = round(($xaphire_size/100)*16);

$xaphire_width = $xaphire_size<=16 ? (5*16)+80 : (5*($xaphire_size+($xaphire_padding*2)))+(2*10);
$xaphire_text = round((20/100)*(100/(128/$xaphire_size)))+6;

$menu = <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 15px;">
  <tr>
    <td colspan="2" width="100%" valign="top"><div class="titlebg-left"></div><div class="titlebg" align="center">{$title}</div></td>
  </tr>
  <tr>
<td width="10%" valign="top"><table border="0" cellpadding="0" cellspacing="0" style="margin-top: 15px;" width="{$xaphire_width}" onClick="hideAdmin();">
<style type="text/css">
.admin-icon {
padding:{$xaphire_padding}px;
float:left;
}
.menu-border{
float:left;
}
.mouse-under{
height:{$xaphire_under}px;
}
</style>
<script language=javascript>
q = '"';
current_Hilte = '';
cur_sub_Hilte = '';
function showAdmin(xObj,xText){
  hideAll();
  xObj.style.display='inline';
  mouseover_text.innerHTML = xText;
}
function showMenuText(xMenuText){
  mouseover_text.innerHTML = xMenuText;
}
function hideAdmin(){
  hideAll();
  a_default.style.display = 'inline';
}
function hideAll(){
  mouseover_text.innerHTML   = '';
  a_starthere.style.display    = 'none';
  a_editcontent.style.display  = 'none';
  a_sitesettings.style.display = 'none';
  a_usersettings.style.display = 'none';
  a_default.style.display      = 'none';
}
function hilite(xWorkWith){
  eval('clearTimeout(h_' + xWorkWith.id + ');');
  xOpacity = parseInt(xWorkWith.getAttribute('op'));
  if(xOpacity==100) return;
  xOpacity = xOpacity + 10;
  if(xOpacity>100) xOpacity = 100;
  if(window.innerWidth){
    xWorkWith.style.opacity = (xOpacity/100).toString();
  }else{
    xWorkWith.style.filter = 'alpha(opacity='+xOpacity+')';
  }
  xWorkWith.setAttribute('op',xOpacity);
  eval('h_' + xWorkWith.id + '=setTimeout(\'hilite(' + xWorkWith.id + ')\',39)');
}
function lolite(xWorkWith){
  eval('clearTimeout(h_' + xWorkWith.id + ');');
  xOpacity = parseInt(xWorkWith.getAttribute('op'));
  if(xOpacity==50) return;
  xOpacity = xOpacity - 5;
  if(xOpacity<50) xOpacity = 50;
  if(window.innerWidth){
    xWorkWith.style.opacity = (xOpacity/100);
  }else{
    xWorkWith.style.filter = 'alpha(opacity='+xOpacity+')';
  }
  xWorkWith.setAttribute('op',xOpacity);
  eval('h_' + xWorkWith.id + '=setTimeout(\'lolite(' + xWorkWith.id + ')\',' + (Math.floor(Math.random()*11)+31) + ')');
}
</script>
  <tr><td width="100%" valign="top" onMouseOver="hideAdmin();">&nbsp;</td></tr>
  <tr>
    <td width="100%" height="100%" valign=top align=center>
	<table align=center id=admin_main><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/starthere.png" id=admin_main_start op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_start); showAdmin(a_starthere,'start here, step by step'); h_admin_main_start=setTimeout('hilite(admin_main_start);',15);" onMouseOut="clearTimeout(h_admin_main_start); h_admin_main_start=setTimeout('lolite(admin_main_start);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=starthere></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/fileshare.png" id=admin_main_edit op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_edit); showAdmin(a_editcontent,'edit Site Content'); h_admin_main_edit=setTimeout('hilite(admin_main_edit);',15);" onMouseOut="clearTimeout(h_admin_main_edit); h_admin_main_edit=setTimeout('lolite(admin_main_edit);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=editcontent></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/Service Manager.png" id=admin_main_manage op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_manage); showAdmin(a_sitesettings,'edit Site Settings'); h_admin_main_manage=setTimeout('hilite(admin_main_manage);',15);" onMouseOut="clearTimeout(h_admin_main_manage); h_admin_main_manage=setTimeout('lolite(admin_main_manage);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=sitesettings></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size}>
	    <tr><td><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/Login Manager.png" id=admin_main_user op="50" style="opacity:.5;filter:alpha(opacity=50);" onMouseOver="clearTimeout(h_admin_main_user); showAdmin(a_usersettings,'edit User Settings'); h_admin_main_user=setTimeout('hilite(admin_main_user);',15);" onMouseOut="clearTimeout(h_admin_main_user); h_admin_main_user=setTimeout('lolite(admin_main_user);',15);"></td></tr>
	    <tr><td class="mouse-under" align=center id=usersettings></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_main_start = document.getElementById('admin_main_start'); h_admin_main_start = null;
admin_main_edit = document.getElementById('admin_main_edit'); h_admin_main_edit = null;
admin_main_manage = document.getElementById('admin_main_manage'); h_admin_main_manage = null;
admin_main_user = document.getElementById('admin_main_user'); h_admin_main_user = null;
starthere = document.getElementById('starthere');
editcontent = document.getElementById('editcontent');
sitesettings = document.getElementById('sitesettings');
usersettings = document.getElementById('usersettings');
</script></td></tr></table>
<table width=100%><tr><td height={$xaphire_size} witdh=100% id=mouseover_text align=center valign=middle style="font-size:{$xaphire_text}pt;"></td></tr></table>
	<table id=a_starthere align=center style="display:none;" op="50"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();start_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('edit HOME page');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}pages&amp;type=edit&amp;id=1&amp;Xaphire=admin_start_forum{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kfm_home.png" id=admin_start_home op="100" onMouseOver="start_lolite('');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=shome height=20></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('add Forum Boards');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}forum&amp;Xaphire=admin_start_forum{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kfm.png" id=admin_start_forum op="100" onMouseOver="start_lolite('forum');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=sforum></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('set Site Defaults');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}articles&amp;Xaphire=admin_start_write{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/lists.png" id=admin_start_write op="100" onMouseOver="start_lolite('write');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=swrite></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('set Layout Defaults');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}sitesettings&amp;Xaphire=admin_start_layout{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/colors.png" id=admin_start_layout op="100" onMouseOver="start_lolite('layout');" onMouseOut="start_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=slayout></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();start_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_start_home   = document.getElementById('admin_start_home');   h_admin_start_home   = null;
admin_start_forum  = document.getElementById('admin_start_forum');  h_admin_start_forum  = null;
admin_start_write  = document.getElementById('admin_start_write');  h_admin_start_write  = null;
admin_start_layout = document.getElementById('admin_start_layout'); h_admin_start_layout = null;
shome = document.getElementById('shome');
sforum = document.getElementById('sforum');
swrite = document.getElementById('swrite');
slayout = document.getElementById('slayout');
mouseover_text = document.getElementById('mouseover_text');

function start_rehilite(){
  clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('hilite(admin_start_home);',150);
  clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('hilite(admin_start_forum);',150);
  clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('hilite(admin_start_write);',150);
  clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('hilite(admin_start_layout);',150);
}
function start_lolite(xMatch){
  if(xMatch=='layout'){
    clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('lolite(admin_start_home);',5);
    clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('lolite(admin_start_forum);',5);
    clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('lolite(admin_start_write);',5);
    return;
  }else if(xMatch=='write'){
    clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('lolite(admin_start_home);',5);
    clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('lolite(admin_start_forum);',5);
    clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('lolite(admin_start_layout);',5);
    return;
  }else if(xMatch=='forum'){
    clearTimeout(h_admin_start_home);   h_admin_start_home   = setTimeout('lolite(admin_start_home);',5);
    clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('lolite(admin_start_write);',5);
    clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('lolite(admin_start_layout);',5);
    return;
  }else{
    clearTimeout(h_admin_start_forum);  h_admin_start_forum  = setTimeout('lolite(admin_start_forum);',5);
    clearTimeout(h_admin_start_write);  h_admin_start_write  = setTimeout('lolite(admin_start_write);',5);
    clearTimeout(h_admin_start_layout); h_admin_start_layout = setTimeout('lolite(admin_start_layout);',5);
  }
}
</script></td></tr></table>
	<table id=a_editcontent align=center style="display:none;"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();edit_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('edit News');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}news&amp;Xaphire=admin_edit_news{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/date.png" id=admin_edit_news op="100" onMouseOver="edit_lolite('');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=enews height=20></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('edit Articles');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}articles&amp;Xaphire=admin_edit_articles{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kcmfontinst.png" id=admin_edit_articles op="100" onMouseOver="edit_lolite('articles');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=articles></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('edit Pages');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}pages&amp;Xaphire=admin_edit_pages{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/netjaxer.png" id=admin_edit_pages op="100" onMouseOver="edit_lolite('pages');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=pages></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('edit Links');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}links&amp;Xaphire=admin_edit_links{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/browser.png" id=admin_edit_links op="100" onMouseOver="edit_lolite('links');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=elinks></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('edit Smileys');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}emoticons&amp;Xaphire=admin_edit_smileys{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/ksmiletris.png" id=admin_edit_smileys op="100" onMouseOver="edit_lolite('smileys');" onMouseOut="edit_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=smileys></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();edit_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_edit_news     = document.getElementById('admin_edit_news');     h_admin_edit_news     = null;
admin_edit_articles = document.getElementById('admin_edit_articles'); h_admin_edit_articles = null;
admin_edit_pages    = document.getElementById('admin_edit_pages');    h_admin_edit_pages    = null;
admin_edit_links    = document.getElementById('admin_edit_links');    h_admin_edit_links    = null;
admin_edit_smileys  = document.getElementById('admin_edit_smileys');  h_admin_edit_smileys  = null;
enews = document.getElementById('enews');
articles = document.getElementById('articles');
pages = document.getElementById('pages');
elinks = document.getElementById('elinks');
smileys = document.getElementById('smileys');

function edit_rehilite(){
  clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('hilite(admin_edit_news);',150);
  clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('hilite(admin_edit_articles);',150);
  clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('hilite(admin_edit_pages);',150);
  clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('hilite(admin_edit_links);',150);
  clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('hilite(admin_edit_smileys);',150);
}
function edit_lolite(xMatch){
  if(xMatch=='smileys'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    return;
  }else if(xMatch=='links'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
    return;
  }else if(xMatch=='pages'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
    return;
  }else if(xMatch=='articles'){
    clearTimeout(h_admin_edit_news);     h_admin_edit_news     = setTimeout('lolite(admin_edit_news);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
    return;
  }else{
    clearTimeout(h_admin_edit_articles); h_admin_edit_articles = setTimeout('lolite(admin_edit_articles);',15);
    clearTimeout(h_admin_edit_pages);    h_admin_edit_pages    = setTimeout('lolite(admin_edit_pages);',15);
    clearTimeout(h_admin_edit_links);    h_admin_edit_links    = setTimeout('lolite(admin_edit_links);',15);
    clearTimeout(h_admin_edit_smileys);  h_admin_edit_smileys  = setTimeout('lolite(admin_edit_smileys);',15);
  }
}
</script></td></tr></table>
	<table id=a_sitesettings align=center style="display:none;" op="50"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();manage_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('manage Defaults');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}sitesettings&amp;Xaphire=admin_manage_defaults{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kbackgammon_engine.png" id=admin_manage_defaults op="100" onMouseOver="manage_lolite('');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=defaults height=20></td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('manage Boards');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}forum&amp;Xaphire=admin_manage_boards{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/fileshare.png" id=admin_manage_boards op="100" onMouseOver="manage_lolite('board');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=boards></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('manage Plug-ins');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}plugins&amp;Xaphire=admin_manage_plugins{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/ksirtet.png" id=admin_manage_plugins op="100" onMouseOver="manage_lolite('plugin');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=pplugins></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('manage Panels');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}panels&amp;Xaphire=admin_manage_panels{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kllckety.png" id=admin_manage_panels op="100" onMouseOver="manage_lolite('panel');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=panels></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('manage Layout');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}themes&amp;Xaphire=admin_manage_layout{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/looknfeel.png" id=admin_manage_layout op="100" onMouseOver="manage_lolite('layout');" onMouseOut="manage_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=llayout></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin();manage_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_manage_defaults = document.getElementById('admin_manage_defaults'); h_admin_manage_defaults = null;
admin_manage_boards   = document.getElementById('admin_manage_boards');   h_admin_manage_boards   = null;
admin_manage_plugins  = document.getElementById('admin_manage_plugins');  h_admin_manage_plugins  = null;
admin_manage_panels   = document.getElementById('admin_manage_panels');   h_admin_manage_panels   = null;
admin_manage_layout   = document.getElementById('admin_manage_layout');   h_admin_manage_layout   = null;
defaults = document.getElementById('defaults');
boards   = document.getElementById('boards');
pplugins = document.getElementById('pplugins');
panels   = document.getElementById('panels');
llayout  = document.getElementById('llayout');

function manage_rehilite(){
  clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('hilite(admin_manage_defaults);',150);
  clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('hilite(admin_manage_boards);',150);
  clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('hilite(admin_manage_plugins);',150);
  clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('hilite(admin_manage_panels);',150);
  clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('hilite(admin_manage_layout);',150);
}
function manage_lolite(xMatch){
  if(xMatch=='layout'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',7);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',5);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',3);
    return;
  }else if(xMatch=='panel'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',7);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
    return;
  }else if(xMatch=='plugin'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',7);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
    return;
  }else if(xMatch=='board'){
    clearTimeout(h_admin_manage_defaults); h_admin_manage_defaults = setTimeout('lolite(admin_manage_defaults);',9);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',7);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
    return;
  }else{
    clearTimeout(h_admin_manage_boards);   h_admin_manage_boards   = setTimeout('lolite(admin_manage_boards);',9);
    clearTimeout(h_admin_manage_plugins);  h_admin_manage_plugins  = setTimeout('lolite(admin_manage_plugins);',7);
    clearTimeout(h_admin_manage_panels);   h_admin_manage_panels   = setTimeout('lolite(admin_manage_panels);',5);
    clearTimeout(h_admin_manage_layout);   h_admin_manage_layout   = setTimeout('lolite(admin_manage_layout);',3);
  }
}
</script></td></tr></table>
	<table id=a_usersettings align=center style="display:none;"><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin(); users_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('activate Users');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}users&amp;Xaphire=admin_user_activate{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kwallet.png" id=admin_user_activate op="100" onMouseOver="users_lolite('');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=activate></td></tr>
	  </table>
	  <table class="admin-icon" width width={$xaphire_size} onMouseOver="showMenuText('forum Users');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}users&amp;Xaphire=admin_user_users{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kdmconfig.png" id=admin_user_users op="100" onMouseOver="users_lolite('users');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=users></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('user Options');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}themes&amp;Xaphire=admin_user_opts{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kuser.png" id=admin_user_opts op="100" onMouseOver="users_lolite('opts');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=uoptions></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('user IP Bans');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}bans&amp;Xaphire=admin_user_bans{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/kgpg.png" id=admin_user_bans op="100" onMouseOver="users_lolite('bans');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=bans></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('user Permissions');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}permissions&amp;Xaphire=admin_user_mods{$menu_auth}"><img class="admin-icon-image" width={$xaphire_size} src="{$theme_path}icons/128x128/password.png" id=admin_user_mods op="100" onMouseOver="users_lolite('mods');" onMouseOut="users_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=upermissions></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="hideAdmin(); users_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_user_activate = document.getElementById('admin_user_activate'); h_admin_user_activate = null;
admin_user_users    = document.getElementById('admin_user_users');    h_admin_user_users = null;
admin_user_opts     = document.getElementById('admin_user_opts');     h_admin_user_opts = null;
admin_user_bans     = document.getElementById('admin_user_bans');     h_admin_user_bans = null;
admin_user_mods     = document.getElementById('admin_user_mods');     h_admin_user_mods = null;
activate = document.getElementById('activate');
users = document.getElementById('users');
uoptions = document.getElementById('uoptions');
bans = document.getElementById('bans');
upermissions = document.getElementById('upermissions');

function users_rehilite(){
  clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('hilite(admin_user_activate);',150);
  clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('hilite(admin_user_users);',150);
  clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('hilite(admin_user_opts);',150);
  clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('hilite(admin_user_bans);',150);
  clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('hilite(admin_user_mods);',150);
}
function users_lolite(xMatch){
  if(xMatch=='mods'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',15);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',10);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',5);
    return;
  }else if(xMatch=='bans'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',15);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',10);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
    return;
  }else if(xMatch=='opts'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',15);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',10);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
    return;
  }else if(xMatch=='users'){
    clearTimeout(h_admin_user_activate); h_admin_user_activate=setTimeout('lolite(admin_user_activate);',20);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',15);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',5);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
    return;
  }else{
    clearTimeout(h_admin_user_users);    h_admin_user_users=setTimeout('lolite(admin_user_users);',20);
    clearTimeout(h_admin_user_opts);     h_admin_user_opts=setTimeout('lolite(admin_user_opts);',15);
    clearTimeout(h_admin_user_bans);     h_admin_user_bans=setTimeout('lolite(admin_user_bans);',10);
    clearTimeout(h_admin_user_mods);     h_admin_user_mods=setTimeout('lolite(admin_user_mods);',5);
  }
}
</script></td></tr></table>
	<table id=a_default align=center style=""><tr>
	  <td><table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="showMenuText(''); default_rehilite();">&nbsp;</td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onmouseover="showMenuText('write An Article');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}articles&amp;Xaphire=admin_default_articles{$menu_auth}"><img class="admin-icon-image" id=admin_default_articles op="100" src="{$theme_path}icons/128x128/kedit.png" onMouseOver="default_lolite('');" onMouseOut="default_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=aarticales></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('add News Item');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}news&amp;Xaphire=admin_default_news{$menu_auth}"><img class="admin-icon-image" id=admin_default_news op="100" src="{$theme_path}icons/128x128/knewsticker.png" onMouseOver="default_lolite('news');" onMouseOut="default_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=nnews></td></tr>
	  </table>
	  <table class="admin-icon" width={$xaphire_size} onMouseOver="showMenuText('moderators');">
	    <tr><td><a class="admin-icon-href" href="{$menu_link}permissions&amp;Xaphire=admin_default_mods{$menu_auth}"><img class="admin-icon-image" id=admin_default_mods op="100" src="{$theme_path}icons/128x128/password.png" onMouseOver="default_lolite('mods');" onMouseOut="default_rehilite();"></a></td></tr>
	    <tr><td class="mouse-under" align=center id=mods></td></tr>
	  </table>
	  <table class="menu-border">
	    <tr><td height={$xaphire_large} height={$xaphire_large} onMouseOver="showMenuText(''); default_rehilite();">&nbsp;</td></tr>
	  </table><script language=javascript>
admin_default_articles = document.getElementById('admin_default_articles'); h_admin_default_articles = null;
admin_default_news = document.getElementById('admin_default_news'); h_admin_default_news = null;
admin_default_mods = document.getElementById('admin_default_mods'); h_admin_default_mods = null;
aarticales = document.getElementById('aarticales');
nnews = document.getElementById('nnews');
mods = document.getElementById('mods');

function default_rehilite(){
  clearTimeout(h_admin_default_articles); h_admin_default_articles=setTimeout('hilite(admin_default_articles);',150);
  clearTimeout(h_admin_default_news);     h_admin_default_news=setTimeout('hilite(admin_default_news);',150);
  clearTimeout(h_admin_default_mods);     h_admin_default_mods=setTimeout('hilite(admin_default_mods);',150);
}
function default_lolite(xMatch){
  if(xMatch=='mods'){
    clearTimeout(h_admin_default_articles); h_admin_default_articles=setTimeout('lolite(admin_default_articles);',5);
    clearTimeout(h_admin_default_news);     h_admin_default_news=setTimeout('lolite(admin_default_news);',5);
    return;
  }else if(xMatch=='news'){
    clearTimeout(h_admin_default_mods);     h_admin_default_mods=setTimeout('lolite(admin_default_mods);',5);
    clearTimeout(h_admin_default_articles); h_admin_default_articles=setTimeout('lolite(admin_default_articles);',5);
    return;
  }else{
    clearTimeout(h_admin_default_mods);     h_admin_default_mods=setTimeout('lolite(admin_default_mods);',5);
    clearTimeout(h_admin_default_news);     h_admin_default_news=setTimeout('lolite(admin_default_news);',5);
  }
}

a_starthere    = document.getElementById('a_starthere');
a_editcontent  = document.getElementById('a_editcontent');
a_sitesettings = document.getElementById('a_sitesettings');
a_usersettings = document.getElementById('a_usersettings');
a_default      = document.getElementById('a_default');

</script></td>
      </tr>
    </table></td>
  </tr>
  <tr><td onMouseOver="hideAdmin();">&nbsp;</td></tr>
</table></td>
<td valign=top>{$body}</td>
  </tr>
</table>
HTML;
}

?>