<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/gallery/assets/css/slimbox2.css" type="text/css" media="screen" />
<!--[if lt IE 8]><link rel="stylesheet" href="'/gallery/assets/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="/gallery/assets/css/custom.css" type="text/css" media="screen" />
<script type="text/javascript" src="/gallery/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/gallery/assets/js/slimbox2.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	$('a.revert_link').hide(0);
	
	// Manages deleting and removing elements.
	$('a.delete_link').click(function(){
		var sure = confirm('Are you sure you want to delete this image?');
		if(sure==true){
			var photo_id = $(this).attr('id');
			$.post('/gallery/gallery/ajax_delete', { id: photo_id }, function(data){
				$('span#action').html(data);
				$('tr#pic_id_' + photo_id).hide('slow');
				$('span#action').addClass('notice');
			});
		}
	});
	
	// Manage clicking the edit link and making the title for the relevant element editable, then saving that content.
	$('a.edit_link').click(function(){
		var p_id = $(this).attr('id');
		var title = $('td#title_' + p_id + '.editable');
		var edit_link = $('a.edit_link#' + p_id);
		if (edit_link.html() == "Edit")
		{
			original = title.html();
			title.attr('contenteditable','true');
			title.css('border','1px solid #cdcdcd');
			edit_link.html('Save');
		}
		else if (edit_link.html() == 'Save')
		{
			title.attr('contenteditable','false');
			title.css('border','none');
			edit_link.html('Edit');
			$.post('/gallery/gallery/ajax_update', { id: p_id, title: title.html() });
		}
	});
});
</script>
<title>Kutt Out Studios // <?=$title?></title>
</head>
<body>
<?php if ($logged_in) { ?>
<a id='logout_link' href='/gallery/login/logout/' >Logout</a> - 
<a id='admin_link'href='/gallery/admin' >Admin</a>
<?php } ?>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>