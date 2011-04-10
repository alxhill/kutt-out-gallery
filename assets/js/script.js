$(document).ready(function(){
	
	// Fixes the nav bar spacing and front image display in browsers with older versions of WebKit.
	
	
	if ((parseInt($.browser.version, 10) < 534) && ($.browser.webkit))
	{
		$('#out, #studios').css('margin-top','-10px');
		$('img#home').css('margin-top','-250px');
	}
	
	// Manages deleting and hiding photos.
	$('.photos .delete_link').click(function(){
		var sure = confirm('Are you sure you want to delete this image?');
		if(sure === true){
			var photo_id = $(this).attr('id');
			$.post('/gallery/gallery/ajax_delete', { id: photo_id, type: "photo" }, function(data){
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
	
	// Manages deleting and hiding videos.
	$('.video .delete_link').click(function(){
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
	
	
	// Manage clicking the edit link and making the title for the relevant element editable, then saving that content - for photos.
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
	
	$('.video .edit_link').click(function(){
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
	
	
	// Manages deleting and removing of galleries.
	$('.g_delete_link').click(function(){
		var sure = confirm('Are you sure you want to delete this gallery? This cannot be undone.');
		if(sure === true){
			var g_id = $(this).attr('id');
			$.post('/gallery/gallery/ajax_gallery_delete', { id: g_id }, function(data){
				$('div#action').html(data.message);
				if (data.code === 0)
				{
					$('li#gallery_' + g_id).fadeOut('slow');
					$('li a.nav_link#g_' + g_id).fadeOut('slow');
					$('#action').addClass('notice').delay(3000).fadeOut('slow');
				}
				else
				{
					$('#action').addClass('error').delay(3000).fadeOut('slow');
				}
			},
			'json'
			);
		}
					
	});
	
	$('.success, .error, .notice').delay(3000).fadeOut('slow');
	
	$('.photos').tableDnD({
		dragHandle: 'dragger'
	});
	
});