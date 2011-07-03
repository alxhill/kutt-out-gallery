/**
 * Javascript debouncer plugin. MOVE TO PLUGINS.JS!
 */
(function($,sr){var debounce=function(func,threshold,execAsap){var timeout;return function debounced(){var obj=this,args=arguments;function delayed(){if(!execAsap)
func.apply(obj,args);timeout=null;};if(timeout)
clearTimeout(timeout);else if(execAsap)
func.apply(obj,args);timeout=setTimeout(delayed,threshold||100);};}
jQuery.fn[sr]=function(fn){return fn?this.bind('resize',debounce(fn)):this.trigger(sr);};})(jQuery,'smartresize');

/**
 * Javascript function to decode HTML entities. MOVE TO PLUGINS.JS
 */
function html_entity_decode(str) {
  var ta=document.createElement("textarea");
  ta.innerHTML=str.replace(/</g,"&lt;").replace(/>/g,"&gt;");
  return ta.value;
}

$(function() {
		
	/**
	 * Code to manage page resizing.
	 */
	var hh = 340;
	var $home_content = $('#home_content');
	var $content = $('#content');
	var $scroller = $('#scroller');
	var $window = $(window);
	function setup() {
		$scroller.simplyScroll({
	        autoMode: 'loop',
	        pauseOnHover: false,
	        speed: 1,
			frameRate: 35,
			//jsonSource: '/gallery/gallery/ajax_most_recent'
		});
	}
	setup();
	$(window).smartresize(setup);
	
	/**
	 * Manages AJAX deletion of photos & videos in gallery edit views.
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
	
	$('.videos .delete_link').click(function(){
		var sure = confirm('Are you sure you want to delete this video?');
		if(sure === true){
			var video_id = $(this).attr('id');
			
			$.post('/gallery/gallery/ajax_delete', { id: video_id, type: "video" }, function(data){
				if (data.code === 0)
				{
					$('#action').html('The video with ID ' + data.id + ' ("' + data.title + '") was deleted successfully.');
					$('tr#vid_id_' + video_id).hide('slow');
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
	 * Manages AJAX editing of photos & videos in gallery edit views.
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
	
	$('.videos .edit_link').click(function(){
		var v_id = $(this).attr('id');
		var title = $('td#video_title_' + v_id + '.editable');
		var description = $('td#video_description_' + v_id + '.editable');
		var edit_link = $('.edit_link#' + v_id);
		if (edit_link.html() === "Edit")
		{
			title.attr('contenteditable','true');
			title.css('border','1px solid #cdcdcd');
			
			description.attr('contenteditable','true');
			description.css('border','1px solid #cdcdcd');
			
			edit_link.html('Save');
		}
		else if (edit_link.html() === 'Save')
		{
			title.attr('contenteditable','false');
			title.css('border','none');
			
			description.attr('contenteditable','false');
			description.css('border','none');
			
			edit_link.html('Edit');
			$.post('/gallery/gallery/ajax_update', { id: v_id, title: title.html(), description: description.html(), type: "video" }, function(data){
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

	var is_out = false,
    	$video_div = $('#video_view'),
    	$iframe = $video_div.find('iframe'),
    	$title = $video_div.find('h3'),
    	$description = $video_div.find('p');
	
	/**
	 * Does the sexy magic on video pages.
	 */
	$('.video_container a').click(function(el){
		el.preventDefault();
		
		var v_url = $(this).attr('href'),
			$clicked = $(this);

		if ($iframe.attr('src') === v_url)
		{
			return;
		}
		else
		{
			$iframe.attr('height', ($window.height()-(hh + 50) ) );
			if (!is_out)
			{
				$iframe.css('display', 'none');
				$title.css('display', 'none');
				$video_div.css({
					display: 'inline',
					'padding-left': 0,
					'padding-right': 0,
					'padding-top': 0,
					'padding-bottom': 0,
					width: 0
				})
				.animate({
					'padding-top': '10px',
					'padding-right': '10px',
					'padding-left': '60px',
					width: '450px'
				},
				500,
				function(){
					set_content(v_url, $clicked.attr('title'), html_entity_decode($clicked.find('img').attr('title')));
					$iframe.fadeIn('slow');
					$title.fadeIn('slow');
				});
				is_out = true;
			}
			else
			{
				$title.fadeOut().delay(20);
				$iframe.fadeOut().delay(20);
				set_content(v_url, $clicked.attr('title'), html_entity_decode($clicked.find('img').attr('title')));
				$iframe.delay(20).fadeIn();
				$title.delay(20).fadeIn();
			}
		}
	});
	
	// Set the necessary content in the div
	function set_content(url, title, description)
	{
		$iframe.attr('src', url);
		$title.html(title);
		$description.html(description);
	}
	
	// Toggle the custom thumbnail checkbox
	$('.custom_thumb').toggle();
	$("input[name='custom_thumbnail']").change(function() {
		$('.custom_thumb').toggle();
	});
	
	
});