<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Kutt Out Studios</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width,initial-scale=1">
  
  <script src="/gallery/assets/kutt-out/js/libs/jquery-1.6.2.min.js"></script>
  <script src="/gallery/assets/kutt-out/js/plugins.js"></script>
  <script src="/gallery/assets/kutt-out/js/script.js"></script>
  
	<link rel="stylesheet" href="/gallery/assets/kutt-out/css/style.css">
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

<div id="container">
  <? if ($logged_in): ?>
  <div id='logged_in_link'>
  <a class='l_link' href='/gallery/login/logout/' >Logout</a> - 
  <a class='l_link' href='/gallery/admin' >Admin</a>
  </div>
  <? endif; ?>
  
  <?=isset($message) ? show_messages($class,$message) : null ?>
  
	<header>
    <a href="/gallery/"><h1 class="left">kutt out</h1><h1 class="right">billy boyd cape</h1></a>
	</header>
	<div id="main" role="main">