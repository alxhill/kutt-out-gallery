<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/gallery/assets/css/screen.css" media="screen, projection" >
<link rel="stylesheet" href="/gallery/assets/css/lightbox.css" type="text/css" media="screen" />
<!--[if lt IE 8]><link rel="stylesheet" href="'/gallery/assets/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="/gallery/assets/css/custom.css" type="text/css" media="screen" />
<script type="text/javascript" src="/gallery/assets/js/prototype.js"></script>
<script type="text/javascript" src="/gallery/assets/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="/gallery/assets/js/lightbox.js"></script>

<title>Kutt Out Studios // <?=$title?></title>
</head>
<body>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>
<div class='span-8' id='nav'>
	<ul>
		<li><a href='<?=site_url()?>'><div id='kutt'>Kutt</div><div id='out'>Out</div><div id='studios'>Studios</div></a></li>
		<li><a href='/gallery/portraits'>Portraits</a></li>
		<li><a href='#'>Landscapes</a></li>
		<li id="middle"><a href='#'>Digital</a></li>
		<li><a href='#'>Videos</a></li>
		<li><a href='#'>Contact</a></li>
		<li><a href='#'><div id='billy'>Billy</div><div id='boyd'>Boyd</div><div id='cape'>Cape</div></a></li>
	</ul>
</div>