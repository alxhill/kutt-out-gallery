<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?=site_url('/assets/css/screen.css')?>" media="screen, projection" >
<!--[if lt IE 8]><link rel="stylesheet" href="<?=site_url('/assets/css/ie.css')?>" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" type="text/css" href="<?=site_url('/assets/css/custom.css') ?>">
<title><?=$title?></title>
</head>
<body>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>