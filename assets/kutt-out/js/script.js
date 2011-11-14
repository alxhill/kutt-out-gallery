/**
 * Dump script - remove when used in production.
 */
function dump(arr,level){var dumped_text="";if(!level)level=0;var level_padding="";for(var j=0;j<level+1;j++)level_padding+="    ";if(typeof(arr)=='object'){for(var item in arr){var value=arr[item];if(typeof(value)=='object'){dumped_text+=level_padding+"'"+item+"' ...\n";dumped_text+=dump(value,level+1);}else{dumped_text+=level_padding+"'"+item+"' => \""+value+"\"\n";}}}else{dumped_text="===>"+arr+"<===("+typeof(arr)+")";}
return dumped_text;}
//-------[Remove when live]--------//

showMessage = function(text, type){
	$('#action').html(text).addClass().delay(3000).fadeOut('slow');
};

$(document).ready(function(){
	
	// Manages deleting and hiding photos and videos.
	$('.delete_link').click(function(){
		
		var sure = confirm('Are you sure you want to delete this video?');
		if (sure === true)
		{
			var $this = $(this);
			var type = $this.parent().parent().parent().parent().hasClass('photos') ? 'photo' : 'video';
			var id = $(this).attr('id');
			console.log([type, id]);
			$.post('/gallery/gallery/ajax_delete', { id: id, type: type }, function(data){
				console.log(data);
				if (data.code === 0)
				{
					var css = '';
					if (type == 'photo')
					{
						selector = 'tr#pic_id_' + id;
					}
					else if (type == 'video')
					{
						selector = 'tr#vid_id_' + id;
					}
					$(selector).hide('slow');
					showMessage('The '+ type +' with ID ' + data.id + ' ("' + data.title + '") was deleted successfully.', 'notice');
				}
				else
				{
					showMessage(data.message + "<br>Code:" + data.code, type);
				}
			},
			'json'
			);
		}
	});
		
	// Manage clicking the edit link and making the title for the relevant element editable, then saving that content for photos and videos.
	$('.edit_link').click(function(){
		var $this = $(this),
		 	type = $this.parent().parent().parent().parent().hasClass('photos') ? 'photo' : 'video',
			id = $this.attr('id'),
			title;
		
		if (type == 'photo')
		{
			title = $('td#title_' + id + '.editable');
		}
		else if (type == 'video')
		{
			title = $('td#video_title_' + id + '.editable');
			var description = $('td#video_description_' + id + '.editable');
		}
		var edit_link = $('a.edit_link#' + id);
		
		if (edit_link.html() === "Edit")
		{
			title.attr('contenteditable','true');
			title.css('border','1px solid #cdcdcd');
			if (type == 'video')
			{
				description.attr('contenteditable','true');
				description.css('border','1px solid #cdcdcd');
			}
			edit_link.html('Save');
		}
		else if (edit_link.html() === 'Save')
		{
			title.attr('contenteditable','false');
			title.css('border','none');
			
			var dataObj = { id: id, title: title.html(), type: type };
			
			if (type == 'video')
			{
				description.attr('contenteditable','false');
				description.css('border','none');
				dataObj.description = description.html();
			}
			
			edit_link.html('Edit');
			$.post('/gallery/gallery/ajax_update', dataObj, function(data){
				if (data.code === 1)
				{
					showMessage(data.message, 'error');
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
				if (data.code === 0)
				{
					$('li#gallery_' + g_id).fadeOut('slow');
					$('li a.nav_link#g_' + g_id).fadeOut('slow');
					showMessage(data.message, 'notice');
				}
				else
				{
					showMessage(data.message, 'error');
				}
			},
			'json'
			);
		}
					
	});
	
	$('.success, .error, .notice').delay(3000).fadeOut('slow');
	
	$('.photos, .videos').tableDnD({
		dragHandle: 'dragger',
		onDrop: function(table, row) {
            $.post('/gallery/gallery/ajax_reorder', $.tableDnD.serialize(), function(data) {
				if (data.code == 1) {
					showMessage(data.message, 'error');
				}
				else if (data.code == 0)
				{
					showMessage('Reorder was successful.', 'info');
				}
			});
		}
	});
	
	// Manages redordering and AJAX changing of gallery order.
	$('#galleries_list').ListReorder({dragTargetClass: 'gallery_drag'}).bind('listorderchanged', function(evt, jq_list, list_order) {
		
		var post_array = [];
		
		jq_list.children().each(function(index) {
			post_array[index] = $(this).attr('id');
		});
				
		$.post('/gallery/gallery/ajax_reorder_galleries', { gallery: post_array }, function(data) {
			if (data.code == 0)
			{
				showMessage('Reorder was successful.', 'info');
			}
			else if (data.code == 3)
			{
				showMessage('Please visit the <a href="/gallery/login">login page</a> and try again.', 'info');
			}
		});
	});
	
	$('.custom_thumb').toggle();
	
	$("input[name='custom_thumbnail']").change(function() {
		$('.custom_thumb').toggle();
	});
	
	$('#new_category').click(function(){
		$('#cat_title, #cat_submit').removeClass('hidden');
		$(this).addClass('hidden');
	});
	
	$('#cat_submit').click(function(){
		var $this = $(this);
		var $title = $('#cat_title');
		$.post('/gallery/gallery/ajax_create_category', {title: $title.val()}, function(data){
			if (data.code === 0)
			{
				$this.addClass('hidden');
				$title.addClass('hidden');
				$('#new_category').removeClass('hidden').val('');
				
				$('select[name="category"]').append($('<option>', {value: data.id}).text($title.val())).val(data.id);
				
				showMessage('Category created successfully', 'success');
			}
		});
	});
		
});