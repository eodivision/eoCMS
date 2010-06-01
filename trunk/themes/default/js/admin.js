// global error event, means we dont have to write this multiple times
$.ajaxSetup({error: function(event, request, settings) { //any errors that might occur, let the user know whats happening
	if(event.status == 0)
		ajaxerror = 'You are offline!\n Please Check Your Network Connection';
	else if(event.status == 404)
		ajaxerror = 'Requested URL not found';
	else if(event.status == 500)
		ajaxerror = 'Internel Server Error';
	else if(request == 'parsererror')
		ajaxerror = 'Parsing JSON Request failed';
	else if(request == 'timeout')
		ajaxerror = 'Request Time out';
	else
		ajaxerror = 'Unknown Error. '+event.responseText;
	if(ajaxerror) {
		$("#dialog").html(ajaxerror).dialog({title: 'Error', buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});
	}
}});/*
// this is where the magic is, it creates all the windows
function adminclick(event) {
	if(event.button != 0) {
			// wasn't the left button - ignore the click
			return false;
	}
	event.preventDefault();
	var url = $(this).attr("href");
	if(url != "#") {
		adminwindow = $.window({
						onClose: function() {
									$(this).remove();
									if($(".window_panel").length == 1)
										$(".ui-widget-overlay").remove();
						},
						content: '<div class="ajaxloading"></div>',
						title: 'Loading',
						resizable: true
		});
		windowcache[adminwindow.getWindowId()] = url;
		$.ajax({url: url+"&window="+adminwindow.getWindowId(),
				dataType: "json",
				type: "GET",
				beforeSend: function() {
					// jQuery UI dialog has problems with the overlay (modal) and multiple dialogs, lets create our own :)
					if($(".ui-widget-overlay").length == 0) { // check if the modal is there, if it isnt, create it!
						// Get the screen height and width
						var modalHeight = $(document).height();
						var modalWidth = $(window).width();
						// add in the overlay
						$(".admin-navbar-container").after('<div class="ui-widget-overlay" style="display: none"></div>');
						// fade it in, for a nice effect :)
						$(".ui-widget-overlay").fadeIn("fast");
						// Set height and width to mask to fill up the whole screen
						$('.ui-widget-overlay').css({'width': modalWidth, 'height': modalHeight});
					}
				},
				success: function(data) {
					// jQuery sets cache to false automaitically when loading external files from within the data, lets stop that :P
					var tempAJAX = $.ajax; // save the original $.ajax
        			$.ajax=function(s){ // wrap the old $.ajax so set cache to true...
						s.cache=true;
            			tempAJAX(s); // call old $.ajax
        			}
      				if(adminwindow != null) {
						adminwindow.setContent(data.body); // sets the contents of the window to that of the ajax
						adminwindow.setTitle(data.title); // same as above but sets the title
						$("#"+adminwindow.getWindowId()+" .window_frame").css("height", "100%"); // sets height to 100%, removes the scrollbar problem	}
					}
					if(data.body == '')
						closewindow($("#"+adminwindow.getWindowId())); // close it
					// add it before </head>
					$("head").append(data.head);
					// process any JavaScript code in here
					eval(data.head_js);
					// unbind the click to prevent multiple ajax post requests
					$(".window_frame").find("input[type=submit]").unbind("click");
					// bind the submit button to submit all the forms contents and the window id
					$(".window_frame").find("input[type=submit]").bind("click", function(event) {
						// grab the id
						id = $(this).offsetParent().offsetParent().attr("id");
						if(id == '')
							id = $(this).offsetParent().attr("id");
						// this is the id element, above will just reuturn window_x where as below will enable use with jQuery
						jid = $("#"+id);
						// make sure that the form is valid first
						if(jid.find("form").valid()) {
							//make the ajax request!
							$.ajax({
									url: windowcache[id],
									type: "POST",
									dataType: "json",
									data: jid.find("form").serialize()+"&"+$(this).attr("name")+"="+$(this).val()+"&window="+id,
									beforeSend: function(request) {
										// add the ajax image on top of the form
										jid.find(".window_frame").prepend('<div class="ajaxloading"></div>');
									},
									success: function(data) {
										//check for errors!
										if(data.error) {
											if(jid.find(".window-error").length != 0)
												jid.find(".window-error").remove();
											jid.find(".ajaxloading").remove();
											jid.find(".window_frame").prepend(data.error);
										} else if(data.error_die) { // this one replaces the whole contents of the window
											jid.find(".ajaxloading").remove();
											jid.find(".window_frame").html(error_die);
										} else if(data.success) {
											jid.find(".window_frame").html(data.success);
											jid.fadeOut(5000, function() { // fade the message out after 5 seconds
												closewindow(jid); // close it
											});
										} else // if no success message or anything, just close it
											closewindow(jid); // close the window!
									}
							});
						}
						event.preventDefault();
						return false;
					});
				$.ajax = tempAJAX; // reset $.ajax to the original.
				}
		});
	}
	return false;
}
$(function() {
	var container = $(".admin-navbar-container");
	var bar = $(".admin-navbar");
	container.width($(window).width()); // set the width to that of the screen
	$(window).resize(function() { // upon resize change it accordingly
		container.width($(window).width()).scrollLeft(0);
		if($(".ui-widget-overlay").length != 0) {
			var modalHeight = $(document).height();
			var modalWidth = $(window).width();
			$('.ui-widget-overlay').css({'width':modalWidth,'height':modalHeight});
		}
	});
	bar.superfish({
		dropShadows: false,
		delay: 0
	});
	$.fn.loopingAnimation = function(props, dur, eas) {
		if(this.data('loop') == true) {
		   this.animate(props, dur, eas, function() {
			   if($(this).data('loop') == true)
					$(this).loopingAnimation(props, dur, eas);
		   });
		}
		return this;
	}
	bar.width(bar.children("li").length * bar.children("li").width());
	$('.admin-navbar-container .prev').hover(function(e) {
		bar.data('loop', true).stop().loopingAnimation({"right": "+=6em"}, "fast", "linear");
	}, function() {
		bar.data('loop', false).stop();
	});
	$('.admin-navbar-container .next').hover(function(e) {
		bar.data('loop', true).stop().loopingAnimation({"right": "-=6em"}, "fast", "linear");							
	}, function() {
		bar.data('loop', false).stop();
	});
	//When user moves mouse over menu
	/*$('.admin-navbar > li').mousemove(function(e) {
		baroffset = container.width() - bar.width();
		var left_indent = (e.pageX - 30) / bar.width() * 100;
		//temp = (e.pageX - bar.offset().left) * bar.width() / container.width();
		bar.css("right", left_indent+"em");
	});
	bar.children("li").mouseout(function() {
		//bar.css('right', 'auto');					  
	});
	var adminwindow = null;
	$(".admin-link a").bind("click", adminclick);
	$("#miniglobe").bind("click", function() {
		if(bar.css("display") != "none") {
			bar.fadeOut();
			$.window.closeAll();
			$.cookie('adminnavbar', 'none', {expires: 14});
		} else {
			bar.fadeIn();
			$.cookie('adminnavbar', 'block', {expires: 14});
		}
	});
	bar.css("display", $.cookie("adminnavbar"));
	$(".sortable ul").sortable({
		items: "li:not(.ui-state-disabled)",
		revert: true,
		stop: function() {
			neworder = $(".sortable ul").sortable("serialize");
			type =  $(".sortable ul").parent("li .sortable").attr("class").match(/sortabletype-([a-z]*)/);
			$.post("index.php?act=ajax", {m: "order", order: neworder, type: type[1]}, "text");
		}
	});
	$(".sortable ul").disableSelection(); // prevent them from selecting the items
	$(".sortable a").unbind("click"); // unbind the function link set above
	$(".delete_links").bind("click", function() {
		list = $(this).parent("div").parent("ul");
		if(!$(this).hasClass("done_delete")) {
			$(this).toggleClass("done_delete");
			if(list.hasClass("ui-sortable"))
				list.sortable("disable");
			list.find("li").each(function() {
				if(!$(this).hasClass("ui-state-disabled"))
					$(this).find(".delete-icon").css("display", "inline");
			});
		} else {
			if(list.hasClass("ui-sortable"))
				list.sortable("enable");
			$(this).toggleClass("done_delete");
			list.find("li").find(".delete-icon").hide().removeClass("delete-confirm");
		}
	});
	$(".delete-icon").live("click", function(event) {
		if(event.button != 0) {
			// wasn't the left button - ignore the click
			return true;
		}
		if(!$(this).hasClass("delete-confirm"))
			$(this).toggleClass("delete-confirm");
		else if(!$(this).hasClass("delete-loading")) {
			// before we remove it we need to grab the delete url
			deleteurl = $(this).find("img").attr("alt"); 
			// we cant use the href to store it as its not possible to prevent users opening the link via middle mouse button
			$(this).find("img").remove();
			$(this).toggleClass("delete-loading");
			// assign $(this) to a variable as it changes inside the $.ajax
			current_item = $(this);
			$.ajax({
				url: deleteurl,
				type: "GET",
				dataType: "json",
				success: function(data) {
					if(data.error) {
						// will get to this in a bit
					} else if(data.error_die) {
						// and this too XD
					} else
						current_item.parent("li").fadeOut("normal").remove();	
				}
			});
		}
	});
	// thats the delete stuff done, phew, now on to the edit!
	// bind the adminclick function to them
	$(".edit_links").parent("div").parent("ul").find("li").find(".edit-icon").find("a").bind("click", adminclick);
	$(".edit_links").bind("click", function() {
		if(!$(this).hasClass("done_edit")) {
			$(this).toggleClass("done_edit");
		editlinkwidth = $(this).width();
			if($(this).parent("div").parent("ul").hasClass("ui-sortable"))
				$(this).parent("div").parent("ul").sortable("disable");
				$(this).parent("div").parent("ul").width($(this).parent("div").parent("ul").width() + editlinkwidth);
			$(this).parent("div").parent("ul").find("li").each(function() {
				if(!$(this).hasClass("ui-state-disabled"))
					$(this).find(".edit-icon").css("display", "inline");
			});
		} else {
			if($(this).parent("div").parent("ul").hasClass("ui-sortable"))
				$(this).parent("div").parent("ul").sortable("enable");
			$(this).toggleClass("done_edit");
			$(this).parent("div").parent("ul").find("li").find(".edit-icon").hide();
			$(this).parent("div").parent("ul").width($(this).parent("div").parent("ul").width() - editlinkwidth);
		}
	});
});*/
$(function() {
	$("#tabs").tabs({selected: 0});
	$(".admin-link a").bind("click", function(event) {
		if(event.button != 0)
				return false; // wasn't the left button - ignore the click
		event.preventDefault();
		var url = $(this).attr("href");
		if(url != "#") {
			$.ajax({url: url,
					dataType: "json",
					type: "GET",
					success: function(data) {
						document.title = data.title; // same as above but sets the title;
						$("#tabs").tabs('destroy').html(data.body);
						// process any JavaScript code in here
						eval(data.js);
						$("head").append(data.head);
						$("#tabs").tabs({												
							selected: 0
						});
					}
			});
		}
		return false;
	});
});