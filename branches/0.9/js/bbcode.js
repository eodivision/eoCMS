function bbcode_ins(fieldId, tag)
{
field=document.getElementById(fieldId);
if (document.selection) 
{
field.focus();
var selected = document.selection.createRange().text;
sel = document.selection.createRange();
sel.text = '' + tag + '' + selected + '';
}
//MOZILLA/NETSCAPE/SAFARI support
else if (field.selectionStart || field.selectionStart == 0) 
{
var startPos = field.selectionStart;
var endPos = field.selectionEnd;
var selected = field.value.substring(startPos, endPos);
field.focus();
field.value = field.value.substring(0, startPos) + '' + tag + '' + selected + '' + field.value.substring(endPos, field.value.length);
}
	}
function InsertQuote(postid, type, previousdata, quotetype)
{
	$("#ajax").html("Loading...");
	$("#ajax").slideToggle();
	var form = document.forms[type];
$.ajax({type:"POST", url:"index.php?act=ajax", data:"postid="+postid+"&m=quote&type="+quotetype+"&formstuff="+escape($("#message").val()),
  success:function(data){
   $(form).populate({"message": data});
	$("#ajax").slideToggle();	
  }, dataType: "text", error: function (XMLHttpRequest, textStatus, errorThrown) {
		$("#ajax").html("Error unable to load quote due to "+XMLHttpRequest.status+". Please Try Again!");  
  }});
}