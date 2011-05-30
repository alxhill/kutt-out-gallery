/**
 * Javascript debouncer plugin. MOVE TO PLUGINS.JS!
 */
(function($,sr){var debounce=function(func,threshold,execAsap){var timeout;return function debounced(){var obj=this,args=arguments;function delayed(){if(!execAsap)
func.apply(obj,args);timeout=null;};if(timeout)
clearTimeout(timeout);else if(execAsap)
func.apply(obj,args);timeout=setTimeout(delayed,threshold||100);};}
jQuery.fn[sr]=function(fn){return fn?this.bind('resize',debounce(fn)):this.trigger(sr);};})(jQuery,'smartresize');

$(function() {
	
	/**
	 * Code to manage page resizing.
	 */
	// variable to store the number of pixels to take off the height of the main content.
	var hh = 340;
	var $home_content = $('#home_content');
	var $content = $('#content');
	var $scroller = $('#scroller');
	var $window = $(window);
	function setup(changesize) {
		if (arguments.length > 0 && changesize)
		{
			$content.height($window.height()-hh);
			$home_content.length > 0 && $home_content.height($window.height()-(hh-20));
		}
		$scroller.simplyScroll({
	        autoMode: 'loop',
	        pauseOnHover: false,
	        speed: 1,
			frameRate: 35,
			startOnLoad: false
		});
				
	}
	setup(false);
	//$(window).smartresize(setup);
	
	/**
	 * Manages AJAX deletion of photos in gallery edit views.
	 */
	$('.photos .delete_link').click(function(){
		var sure = confirm('Are you sure you want to delete this image?');
		if(sure === true)
		{
			var photo_id = $(this).attr('id');
			$.post('/gallery/gallery/ajax_delete', { id: photo_id, type: "photo" }, function(data)
			{
				if (data.code === 0)
				{
					$('#action').html('The image with ID ' + data.id + ' ("' + data.title + '") was deleted successfully.');
					$('tr#pic_id_' + photo_id).hide('slow');
					$('#action').addClass('notice').delay(3000).fadeOut('slow');
				}
				else
				{
					$('#action').html(data.message).addClass('error').delay(3000).fadeOut('slow');
				}
			},
			'json'
			);
		}
	});
	
	/**
	 * Manages AJAX editing of photos in gallery edit views.
	 */
	$('.photos .edit_link').click(function(){
		var p_id = $(this).attr('id');
		var title = $('td#title_' + p_id + '.editable');
		var edit_link = $('a.edit_link#' + p_id);
		if (edit_link.html() === "Edit")
		{
			title.attr('contenteditable','true');
			title.css('border','1px solid #cdcdcd');
			edit_link.html('Save');
		}
		else if (edit_link.html() === 'Save')
		{
			title.attr('contenteditable','false');
			title.css('border','none');
			edit_link.html('Edit');
			$.post('/gallery/gallery/ajax_update', { id: p_id, title: title.html(), type: "photo" }, function(data){
				if (data.code === 1)
				{
					$('#action').html(data.message);
					$('#action').addClass('error').delay(3000).fadeOut('slow');
				}
			},
			'json'
			);
		}
	});	
	
	/**
	 * Manages AJAX redoredering of photos & videos in gallery edit views.
	 */
	$('.photos, .videos').tableDnD({
		dragHandle: 'dragger',
		onDrop: function(table, row) {
            $.post('/gallery/gallery/ajax_reorder', $.tableDnD.serialize(), function(data) {
				if (data.code == 1) {
					$('#action').html(data.message).addClass('error').delay(3000).fadeOut('slow');
				}
				else if (data.code == 0)
				{
					$('#action').html('Reorder was successful.').addClass('info').delay(3000).fadeOut('slow');
				}
			});
		}
	});

	/**
	 * Does magic on video pages.
	 */
	$('.video_container a').click(function(el){
		el.preventDefault();
		
		 var v_url = $(this).attr('href'),
			$video_div = $('#video_view'),
			$iframe = $video_div.find('iframe');
		
		if ($iframe.attr('src') === v_url)
		{
			return;
		}
		else
		{
			$iframe.attr('src', v_url);
			$iframe.attr('height', ($window.height()-(hh-40)) );
			$video_div.css('display','inline');
		}
	});
	
	// Toggle the custom thumbnail checkbox
	$('.custom_thumb').toggle();
	$("input[name='custom_thumbnail']").change(function() {
		$('.custom_thumb').toggle();
	});
	
	
});