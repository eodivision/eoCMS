function showPM(id)
{
	$("#ajax").html("Loading...");
	$("#ajax").slideToggle();	
$.ajax({type: "POST", url:"index.php?act=ajax", data:"m=showmessage&id="+id, success:function(data){
	$("#ajax").slideToggle();	
   $("#showmessage").html(data)
  }, dataType: "text", error: function (XMLHttpRequest, textStatus, errorThrown) {
		$("#ajax").html("Error unable to load the message due to "+XMLHttpRequest.status+". Please Try Again!");  
  }});
}