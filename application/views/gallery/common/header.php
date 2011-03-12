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
<script type="text/javascript" src="/gallery/assets/js/jquery.min.js"></script>
<!--[if lt IE 8]><link rel="stylesheet" href="'/gallery/assets/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" href="/gallery/assets/css/custom.css" type="text/css" media="screen" />
<? if ($gallery_type == 'photo'): ?>
<link rel="stylesheet" href="/gallery/assets/css/slimbox2.css" type="text/css" media="screen" />
<script type="text/javascript" src="/gallery/assets/js/slimbox2.js"></script>
<? elseif ($gallery_type == 'video'): ?>
<link rel="stylesheet" href="/gallery/assets/css/shadowbox.css" type="text/css" media="screen" />
<script type='text/javascript' src='/gallery/assets/js/shadowbox.js'></script>
<? endif; ?>
<script type='text/javascript' src='/gallery/assets/js/script.js'></script>
<title><?=$title?></title>
</head>
<body onload='test();'>
<? if ($logged_in): ?>
<div id='logged_in_link'>
<a class='l_link' href='/gallery/login/logout/' >Logout</a> - 
<a class='l_link' href='/gallery/admin' >Admin</a>
</div>
<? endif; ?>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>