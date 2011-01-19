<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/gallery/assets/css/screen.css" media="screen, projection" >
<link rel="stylesheet" href="/gallery/assets/css/slimbox2.css" type="text/css" media="screen" />
<!--[if lt IE 8]><link rel="stylesheet" href="'/gallery/assets/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="/gallery/assets/css/custom.css" type="text/css" media="screen" />
<script type="text/javascript" src="/gallery/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/gallery/assets/js/slimbox2.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	$('a.delete_link').click(function(){
		var sure = confirm('Are you sure you want to delete this image?');
		if(sure==true){
			var photo_id = $(this).attr('id');
			$.post('/gallery/gallery/ajax_delete', { id: photo_id }, function(data){
				$('div#action').html(data);
				$('tr#pic_id_' + photo_id).hide('slow');
				$('div#action').addClass('notice').fadeIn();
			});
		}
	});
});
</script>
<script type="text/javascript" src="/gallery/assets/js/lightbox.js"></script>
<title>Kutt Out Studios // <?=$title?></title>
</head>
<body>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>
<div class='span-8' id='nav'>
	<ul>
		<li><a href='<?=site_url('/home')?>'><div id='kutt'>Kutt</div><div id='out'>Out</div><div id='studios'>Studios</div></a></li>
		<li><a href='/gallery/portraits'>Portraits</a></li>
		<li><a href='#'>Landscapes</a></li>
		<li id="middle"><a href='#'>Digital</a></li>
		<li><a href='#'>Videos</a></li>
		<li><a href='#'>Contact</a></li>
		<li><a href='#'><div id='billy'>Billy</div><div id='boyd'>Boyd</div><div id='cape'>Cape</div></a></li>
	</ul>
</div>