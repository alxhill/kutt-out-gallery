<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>-->
<script>
// Run the Chrome frame check & script
function test() {
CFInstall.check({mode: "overlay"});
}

window.onload=test();
</script>
<link rel="stylesheet" href="/gallery/assets/css/slimbox2.css" type="text/css" media="screen" />
<!--[if lt IE 8]><link rel="stylesheet" href="'/gallery/assets/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="/gallery/assets/css/custom.css" type="text/css" media="screen" />
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>-->
<script type="text/javascript" src="/gallery/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/gallery/assets/js/slimbox2.js"></script>
<script type='text/javascript' src='/gallery/assets/js/script.js'></script>
<title>Kutt Out Studios // <?=$title?></title>
</head>
<body onload='test();'>
<?php if ($logged_in) { ?>
<div id='logged_in_link'>
<a class='l_link' href='/gallery/login/logout/' >Logout</a> - 
<a class='l_link' href='/gallery/admin' >Admin</a>
</div>
<?php } ?>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>