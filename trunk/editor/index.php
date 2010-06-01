<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Untitled Document</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="jquery.editor.js"></script>
<script type="text/javascript">
myOptions = {
	Set: [
			{name: 'Bold', shortcutKey: 'Ctrl+B', openTag: '[b]', closeTag: '[/b]', image: '../themes/default/images/bold.png'},
			{}
		  ]};
	$(function(){
    	$("#message").editor(myOptions);
	});
</script>
</head>

<body><div align="center"><textarea id="message" rows="20" cols="70"></textarea></div>
</body>
</html>