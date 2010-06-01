var msgcache = {};
var subcache = {};
function QuickEdit(id)
{
	$("#ajax").html("Loading...");
	$("#ajax").slideToggle();	
	msgcache[id] =  $("#viewmessage_" + id).html();
	subcache[id] =  $("#subject_" + id).html();
	$.ajax({url:"index.php?act=ajax", type: "POST", data: "m=quickedit&postid="+id, success:
  function(data){
	  $("#ajax").slideToggle();
	$("#subject_" + id).replaceWith("<input type='text' value='" + data.subject + "' maxlength='80' id='subjectedit_" + id + "' />");
	  $("#viewmessage_" + id).replaceWith("<textarea rows='5' style='width: 100%;' id='message_" + id + "'>" + data.message + "</textarea><br /><input type='button' value='Save' onclick='return SaveEdit(" + id + ")' id='submit_" + id + "' /><input type='submit' value='Cancel' onclick='return CancelEdit(" + id + ")' id='cancel_" + id + "' />");
  },  error: function (XMLHttpRequest, textStatus, errorThrown) {
		$("#ajax").html("Error unable to load the message due to "+XMLHttpRequest.status+". Please Try Again!");  
  }, dataType: "json"});
}
function SaveEdit(id)
{
		$("#ajax").html("Saving...");
	$("#ajax").slideToggle();	
$.ajax({url:"index.php?act=ajax", type:"POST", data: "m=saveedit&postid="+id+"&subject="+escape($("#subjectedit_" + id).val())+"&message="+escape($("#message_" + id).val()), success:
  function(data){
	  	  $("#ajax").slideToggle();
	$("#subjectedit_" + id).replaceWith("<div id='subject_" + id + "'>" + data.subject + "</div>");
	$("#message_" + id).replaceWith("<div id='viewmessage_" + id + "' style='overflow: auto;'>" + data.message + "</div>");
	$("#submit_" + id).replaceWith();
	$("#cancel_" + id).replaceWith();
  }, dataType: "json", error: function (XMLHttpRequest, textStatus, errorThrown) {
		$("#ajax").html("Error unable to save the message due to "+XMLHttpRequest.status+". Please Try Again!");  
  }});	
}
function CancelEdit(id)
{
	$("#subjectedit_" + id).replaceWith("<div id='subject_" + id + "'>" + subcache[id] + "</div>");
	$("#message_" + id).replaceWith("<div id='viewmessage_" + id + "' style='overflow: auto;'>" + msgcache[id] + "</div>");
	$("#submit_" + id).replaceWith();
	$("#cancel_" + id).replaceWith();
}