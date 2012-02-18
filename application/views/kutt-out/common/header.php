<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel="stylesheet" href="/gallery/assets/kutt-out/css/960_12_col.css">
	<link rel="stylesheet" href="/gallery/assets/kutt-out/css/style.css">
  
  <script src="/gallery/assets/kutt-out/js/libs/jquery-1.6.2.min.js"></script>
  <script src="/gallery/assets/kutt-out/js/plugins.js"></script>
  <script src="/gallery/assets/kutt-out/js/script.js"></script>
  
  <? if ($gallery_type == 'video'): ?>
  <link rel="stylesheet" href="/gallery/assets/kutt-out/css/shadowbox.css" type="text/css" media="screen" />
  <script>
  Shadowbox.init();
  </script>
  <? elseif($gallery_type == 'photo'): ?>
  <link rel="stylesheet" href="/gallery/assets/kutt-out/css/slimbox2.css" type="text/css" media="screen" charset="utf-8">
  <? endif;?>
  
	<script src="/gallery/assets/kutt-out/js/libs/modernizr-2.0.6.min.js"></script>
</head>
<body>
<? if ($logged_in): ?>
<div id='logged_in_link'>
<a href='/gallery/login/logout/'>logout</a> - 
<a href='/gallery/admin'>admin</a>
</div>
<? endif; ?>

<div id="container">
  <?=isset($message) ? show_messages($class,$message) : null ?>
	<header>
    <h1 class="caps">billy boyd cape</h1>
	</header>
	<div id="main" role="main"  class="container_12">