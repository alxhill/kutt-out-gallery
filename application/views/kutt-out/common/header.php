<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>-->
<script>
window.onload=function(){
  //CFInstall.check({mode: "overlay"});
};
</script>
<script type="text/javascript" src="/gallery/assets/kutt-out/js/jquery.min.js"></script>
<script type="text/javascript" src="/gallery/assets/kutt-out/js/plugins.js"></script>
<!--[if lt IE 8]><link rel="stylesheet" href="'/gallery/assets/kutt-out/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="/gallery/assets/kutt-out/css/custom.css" type="text/css" media="screen" />
<? if ($gallery_type == 'video'): ?>
<link rel="stylesheet" href="/gallery/assets/kutt-out/css/shadowbox.css" type="text/css" media="screen" />
<script type='text/javascript' src='/gallery/assets/kutt-out/js/shadowbox.js'></script>
<script>
Shadowbox.init();
</script>
<? elseif($gallery_type == 'photo'): ?>
<link rel="stylesheet" href="/gallery/assets/kutt-out/css/slimbox2.css" type="text/css" media="screen" charset="utf-8">
<? endif;?>
<script type='text/javascript' src='/gallery/assets/kutt-out/js/script.js'></script>
<title><?=$title?></title>
</head>
<body>
<? if ($logged_in): ?>
<div id='logged_in_link'>
<a class='l_link' href='/gallery/login/logout/' >Logout</a> - 
<a class='l_link' href='/gallery/admin' >Admin</a>
</div>
<? endif; ?>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>
<div id="title"><span class="large">kutt out</span>billy boyd cape</div>