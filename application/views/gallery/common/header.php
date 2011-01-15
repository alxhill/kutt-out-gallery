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

<title><?=$title?></title>
</head>
<body>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>