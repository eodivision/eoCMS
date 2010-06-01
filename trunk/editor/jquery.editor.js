(function($){
    $.fn.extend({ 
        editor: function(settings) {
			options = {};
			$.extend(options, settings);
			return this.each(function() {
				//asign the element we are using to ta for ease
				var ta = $(this);
				//this function turns it into the editor
				function build() {
					ta.wrap('<div id="editor-container"></div>');
					$('<div id="editor-header"></div>').insertBefore(ta);
					buttonBuild(settings.Set);
				}
				function buttonBuild(settings) {
					var ul = $('<ul></ul>');
					$('li:hover > ul', ul).css('display', 'block');
					$(settings).each(function() {
						$('<li><a title="'+this.name+'"><img src="'+this.image+'" alt="'+this.name+'" /></a></li>').appendTo(ul);
					});
					$('#editor-header').html(ul);
				}
				function insert(data) {
					ta.val(data);
				}
				build();
			});
        }
    });
})(jQuery);