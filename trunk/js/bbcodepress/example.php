<center>
<script language=JavaScript src=bbcodepress-lite.js></script>
<script type="text/javascript" src="http://localhost/eoCMS/js/jquery.js"></script>
<?php
$textarea_name = 'dataBox';
$smiley_image_path = '../../images/emoticons/';
$bbcode_image_path = '../../images/';

# store the below info however you like, db or file

$buttons = array(
	b     => array('bold',     '[b]$0[/b]',        'bold.gif',     'ctrl+b'),
	u     => array('underline','[u]$0[/u]',        'underline.gif','ctrl+u'),
	i     => array('italic',   '[i]$0[/i]',        'italic.gif',   'ctrl+i'),
	ibr0  => '',
	url   => array('link',     '[url]$0[/url]',    'href.gif',     'ctrl+h'),
	img   => array('image',    '[img]$0[/img]',    'image.gif',    'ctrl+p'),
	ibr1  => '',
	code  => array('code',     '[code]$0[/code]',  'code.gif',     'shift+ctrl+c'),
	quote => array('quote',    '[quote]$0[/quote]','quote.gif',    'shift+ctrl+q')
);

$smileys = array(
	happy   => array('happy',  ':happy:',  'happy.gif',  'shift+ctrl+h'),
	sad     => array('sad',    ':sad:',    'sad.gif',    'shift+ctrl+s'),
	angry   => array('angry',  ':angry:',  'angry.gif',  ''),
	tonge   => array('tonge',  ':tonge:',  'tonge.gif',  ''),
	lookup  => array('lookup', ':lookup:', 'lookup.gif', ''),
	roll    => array('roll',   ':roll:',   'roll.gif',   'shift+ctrl+r'),
	evil    => array('evil',   ':evil:',   'evil.gif',   'shift+ctrl+e'),
	jesus   => array('jesus',  ':jesus:',  'jesus.gif',  ''),
	mrgreen => array('mrgreen',':mrgreen:','mrgreen.gif',''),
	br0     => ''
);

foreach($smileys as $key => $button){
	if(is_array($button)){
		if($button[3] != ''){
			$register_keys .= $textarea_name . '_cp.registerKey("' . $button[3] . '","' . $button[1] . '");' . "\r\n";
			$title = $button[0] . ' - ' . $button[3];
		}else{
			$title = $button[0];
		}
		echo '<img class=bbcode-smiley width=20 height=20 style="padding:0px;" src="' . $smiley_image_path . $button[2] . '" title=" ' . $title . ' " alt="' . $key . '" onClick="' . $textarea_name . '_cp.insertText(\'' . $button[1] . '\');" />' . "\r\n";
	}else{
		if (strstr($key,"br")==$key) echo '<br />' . "\r\n";
		elseif (strstr($key,"ibr")==$key) echo '<img class=bbcode-divider width=2 height=20 style="padding-left:10px;padding-right:10px;" />' . "\r\n";
	}
}

foreach($buttons as $key => $button){
	if(is_array($button)){
		if($button[3] != ''){
			$register_keys .= $textarea_name . '_cp.registerKey("' . $button[3] . '","' . $button[1] . '");' . "\r\n";
			$title = $button[0] . ' - ' . $button[3];
		}else{
			$title = $button[0];
		}
		echo '<img class=bbcode-button width=20 height=20 style="padding:0px;" src="' . $button_image_path . $button[2] . '" title=" ' . $title . ' " alt="' . $key . '" onClick="' . $textarea_name . '_cp.insertText(\'' . $button[1] . '\');" />' . "\r\n";
	}else{
		if (strstr($key,"br")==$key) echo '<br />' . "\r\n";
		elseif (strstr($key,"ibr")==$key) echo '<img class=bbcode-divider width=2 height=20 style="padding-left:10px;padding-right:10px;" />' . "\r\n";
	}
}

# for loops above cold be a function

if($register_keys)
  $register_keys = "<script id={$textarea_name}_regKeys >\r\nfunction {$textarea_name}_Keys(){\r\n" . $register_keys . "}\r\n</script>\r\n";
?>
<form action='' method=POST onSubmit="return(false)">
       <textarea rows=22 cols=80 class="bbcodepress" id="<?=$textarea_name ?>" style="border: 2px black solid; width: expression(document.body.clientWidth-100); height: expression(document.body.clientHeight-100);"></textarea>
</form>
<script language=JavaScript>
function fixResize(){
	if (window.innerHeight){
		xObj = document.getElementById('dataBox');
		xObj.style.width = window.innerWidth-100;
		xObj.style.height = window.innerHeight-100;
	}
}
fixResize();
window.onresize = fixResize;
</script>
<?=$register_keys ?>
</center>