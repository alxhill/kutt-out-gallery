<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?=site_url('/assets/css/screen.css')?>" media="screen, projection" >
<!--[if lt IE 8]><link rel="stylesheet" href="<?=site_url('/assets/css/ie.css')?>" type="text/css" media="screen, projection"><![endif]-->
<title>Image uploaded!</title>
</head>
<body>
<div class="container">

<div class="span-24 last">
<?= isset($message) ? show_messages($class,$message) : null ?>
<h1>Image:</h1>
<img alt="image" src="<?=$link ?>">
</div>

</div>
</body>
</html>
