<!DOCTYPE html>
<html>

<head>
<style type="text/css">

#titleform, #uploadform {
	margin-bottom: 5px;
}

</style>
<link rel="stylesheet" type="text/css" href="<?=site_url('/assets/css/screen.css')?>" media="screen, projection" >
<!--[if lt IE 8]><link rel="stylesheet" href="<?=site_url('/assets/css/ie.css')?>" type="text/css" media="screen, projection"><![endif]-->
<title>Upload a new photo</title>
</head>

<body>
<div class="container">
<?=isset($message) ? show_messages($class,$message) : null ?>
<div class="span-24 last">
<h1>Select a photo:</h1>
<?=form_open_multipart('gallery/upload'); ?>
<div id="titleform">
<label>Title:</label><br>
<input type="text" name="title" id="title">
</div>
<div id="uploadform">
<label>Select photo:</label><br>
<input type="file" name="photo" id="photo"><br>
</div>
<input type="submit" name="submit" value="Upload">
</form>

</div>

</div>
</body>

</html>